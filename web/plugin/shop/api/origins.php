<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use Exception;
use ArrayObject;

class origins extends \engine\objects\api {


    private $_statuses = array('ACTIVE', 'REMOVED');
    // -----------------------------------------------
    // -----------------------------------------------
    // ORIGINS
    // -----------------------------------------------
    // -----------------------------------------------

    private function __adjustOrigin (&$origin) {
        $origin['ID'] = intval($origin['ID']);
        $origin['_isRemoved'] = $origin['Status'] === 'REMOVED';
        return $origin;
    }

    public function getOriginByID ($originID) {
        if (empty($originID) || !is_numeric($originID))
            return null;
        $config = $this->getPluginConfiguration()->data->jsapiShopGetOriginItem($originID);
        $origin = $this->getCustomer()->fetch($config);
        if (empty($origin))
            return null;
        return $this->__adjustOrigin($origin);
    }

    public function getOriginByName ($originName) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetOriginItem();
        $config['condition']['Name'] = $this->getPluginConfiguration()->data->createCondition($originName);
        $origin = $this->getCustomer()->fetch($config);
        if (empty($origin))
            return null;
        return $this->__adjustOrigin($origin);
    }

    public function getOrigins_List (array $options = array()) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetOriginList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getOriginByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createOrigin ($reqData) {
        $result = array();
        $errors = array();
        $success = false;
        $OriginID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 200),
            'Description' => array('string', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateOrigin = $this->getPluginConfiguration()->data->jsapiShopCreateOrigin($validatedValues);

                $this->getCustomerDataBase()->beginTransaction();
                $OriginID = $this->getCustomer()->fetch($configCreateOrigin) ?: null;
                // var_dump($OriginID);

                if (empty($OriginID))
                    throw new Exception('OriginCreateError');

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($OriginID))
            $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateOrigin ($OriginID, $reqData) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 200),
            'Description' => array('string', 'skipIfUnset'),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (count($validatedValues)) {
                    $this->getCustomerDataBase()->beginTransaction();
                    $configCreateCategory = $this->getPluginConfiguration()->data->jsapiShopUpdateOrigin($OriginID, $validatedValues);
                    $this->getCustomer()->fetch($configCreateCategory);
                    $this->getCustomerDataBase()->commit();
                }

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function disableOrigin ($OriginID) {
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();

            $config = $this->getPluginConfiguration()->data->jsapiShopDeleteOrigin($OriginID);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
            $errors[] = 'OriginUpdateError';
        }

        $result = $this->getOriginByID($OriginID);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function get (&$resp, $req) {
        if (empty($req->get['id'])) {
            $resp = $this->getOrigins_List($req->get);
        } else {
            $OriginID = intval($req->get['id']);
            $resp = $this->getOriginByID($OriginID);
        }
    }

    public function post (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createOrigin($req->data);
        // $this->_getOrSetCachedState('changed:origin', true);
    }

    public function patch (&$resp, $req) {
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (empty($req->get['id'])) {
            $resp['error'] = 'MissedParameter_id';
        } else {
            $OriginID = intval($req->get['id']);
            $resp = $this->updateOrigin($OriginID, $req->data);
            // $this->_getOrSetCachedState('changed:origin', true);
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
            $OriginID = intval($req->get['id']);
            $resp = $this->disableOrigin($OriginID);
            // $this->_getOrSetCachedState('changed:origin', true);
        }
    }
}


?>