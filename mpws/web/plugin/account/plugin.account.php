<?php

class pluginAccount extends objectPlugin {

    // private function _getAddress ($AddressID) {
    //     $config = configurationCustomerDataSource::jsapiGetAddress($AddressID);
    //     return $this->getCustomer()->fetch($config);
    // }

    private function __attachAccountDetails ($account) {
        $AccountID = $account['ID'];
        // get account info
        // get account addresses
        $configAddresses = configurationCustomerDataSource::jsapiGetAccountAddresses($AccountID);
        $account['Addresses'] = $this->getCustomer()->fetch($configAddresses) ?: array();
        
        $configPermissions = configurationCustomerDataSource::jsapiGetPermissions($AccountID);
        $account['Permissions'] = $this->getCustomer()->fetch($configPermissions, true) ?: array();

        // adjust values
        $account['ID'] = intval($account['ID']);
        // $account['CustomerID'] = intval($account['CustomerID']);
        // $account['PermissionID'] = intval($account['PermissionID']);
        $account['IsOnline'] = intval($account['IsOnline']) === 1;
        unset($account['CustomerID']);
        unset($account['Permissions']['ID']);
        unset($account['Permissions']['AccountID']);
        unset($account['Permissions']['DateUpdated']);
        unset($account['Permissions']['DateCreated']);
        unset($account['Password']);

        foreach ($account['Permissions'] as $key => $value) {
            $account['Permissions'][$key] = $value == "1";
        }

        if (!empty($account['Addresses']))
            foreach ($account['Addresses'] as &$item) {
                $item['ID'] = intval($item['ID']);
                $item['CustomerID'] = intval($item['CustomerID']);
                $item['AccountID'] = intval($item['AccountID']);
            }

        return $account;
    }

    private function _getAccountByValidationString ($ValidationString) {
        $config = configurationCustomerDataSource::jsapiGetAccountByValidationString($ValidationString);
        $account = $this->getCustomer()->fetch($config);
        // var_dump('_getAccountByValidationString', $config);
        if (is_null($account)) {
            return glWrap('error', 'Account does not exist');
        }

        $account = $this->__attachAccountDetails($account);

        return $account;
    }

    private function _getAccountByID ($AccountID) {
        $config = configurationCustomerDataSource::jsapiGetAccountByID($AccountID);
        $account = $this->getCustomer()->fetch($config);
        // var_dump('getAccountByID', $AccountID);
        if (!is_null($account))
            $account = $this->__attachAccountDetails($account);

        return $account;
    }

