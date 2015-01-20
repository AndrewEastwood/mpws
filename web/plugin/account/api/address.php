<?php
namespace web\plugin\system\api;

class address {
    public function getAddressByID ($AddressID) {
        global $app;
        $config = $this->getCustomerConfiguration()->data->jsapiGetAddress($AddressID);
        $address = $app->getDB()->query($config);
        // adjust values
        $address['ID'] = intval($address['ID']);
        $address['AccountID'] = intval($address['AccountID']);
        return $address;
    }

    public function createAddress ($AccountID, $reqData, $allowStandalone = false) {
        global $app;
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Address' => array('string', 'notEmpty', 'min' => 2, 'max' => 100),
            'POBox' => array('notEmpty', 'min' => 2, 'max' => 100),
            'Country' => array('string', 'min' => 2, 'max' => 100),
            'City' => array('string', 'min' => 2, 'max' => 100)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                // TODO: if account is authorized and do not have maximum addresses
                // we must link new address to the account otherwise create unlinked account
                $account = $this->getAccountByID($AccountID);
                if (empty($account))
                    throw new Exception("WrongAccount", 1);
                elseif (count($account['Addresses']) >= 3) {
                    if (!$allowStandalone)
                        throw new Exception("AddressLimitExceeded", 1);
                    else
                        $AccountID = null;
                }

                $app->getDB()->beginTransaction();

                $validatedValues = $validatedDataObj['values'];

                $data = array();
                $data["CustomerID"] = $this->getCustomer()->getCustomerID();
                $data["AccountID"] = $AccountID;
                $data["Address"] = $validatedValues['Address'];
                $data["POBox"] = $validatedValues['POBox'];
                $data["Country"] = $validatedValues['Country'];
                $data["City"] = $validatedValues['City'];

                $configCreateAccount = $this->getCustomerConfiguration()->data->jsapiAddAddress($data);

                $AddressID = $app->getDB()->query($configCreateAccount) ?: null;

                $app->getDB()->commit();

                $result = $this->getAddressByID($AddressID);

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                $errors[] = 'AccountAddressCreateError';
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _updateAddressByID ($AddressID, $reqData) {
        global $app;
        $errors = array();
        $success = false;

        $validatedDataObj = Validate::getValidData($reqData, array(
            'Address' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'POBox' => array('skipIfUnset', 'min' => 2, 'max' => 100),
            'Country' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'City' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100)
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $app->getDB()->beginTransaction();

                $data = $validatedDataObj['values'];

                $configUpdateAddress = $this->getCustomerConfiguration()->data->jsapiUpdateAddress($AddressID, $data);

                $app->getDB()->query($configUpdateAddress);

                $app->getDB()->commit();

                $success = true;
            } catch (Exception $e) {
                $app->getDB()->rollBack();
                // return glWrap("error", 'AddressUpdateError');
                $errors[] = 'AddressUpdateError';
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->getAddressByID($AddressID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _disableAddressByID ($AddressID) {
        global $app;
        $config = $this->getCustomerConfiguration()->data->jsapiDisableAddress($AddressID);
        $app->getDB()->query($config);
        return glWrap("ok", true);
    }

    public function patch_account_address (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $AddressID = intval($req->get['id']);
            $resp = $this->_updateAddressByID($AddressID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function post_account_address (&$resp, $req) {
        if (!empty($req->data['AccountID'])) {
            $AccountID = intval($req->data['AccountID']);
            $resp = $this->createAddress($AccountID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_AccountID';
    }

    public function delete_account_address (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $AddressID = intval($req->get['id']);
            $resp = $this->_disableAddressByID($AddressID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }
}

?>