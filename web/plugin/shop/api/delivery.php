<?php
namespace web\plugin\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class delivery {

    private $_statuses = array('ACTIVE', 'DISABLED', 'REMOVED');
    // -----------------------------------------------
    // -----------------------------------------------
    // DELIVERY AGENCIES
    // -----------------------------------------------
    // -----------------------------------------------
    public function getDeliveryAgencyByID ($agencyID) {
        global $app;
        $config = dbquery::shopGetDeliveryAgencyByID($agencyID);
        $data = $app->getDB()->query($config);
        $data['ID'] = intval($data['ID']);
        $data['_isRemoved'] = $data['Status'] === 'REMOVED';
        $data['_isActive'] = $data['Status'] === 'ACTIVE';
        return $data;
    }

    public function getDeliveries_List (array $options = array()) {
        global $app;
        $config = dbquery::shopGetDeliveriesList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getDeliveryAgencyByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $app->getDB()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createDeliveryAgency ($reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;
        $deliveryID = null;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 200),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300, 'defaultValueIfUnset' => '')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                $configCreateOrigin = dbquery::shopCreateDeliveryAgent($validatedValues);

                $app->getDB()->beginTransaction();
                $deliveryID = $app->getDB()->query($configCreateOrigin) ?: null;

                if (empty($deliveryID))
                    throw new Exception('DeliveryCreateError');

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        if ($success && !empty($deliveryID))
            $result = $this->getDeliveryAgencyByID($deliveryID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function updateDeliveryAgency ($id, $reqData) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300, 'defaultValueIfUnset' => ''),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $app->getDB()->beginTransaction();

                $configCreateCategory = dbquery::shopUpdateDeliveryAgent($id, $validatedValues);
                $app->getDB()->query($configCreateCategory);

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getDeliveryAgencyByID($id);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function deleteDeliveryAgency ($id) {
        global $app;
        $errors = array();
        $success = false;

        try {
            $app->getDB()->beginTransaction();

            $config = dbquery::shopDeleteDeliveryAgent($id);
            $app->getDB()->query($config);

            $app->getDB()->commit();

            $success = true;
        } catch (Exception $e) {
            $app->getDB()->rollBack();
            $errors[] = 'OriginUpdateError';
        }

        $result = $this->getDeliveryAgencyByID($id);
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // WRAPPERS
    // -----------------------------------------------
    // -----------------------------------------------

    public function getActiveDeliveryList () {
        $deliveries = $this->getDeliveries_List(array(
            "limit" => 0,
            "_fStatus" => "ACTIVE"
        ));
        return $deliveries['items'];
    }

    // -----------------------------------------------
    // -----------------------------------------------
    // REQUESTS
    // -----------------------------------------------
    // -----------------------------------------------

    public function get (&$resp, $req) {
        if (empty($req->get['id'])) {
            $resp = $this->getDeliveries_List($req->get);
        } else {
            $agencyID = intval($req->get['id']);
            $resp = $this->getDeliveryAgencyByID($agencyID);
        }
    }

    public function post (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createDeliveryAgency($req->data);
    }

    public function put (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        if (isset($req->data['ID']) && is_numeric($req->data['ID'])) {
            $agencyID = intval($req->data['ID']);
            $resp = $this->updateDeliveryAgency($agencyID, $req->data);
        } else {
            $resp['error'] = 'ID_IsMissing';
        }
    }

    public function delete (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit')) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        // var_dump($req);
        if (isset($req->get['params']) && is_numeric($req->get['params'])) {
            $agencyID = intval($req->get['params']);
            $resp = $this->deleteDeliveryAgency($agencyID);
        } else {
            $resp['error'] = 'ID_IsMissing';
        }
    }
}

?>