    private function _createAccount ($reqData) {
        $errors = array();

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'FirstName' => array('string', 'notEmpty', 'min' => 2, 'max' => 40),
            'LastName' => array('string', 'notEmpty', 'min' => 2, 'max' => 40),
            'EMail' => array('isEmail', 'min' => 5, 'max' => 100),
            'Phone' => array('isPhone'),
            'Password' => array('isPassword', 'min' => 8, 'max' => 30),
            'ConfirmPassword' => array('equalTo' => 'Password', 'notEmpty')
        ));

        if ($validatedDataObj["totalErrors"] > 0) {
            return glWrap("errors", $validatedDataObj["errors"]);
        }

        try {

            $this->getCustomerDataBase()->beginTransaction();

            $validatedValues = $validatedDataObj['values'];

            $data = array();
            $data["CustomerID"] = $this->getCustomer()->getCustomerID();
            $data["FirstName"] = $validatedValues['FirstName'];
            $data["LastName"] = $validatedValues['LastName'];
            $data["EMail"] = $validatedValues['EMail'];
            $data["Phone"] = $validatedValues['Phone'];
            $data["Password"] = librarySecure::EncodeAccountPassword($validatedValues['Password']);
            $data["ValidationString"] = librarySecure::EncodeAccountPassword(time());
            $configCreateAccount = configurationDefaultDataSource::jsapiAddAccount($data);
            $AccountID = $this->getCustomer()->fetch($configCreateAccount) ?: null;

            if (empty($AccountID)) {
                $errors[] = 'AccountCreateError';
                return glWrap("errors", $errors);
            }

            // create permission
            $data = configurationDefaultDataSource::jsapiGetNewPermission();
            $data['AccountID'] = $AccountID;
            $configCreatePermission = configurationDefaultDataSource::jsapiAddPermissions($data);
            $PermissionID = $this->getCustomer()->fetch($configCreatePermission) ?: null;

            if (empty($PermissionID)) {
                $errors[] = 'PermissionCreateError';
                return glWrap("errors", $errors);
            }

            $this->getCustomerDataBase()->commit();
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
        }

        if (empty($AccountID)) {
            return glWrap("error", 'AccountCreateError');
        }

        $result = $this->_getAccountByID($AccountID);

        return $result;
    }

    private function _updateAccountByID ($AccountID, $reqData) {

        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'FirstName' => array('skipIfUnset', 'string', 'notEmpty', 'min' => 2, 'max' => 40),
            'LastName' => array('skipIfUnset', 'string', 'notEmpty', 'min' => 2, 'max' => 40),
            'EMail' => array('skipIfUnset', 'isEmail', 'min' => 5, 'max' => 100),
            'Phone' => array('skipIfUnset', 'isPhone'),
            'Password' => array('skipIfUnset', 'isPassword', 'min' => 8, 'max' => 30, 'inPairWith' => 'ConfirmPassword'),
            'ConfirmPassword' => array('skipIfUnset', 'equalTo' => 'Password', 'notEmpty'),
            // permissions
            'p_IsAdmin' => array('skipIfUnset', 'bool', 'notEmpty'),
            'p_CanCreate' => array('skipIfUnset', 'bool', 'notEmpty'),
            'p_CanEdit' => array('skipIfUnset', 'bool', 'notEmpty'),
            'p_CanView' => array('skipIfUnset', 'bool', 'notEmpty'),
            'p_CanAddUsers' => array('skipIfUnset', 'bool', 'notEmpty')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $this->getCustomerDataBase()->beginTransaction();

                $validatedValues = $validatedDataObj['values'];

                // separate data
                $dataAccount = array();
                $dataPermission = array();

                foreach ($validatedValues as $field => $value) {
                    if (preg_match("/^p_/", $field) === 1)
                        $dataPermission[substr($field, strlen("p_"))] = $value;
                    else
                        $dataAccount[$field] = $value;
                }

                if (count($dataAccount)) {
                    if (isset($dataAccount['Password'])) {
                        $dataAccount['Password'] = librarySecure::EncodeAccountPassword($validatedValues['Password']);
                        unset($dataAccount['ConfirmPassword']);
                    }
                    $configUpdateAccount = configurationDefaultDataSource::jsapiUpdateAccount($AccountID, $dataAccount);
                    $this->getCustomer()->fetch($configUpdateAccount);
                    // var_dump($configUpdateAccount);
                }

                if (count($dataPermission)) {
                    // foreach ($dataPermission as $key => $value) {
                    //     $dataPermission[$key] = $value ? 1: 0;
                    // }
                    $configUpdatePermissions = configurationDefaultDataSource::jsapiUpdatePermissions($AccountID, $dataPermission);
                    // var_dump($configUpdatePermissions);
                    $this->getCustomer()->fetch($configUpdatePermissions, true);
                }

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                // echo $this->getCustomerDataBase()->getLastErrorCode();
                // echo $e;
                // return glWrap("error", 'AccountUpdateError');
                $errors[] = 'AccountUpdateError';
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->_getAccountByID($AccountID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _activateAccountByValidationStyring ($ValidationString) {
        $account = $this->_getAccountByValidationString($ValidationString);
        if ($account['Status'] === "TEMP" || glIsToolbox()) {
            $config = configurationCustomerDataSource::jsapiActivateAccount($ValidationString);
            $this->getCustomer()->fetch($config);
            $account = $this->_getAccountByValidationString($ValidationString);
            if ($account['Status'] === 'ACTIVE')
                return $account;
        } elseif ($account['Status'] === 'REMOVED')
            return glWrap('error', 'AccountIsRemoved');
        return $account;
    }

    private function _disableAccountByID ($AccountID) {
        $config = configurationCustomerDataSource::jsapiDisableAccount($AccountID);
        $this->getCustomer()->fetch($config);
        // disable all related addresses
        $account = $this->_getAccountByID($AccountID);
        if ($account['Addresses'])
            foreach ($account['Addresses'] as $addr) {
                $this->_disableAddressByID($addr['ID']);
            }
        return glWrap("ok", true);
    }

    private function _getAddressByID ($AddressID) {
        $config = configurationCustomerDataSource::jsapiGetAddress($AddressID);
        $address = $this->getCustomer()->fetch($config);
        return $address;
    }

    private function _createAddress ($AccountID, $reqData) {
        $errors = array();

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Address' => array('string', 'notEmpty', 'min' => 2, 'max' => 100),
            'POBox' => array('string', 'notEmpty', 'min' => 2, 'max' => 100),
            'Country' => array('string', 'min' => 2, 'max' => 100),
            'City' => array('string', 'min' => 2, 'max' => 100)
        ));

        if ($validatedDataObj["totalErrors"] > 0) {
            return glWrap("errors", $validatedDataObj["errors"]);
        }

        try {

            $this->getCustomerDataBase()->beginTransaction();

            $validatedValues = $validatedDataObj['values'];

            $data = array();
            $data["CustomerID"] = $this->getCustomer()->getCustomerID();
            $data["AccountID"] = $AccountID;
            $data["Address"] = $validatedValues['Address'];
            $data["POBox"] = $validatedValues['POBox'];
            $data["Country"] = $validatedValues['Country'];
            $data["City"] = $validatedValues['City'];

            $configCreateAccount = configurationDefaultDataSource::jsapiAddAddress($data);

            $AddressID = $this->getCustomer()->fetch($configCreateAccount) ?: null;

            $this->getCustomerDataBase()->commit();
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
        }

        if (empty($AddressID)) {
            return glWrap("error", 'AddressCreateError');
        }

        $result = $this->_getAddressByID($AddressID);

        return $result;
    }

    private function _updateAddressByID ($AddressID, $reqData) {
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Address' => array('skipIfUnset', 'min' => 2, 'max' => 100),
            'POBox' => array('skipIfUnset', 'min' => 2, 'max' => 100),
            'Country' => array('skipIfUnset', 'min' => 2, 'max' => 100),
            'City' => array('skipIfUnset', 'min' => 2, 'max' => 100)
        ));

        // if ($validatedDataObj["totalErrors"] > 0) {
        //     return glWrap("errors", $validatedDataObj["errors"]);
        // }

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $this->getCustomerDataBase()->beginTransaction();

                $data = $validatedDataObj['values'];

                $configUpdateAddress = configurationDefaultDataSource::jsapiUpdateAddress($AddressID, $data);

                $this->getCustomer()->fetch($configUpdateAddress);

                $this->getCustomerDataBase()->commit();

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                // return glWrap("error", 'AddressUpdateError');
                $errors[] = 'AddressUpdateError';
            }
        else
            $errors = $validatedDataObj["errors"];

        $result = $this->_getAddressByID($AddressID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _disableAddressByID ($AddressID) {
        $config = configurationCustomerDataSource::jsapiDisableAddress($AddressID);
        $this->getCustomer()->fetch($config);
        return glWrap("ok", true);
    }

    public function get_account_account (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $AccountID = intval($req->get['id']);
            $resp = $this->_getAccountByID($AccountID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function get_account_activate (&$resp, $req) {
        if (!empty($req->get['hash'])) {
            $ValidationString = $req->get['hash'];
            $resp = $this->_activateAccountByValidationStyring($ValidationString);
            return;
        }
        $resp['error'] = 'MissedParameter_hash';
    }

    public function post_account_account (&$resp, $req) {
        // $data = libraryRequest::getObjectFromREQUEST("FirstName", "LastName", "EMail", "Phone", "Password", "ConfirmPassword");
        $resp = $this->_createAccount($req->data);
    }

    public function patch_account_account (&$resp, $req) {
        // var_dump($req);
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump(file_get_contents('php://input'));
        if (!empty($req->data['id'])) {
            $AccountID = intval($req->data['id']);
            $resp = $this->_updateAccountByID($AccountID, $req);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function delete_account_account (&$resp, $req) {
        if (!glIsToolbox()) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        // global $PHP_INPUT;
        // var_dump($req);
        // var_dump($PHP_INPUT);
        if (!empty($req->get['id'])) {
            $AccountID = intval($req->get['id']);
            $resp = $this->_disableAccountByID($AccountID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function patch_account_address (&$resp, $req) {
        if (!empty($req->data['ID'])) {
            $AddressID = intval($req->data['ID']);
            $resp = $this->_updateAddressByID($AddressID, $req);
            return;
        }
        $resp['error'] = 'MissedParameter_ID';
    }

    public function post_account_address (&$resp, $req) {
        if (!empty($req->post['AccountID'])) {
            $AccountID = intval($req->post['AccountID']);
            $account = $this->_getAccountByID($AccountID);
            if (empty($account))
                $resp['error'] = 'WrongAccount';
            elseif (count($account['Addresses']) >= 3)
                $resp['error'] = 'AddressLimitExcided';
            else
                $resp = $this->_createAddress($AccountID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_AccountID';
    }

    public function delete_account_address (&$resp, $req) {
        // if (!empty($req->get('id')) {
        //     $AddressID = intval($req->get('id'));
        //     $resp = $this->_disableAddressByID($AddressID);
        //     return;
        // }
        // $resp['error'] = 'MissedParameter_id';
    }

}

?>