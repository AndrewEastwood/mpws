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
                $item['AccountID'] = intval($item['AccountID']);
            }

        return $account;
    }

    public function getAccountByValidationString ($ValidationString) {
        $config = configurationCustomerDataSource::jsapiGetAccountByValidationString($ValidationString);
        $account = $this->getCustomer()->fetch($config);
        // var_dump('getAccountByValidationString', $config);
        if (is_null($account)) {
            return glWrap('error', 'Account does not exist');
        }

        $account = $this->__attachAccountDetails($account);

        return $account;
    }

    public function getAccountByID ($AccountID) {
        $config = configurationCustomerDataSource::jsapiGetAccountByID($AccountID);
        $account = $this->getCustomer()->fetch($config);
        // var_dump('getAccountByID', $AccountID);
        if (!is_null($account))
            $account = $this->__attachAccountDetails($account);
        return $account;
    }

    public function isEmailAllowedToRegister ($email) {
        $config = configurationCustomerDataSource::jsapiGetAccountByEMail($email);
        $accountWithEmail = $this->getCustomer()->fetch($config);
        return empty($accountWithEmail);
    }

    public function createAccount ($reqData) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'FirstName' => array('string', 'notEmpty', 'min' => 2, 'max' => 40),
            'LastName' => array('skipIfUnset', 'string', "defaultValueIfUnset" => ""),
            'EMail' => array('isEmail', 'min' => 5, 'max' => 100),
            'Phone' => array('isPhone'),
            'Password' => array('isPassword', 'min' => 8, 'max' => 30),
            'ConfirmPassword' => array('equalTo' => 'Password', 'notEmpty')
        ));

        if ($validatedDataObj["totalErrors"] == 0)
            try {

                $validatedValues = $validatedDataObj['values'];

                if (!$this->isEmailAllowedToRegister($validatedValues['EMail']))
                    throw new Exception("EmailAlreadyInUse", 1);

                $this->getCustomerDataBase()->beginTransaction();

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

                if (empty($AccountID))
                    throw new Exception('AccountCreateError');

                // create permission
                $data = configurationDefaultDataSource::jsapiGetNewPermission();
                $data['AccountID'] = $AccountID;
                $configCreatePermission = configurationDefaultDataSource::jsapiAddPermissions($data);
                $PermissionID = $this->getCustomer()->fetch($configCreatePermission) ?: null;

                if (empty($PermissionID))
                    throw new Exception('PermissionCreateError');

                $this->getCustomerDataBase()->commit();

                $result = $this->getAccountByID($AccountID);

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
                $errors[] = $e->getMessage();
            }
        else
            $errors = $validatedDataObj["errors"];

        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _updateAccountByID ($AccountID, $reqData) {

        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'FirstName' => array('skipIfUnset', 'string', 'notEmpty', 'min' => 2, 'max' => 40),
            'LastName' => array('skipIfUnset', 'string'),
            'Phone' => array('skipIfUnset', 'isPhone'),
            'Password' => array('skipIfUnset', 'isPassword', 'min' => 8, 'max' => 30, 'inPairWith' => 'ConfirmPassword'),
            'ConfirmPassword' => array('skipIfUnset', 'equalTo' => 'Password', 'notEmpty'),
            // permissions
            'p_CanAdmin' => array('skipIfUnset', 'bool', 'notEmpty'),
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

        $result = $this->getAccountByID($AccountID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _activateAccountByValidationStyring ($ValidationString) {
        $account = $this->getAccountByValidationString($ValidationString);
        if ($account['Status'] === "TEMP" || glIsToolbox()) {
            $config = configurationCustomerDataSource::jsapiActivateAccount($ValidationString);
            $this->getCustomer()->fetch($config);
            $account = $this->getAccountByValidationString($ValidationString);
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
        $account = $this->getAccountByID($AccountID);
        if ($account['Addresses'])
            foreach ($account['Addresses'] as $addr) {
                $this->_disableAddressByID($addr['ID']);
            }
        return glWrap("ok", true);
    }

    public function getAddressByID ($AddressID) {
        $config = configurationCustomerDataSource::jsapiGetAddress($AddressID);
        $address = $this->getCustomer()->fetch($config);
        // adjust values
        $address['ID'] = intval($address['ID']);
        $address['AccountID'] = intval($address['AccountID']);
        return $address;
    }

    public function createAddress ($AccountID, $reqData, $allowStandalone = false) {
        $result = array();
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
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

                $result = $this->getAddressByID($AddressID);

                $success = true;
            } catch (Exception $e) {
                $this->getCustomerDataBase()->rollBack();
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
        $errors = array();
        $success = false;

        $validatedDataObj = libraryValidate::getValidData($reqData, array(
            'Address' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'POBox' => array('skipIfUnset', 'min' => 2, 'max' => 100),
            'Country' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100),
            'City' => array('skipIfUnset', 'string', 'min' => 2, 'max' => 100)
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

        $result = $this->getAddressByID($AddressID);
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    private function _disableAddressByID ($AddressID) {
        $config = configurationCustomerDataSource::jsapiDisableAddress($AddressID);
        $this->getCustomer()->fetch($config);
        return glWrap("ok", true);
    }

    // stats
    // -----------------------------------------------
    private function _getStats_AccountsOverview () {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = configurationCustomerDataSource::jsapiStat_AccountsOverview();
        $data = $this->getCustomer()->fetch($config) ?: array();
        return $data;
    }

    private function _getStats_AccountsIntensityLastMonth ($status) {
        if (!$this->getCustomer()->ifYouCan('Admin')) {
            return null;
        }
        $config = configurationCustomerDataSource::jsapiStat_AccountsIntensityLastMonth($status);
        $data = $this->getCustomer()->fetch($config) ?: array();
        // var_dump($config);
        return $data;
    }

    // private function _getStats_OrdersIntensityLastMonth ($status) {
    //     if (!$this->getCustomer()->ifYouCan('Admin')) {
    //         return null;
    //     }
    //     $config = configurationShopDataSource::jsapiShopStat_OrdersIntensityLastMonth($status);
    //     $data = $this->getCustomer()->fetch($config) ?: array();
    //     // var_dump($config);
    //     return $data;
    // }

    // private function _getStats_ProductsOverview () {
    //     if (!$this->getCustomer()->ifYouCan('Admin')) {
    //         return null;
    //     }
    //     // get shop products overview:
    //     $config = configurationShopDataSource::jsapiShopStat_ProductsOverview();
    //     $data = $this->getCustomer()->fetch($config) ?: array();
    //     return $data;
    // }

    public function get_account_overview (&$resp) {
        $resp['overview_accounts'] = $this->_getStats_AccountsOverview();
        // $resp['all_orders'] = $this->_getStats_OrdersOverview();
        // $resp['products_todays'] = $this->_getProducts_Todays();
        // $resp['products_popular'] = $this->_getProducts_TopPopular();
        // $resp['products_non_popular'] = $this->_getProducts_TopNonPopular();
        // $resp['orders_all_new'] = $this->_getOrders_ByStatus('NEW');
        // $resp['orders_todays'] = $this->_getOrders_Todays();
        // $resp['orders_expired'] = $this->_getOrders_Expired();
        // $resp['orders_intensity_new_last_month'] = $this->_getStats_OrdersIntensityLastMonth('NEW');
        // $resp['orders_intensity_closed_last_month'] = $this->_getStats_OrdersIntensityLastMonth('SHOP_CLOSED');
        $resp['accounts_intensity_last_month_active'] = $this->_getStats_AccountsIntensityLastMonth('ACTIVE');
        $resp['accounts_intensity_last_month_temp'] = $this->_getStats_AccountsIntensityLastMonth('TEMP');
        $resp['accounts_intensity_last_month_removed'] = $this->_getStats_AccountsIntensityLastMonth('REMOVED');
    }

    public function get_account_account (&$resp, $req) {
        if (!empty($req->get['id'])) {
            $AccountID = intval($req->get['id']);
            $resp = $this->getAccountByID($AccountID);
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
        $resp = $this->createAccount($req->data);
    }

    public function patch_account_account (&$resp, $req) {
        // var_dump($req);
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump(file_get_contents('php://input'));
        if (!empty($req->get['id'])) {
            $AccountID = intval($req->get['id']);
            $resp = $this->_updateAccountByID($AccountID, $req->data);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
    }

    public function delete_account_account (&$resp, $req) {
        if (!glIsToolbox()) {
            $resp['error'] = 'AccessDenied';
            return;
        }
        if (!empty($req->get['id'])) {
            $AccountID = intval($req->get['id']);
            $resp = $this->_disableAccountByID($AccountID);
            return;
        }
        $resp['error'] = 'MissedParameter_id';
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