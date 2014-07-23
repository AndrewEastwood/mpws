<?php

class pluginAccount extends objectPlugin {

    public function _getAddress ($AddressID) {
        $config = configurationCustomerDataSource::jsapiGetAddress($AddressID);
        return $this->getCustomer()->fetch($config);
    }

    public function _createAccount ($reqData) {
        $result = array();
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

            // create permission
            $data = configurationDefaultDataSource::jsapiGetNewPermission();
            $configCreatePermission = configurationDefaultDataSource::jsapiAddAccountPermissions($data);
            $PermissionID = $this->getCustomer()->fetch($configCreatePermission) ?: null;

            if (empty($PermissionID)) {
                $errors[] = 'PermissionCreateError';
                return glWrap("errors", $errors);
            }

            $validatedValues = $validatedDataObj['values'];

            $data = array();
            $data["CustomerID"] = $this->getCustomer()->getCustomerID();
            $data["PermissionID"] = $PermissionID;
            $data["FirstName"] = $validatedValues['FirstName'];
            $data["LastName"] = $validatedValues['LastName'];
            $data["EMail"] = $validatedValues['EMail'];
            $data["Phone"] = $validatedValues['Phone'];
            $data["Password"] = $validatedValues['Password'];
            $data["ValidationString"] = librarySecure::EncodeAccountPassword(time());
            $data["DateLastAccess"] = configurationDefaultDataSource::getDate();
            $data["DateCreated"] = configurationDefaultDataSource::getDate();
            $data["DateUpdated"] = configurationDefaultDataSource::getDate();

            $configCreateAccount = configurationDefaultDataSource::jsapiAddAccount($data);

            $AccountID = $this->getCustomer()->fetch($configCreateAccount) ?: null;

            $this->getCustomerDataBase()->commit();
        } catch (Exception $e) {
            $this->getCustomerDataBase()->rollBack();
        }

        if (empty($AccountID)) {
            $errors[] = 'AccountCreateError';
            return glWrap("errors", $errors);
        }

        $result = $this->_getAccountByID($AccountID);

        return $result;
    }

    public function _getAccountByID ($id) {
        $config = configurationCustomerDataSource::jsapiGetAccountByID($id);
        $account = $this->getCustomer()->fetch($config);
        // var_dump('getAccountByID', $id);
        if (is_null($account)) {
            return glWrap('error', 'Account does not exist');
        }
        // get account info
        // get account addresses
        $configAddresses = configurationCustomerDataSource::jsapiGetAccountAddresses($id);

        $account['Addresses'] = $this->getCustomer()->fetch($configAddresses) ?: array();

        // adjust values
        $account['ID'] = intval($account['ID']);
        // $account['CustomerID'] = intval($account['CustomerID']);
        // $account['PermissionID'] = intval($account['PermissionID']);
        $account['IsOnline'] = intval($account['IsOnline']) === 1;
        unset($account['CustomerID']);
        unset($account['PermissionID']);
        unset($account['Password']);

        foreach ($account as $key => $value) {
            if (preg_match("/^Permission_/", $key) === 1) {
                $account[$key] = $value == "1";
            }
        }

        if (!empty($account['Addresses']))
            foreach ($account['Addresses'] as &$item) {
                $item['ID'] = intval($item['ID']);
                $item['CustomerID'] = intval($item['CustomerID']);
                $item['AccountID'] = intval($item['AccountID']);
            }

        return $account;
    }

    private function _disableAccountByID ($id) {
        $config = configurationCustomerDataSource::jsapiRemoveAccount($id);
        $this->getCustomer()->fetch($config);
        return glWrap("ok", true);
    }

    public function get_account_account (&$resp, $req) {
        if (!empty($req['id'])) {
            $id = intval($req['id']);
            $resp = $this->_getAccountByID($id);
            return;
        }
        $resp['error'] = 'The request must contain "id" parameter';
    }

    public function post_account_account (&$resp, $req) {
        // $data = libraryRequest::getObjectFromREQUEST("FirstName", "LastName", "EMail", "Phone", "Password", "ConfirmPassword");
        $resp = $this->_createAccount($req);
    }

    public function patch_account_account (&$resp, $req) {
        // var_dump($req);
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump(file_get_contents('php://input'));
    }

    public function delete_account_account (&$resp, $req) {
        // global $PHP_INPUT;
        // var_dump($req);
        // var_dump($PHP_INPUT);
        if (!empty($req['id'])) {
            $id = intval($req['id']);
            $resp = $this->_disableAccountByID($id);
            return;
        }
        $resp['error'] = 'The request must contain "id" parameter';
    }

}

?>