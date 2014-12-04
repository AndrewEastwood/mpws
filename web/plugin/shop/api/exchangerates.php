<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class exchangerates extends \engine\objects\api {

    private $_statuses = array('ACTIVE', 'DISABLED', 'REMOVED');
    // -----------------------------------------------
    // -----------------------------------------------
    // DELIVERY AGENCIES
    // -----------------------------------------------
    // -----------------------------------------------
    public function getExchangeRateByID ($agencyID) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRateByID($agencyID);
        $data = $this->getCustomer()->fetch($config);
        $data['ID'] = intval($data['ID']);
        $data['_isRemoved'] = $data['Status'] === 'REMOVED';
        $data['_isActive'] = $data['Status'] === 'ACTIVE';
        return $data;
    }

    public function getExchangeRates_List (array $options = array()) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetExchangeRatesList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getExchangeRateByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createExchangeRate ($reqData) {
        $result = array();
        $errors = array();
        $success = false;
        $rateID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 200),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateOrigin = $this->getPluginConfiguration()->data->jsapiShopCreateExchangeRate($validatedValues);

                $this->getCustomerDataBase()->beginTransaction();
                $rateID = $this->getCustomer()->fetch($configCreateOrigin) ?: null;

                if (empty($rateID))
                    throw new Exception('DeliveryCreateError');

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($rateID))
            $result = $this->getExchangeRateByID($rateID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateExchangeRate ($id, $reqData) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $configCreateCategory = $this->getPluginConfiguration()->data->jsapiShopUpdateExchangeRate($id, $validatedValues);
                $this->getCustomer()->fetch($configCreateCategory);

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getExchangeRateByID($id);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function deleteExchangeRate ($id) {
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();

            $config = $this->getPluginConfiguration()->data->jsapiShopDeleteExchangeRate($id);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = 'OriginUpdateError';
        }

        $result = $this->getExchangeRateByID($id);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // WRAPPERS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getActiveExchangeRatesList () {
        $deliveries = $this->getExchangeRates_List(array(
            "limit" => 0,
            "_fStatus" => "ACTIVE"
        ));
        return $deliveries;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        if (empty($req->get['id'])) {
            $resp = $this->getExchangeRates_List($req->get);
        } else {
            $agencyID = intval($req->get['id']);
            $resp = $this->getExchangeRateByID($agencyID);
        }
    }

    public function post (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createExchangeRate($req->data);
        // $this->_getOrSetCachedState('changed:agencies', true);
    }

    public function patch (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $agencyID = intval($req->get['id']);
            $resp = $this->updateExchangeRate($agencyID, $req->data);
            // $this->_getOrSetCachedState('changed:agencies', true);
        }
    }

    public function delete (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $agencyID = intval($req->get['id']);
            $resp = $this->deleteExchangeRate($agencyID);
            // $this->_getOrSetCachedState('changed:agencies', true);
        }
    }
}

?>