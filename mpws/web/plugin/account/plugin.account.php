<?php

class pluginAccount extends objectPlugin {

    public function _getAddress ($AddressID) {
        $config = configurationCustomerDataSource::jsapiGetAddress($AddressID);
        return $this->getCustomer()->fetch($config);
    }

    public function _createAccountBy () {
        $result = array();
        $errors = array();
        // create permission
        $data = array();
        if (glIsToolbox()) {

        } else {
            $data = configurationDefaultDataSource::jsapiGetNewPermission();
            $configCreatePermission = configurationDefaultDataSource::jsapiAddAccountPermissions($data);
            $PermissionID = $this->getCustomer()->fetch($configCreatePermission) ?: null;

            if (empty($PermissionID)) {
                $errors[] = 'PermissionCreateError';
                return;
            }
        }

        $data = array()
        $data["PermissionID"] = $PermissionID;
        $data["FirstName"] = $req['FirstName'];
        $data["LastName"] = $req['LastName'];
        $data["EMail"] = $req['EMail'];
        $data["Phone"] = $req['Phone'];
        $data["Password"] = $req['Password'];
        $data["ValidationString"] = $req['ValidationString'];
        $data["CustomerID"] = $this->getCustomer()->getCustomerID();
        $data["DateLastAccess"] = configurationDefaultDataSource::getDate();
        $data["DateCreated"] = configurationDefaultDataSource::getDate();
        $data["DateUpdated"] = configurationDefaultDataSource::getDate();

        $configCreateAccount = configurationDefaultDataSource::jsapiAddAccount($data);
        $AccountID = $this->getCustomer()->fetch($configCreatePermission) ?: null;

        if (empty($AccountID)) {
            $errors[] = 'AccountCreateError';
            return;
        }

        $result = $this->_getAccountByID($AccountID);

        return $result;
    }

    public function _getAccountByID ($id) {
        $config = configurationCustomerDataSource::jsapiGetAccountByID($id);
        $account = $this->getCustomer()->fetch($config);
        // var_dump('getAccountByID', $id);
        // var_dump($account);
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

    public function get_account_account (&$resp, $req) {
        $id = intval($req['id']);
        if (empty($id))
            $resp['error'] = 'The request must contain "id" parameter';
        else
            $resp = $this->_getAccountByID($id);
    }

    public function post_account_account (&$resp, $req) {
        $resp = $this->_createAccountBy();
    }

    public function patch_account_account () {
        
    }


    public function delete_account_account () {
        
    }

}

?>