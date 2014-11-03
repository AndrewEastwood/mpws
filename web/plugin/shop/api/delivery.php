<?php
namespace web\plugin\shop\api;

class delivery extends \engine\object\api {

    // -----------------------------------------------
    // -----------------------------------------------
    // DELIVERY AGENCIES
    // -----------------------------------------------
    // -----------------------------------------------
    public function getDeliveryAgencyByID ($agencyID) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetDeliveryAgencyByID($agencyID);
        $data = $this->getCustomer()->fetch($config);
        $data['ID'] = intval($data['ID']);
        $data['_isRemoved'] = $data['Status'] === 'REMOVED';
        $data['_isActive'] = $data['Status'] === 'ACTIVE';
        return $data;
    }

    public function getDeliveries_List (array $options = array()) {
        $config = $this->getPluginConfiguration()->data->jsapiShopGetDeliveriesList($options);
        $self = $this;
        $callbacks = array(
            "parse" => function ($items) use($self) {
                $_items = array();
                foreach ($items as $val)
                    $_items[] = $self->getDeliveryAgencyByID($val['ID']);
                return $_items;
            }
        );
        $dataList = $this->getCustomer()->getDataList($config, $options, $callbacks);
        return $dataList;
    }

    public function createDeliveryAgency ($reqData) {
        $result = array();
        $errors = array();
        $success = false;
        $deliveryID = null;

        $validatedDataObj = \engine\lib\validate::getValidData($reqData, array(
            'Name' => array('string', 'notEmpty', 'min' => 1, 'max' => 100),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $validatedValues["CustomerID"] = $this->getCustomer()->getCustomerID();

                $configCreateOrigin = $this->getPluginConfiguration()->data->jsapiShopCreateDeliveryAgent($validatedValues);

                $this->getCustomerDataBase()->beginTransaction();
                $deliveryID = $this->getCustomer()->fetch($configCreateOrigin) ?: null;

                if (empty($deliveryID))
                    throw new Exception('DeliveryCreateError');

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
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
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = \engine\lib\validate::getValidData($reqData, array(
            'Name' => array('string', 'skipIfUnset', 'min' => 1, 'max' => 100),
            'HomePage' => array('string', 'skipIfUnset', 'max' => 300),
            'Status' => array('string', 'skipIfUnset')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                $this->getCustomerDataBase()->beginTransaction();

                $configCreateCategory = $this->getPluginConfiguration()->data->jsapiShopUpdateDeliveryAgent($id, $validatedValues);
                $this->getCustomer()->fetch($configCreateCategory);

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
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
        $errors = array();
        $success = false;

        try {
            $this->getCustomerDataBase()->beginTransaction();

            $config = $this->getPluginConfiguration()->data->jsapiShopDeleteDeliveryAgent($id);
            $this->getCustomer()->fetch($config);

            $this->getCustomerDataBase()->commit();

            $success = true;
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
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
        if (!$this->getCustomer()->ifYouCan('Admin') && !$this->getCustomer()->ifYouCan('Create')) {
            $resp['error'] = "AccessDenied";
            return;
        }
        $resp = $this->createDeliveryAgency($req->data);
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
            $resp = $this->updateDeliveryAgency($agencyID, $req->data);
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
            $resp = $this->deleteDeliveryAgency($agencyID);
            // $this->_getOrSetCachedState('changed:agencies', true);
        }
    }
}

?>