<?php
namespace static_\plugins\shop\api;

use \engine\objects\plugin as basePlugin;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\path as Path;
use \engine\lib\api as API;
use Exception;
use ArrayObject;

class delivery extends API {

    private $_statuses = array('ACTIVE', 'DISABLED', 'REMOVED');
    // -----------------------------------------------
    // -----------------------------------------------
    // DELIVERY AGENCIES
    // -----------------------------------------------
    // -----------------------------------------------
    public function getDeliveryAgencyByID ($agencyID) {
        global $app;
        $config = data::shopGetDeliveryAgencyByID($agencyID);
        $data = $app->getDB()->query($config);
        $data['ID'] = intval($data['ID']);
        $data['_isRemoved'] = $data['Status'] === 'REMOVED';
        $data['_isActive'] = $data['Status'] === 'ACTIVE';
        return $data;
    }

    public function getDeliveries_List (array $options = array()) {
        global $app;
        $config = data::shopGetDeliveriesList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getDeliveryAgencyByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $app->getDB()->queryMatchedIDs($config, $options, $callbacks);
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

        if ($validatedDataObj->errorsCount == 0)
            try {

                $validatedValues = $validatedDataObj->validData;

                $validatedValues["CustomerID"] = $app->getSite()->getRuntimeCustomerID();

                $configCreateOrigin = data::shopCreateDeliveryAgent($validatedValues);

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
            $errors = $validatedDataObj->errorMessages;

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

        if ($validatedDataObj->errorsCount == 0)
            try {

                $validatedValues = $validatedDataObj->validData;

                $app->getDB()->beginTransaction();

                $configCreateCategory = data::shopUpdateDeliveryAgent($id, $validatedValues);
                $app->getDB()->query($configCreateCategory);

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj->errorMessages;

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

            $config = data::shopDeleteDeliveryAgent($id);
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
        if (empty($req->id)) {
            $resp = $this->getDeliveries_List($req->get);
        } else {
            $agencyID = intval($req->id);
            $resp = $this->getDeliveryAgencyByID($agencyID);
        }
    }

    public function post (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Create'))) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createDeliveryAgency($req->data);
    }

    public function put (&$resp, $req) {
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
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
        if (!API::getAPI('system:auth')->ifYouCan('Maintain') ||
            (!API::getAPI('system:auth')->ifYouCan('Admin') && !API::getAPI('system:auth')->ifYouCan('Edit'))) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        // var_dump($req);
        if (isset($req->id) && is_numeric($req->id)) {
            $agencyID = intval($req->id);
            $resp = $this->deleteDeliveryAgency($agencyID);
        } else {
            $resp['error'] = 'ID_IsMissing';
        }
    }
}

?>