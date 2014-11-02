<?php

namespace engine\extension;

class auth extends \engine\object\extension {

    var $permissions;

    // public function test () {
    //     echo 'I AM TEST FROM EXT AUTH';
    // }

    private function _setPermissions ($perms) {
        $listOfDOs = array();
        // adjust permission values
        foreach ($perms as $field => $value) {
            if (preg_match("/^Can/", $field) === 1)
                $listOfDOs[$field] = intval($value) === 1;
        }
        $this->permissions = $listOfDOs;
    }

    public function getPermissions () {
        return $this->permissions;
    }

    public function ifYouCan ($action) {
        $permissions = $this->getPermissions();
        if (!isset($permissions['Can' . $action]))
            return false;
        return $this->permissions['Can' . $action];
    }

    public function getAuthID () {
        if (!isset($_SESSION['AccountID']))
            $_SESSION['AccountID'] = null;
        if (isset($_SESSION['AccountID'])) {
            $configPermissions = configurationCustomerDataSource::jsapiGetPermissions($_SESSION['AccountID']);
            $permissions = $this->getCustomer()->fetch($configPermissions, true) ?: array();
            $this->_setPermissions($permissions);
            if (glIsToolbox() && !$this->ifYouCan('Admin')) {
                return $this->clearAuthID();
            }
        }
        return $_SESSION['AccountID'];
    }

    public function updateSessionAuth () {
        $authID = $this->getAuthID();
        setcookie('auth_id', $authID, time() + 3600, '/');
    }

    public function clearAuthID () {
        if (!empty($_SESSION['AccountID'])) {
            $configOffline = configurationCustomerDataSource::jsapiSetOnlineAccount($_SESSION['AccountID']);
            $this->getCustomer()->fetch($configOffline);
        }
        $_SESSION['AccountID'] = null;
        $this->_setPermissions(array());
        return null;
    }

    public function get_status (&$resp) {
        $resp['auth_id'] = $this->getAuthID();
        $this->updateSessionAuth();
    }

    public function post_signin (&$resp, $req) {

        $password = $req->post['password'];
        $email = $req->post['email'];
        $remember = $req->post['remember'];

        if (empty($email) || empty($password)) {
            $resp['error'] = 'WrongCredentials';
            return;
        }

        $password = \engine\lib\secure::EncodeAccountPassword($password);

        $config = configurationCustomerDataSource::jsapiGetAccountByCredentials($email, $password);
        // avoid removed account
        $config["fields"] = array("ID");
        $config["condition"]["Status"] = configurationCustomerDataSource::jsapiCreateDataSourceCondition('REMOVED', '!=');
        $account = $this->getCustomer()->fetch($config);
        $AccountID = null;
        // var_dump($config);
        if (empty($account))
            $resp['error'] = 'WrongCredentials';
        else {
            $AccountID = intval($account['ID']);
            $_SESSION['AccountID'] = $AccountID;
            // set online state for account
            $configOnline = configurationCustomerDataSource::jsapiSetOnlineAccount($AccountID);
            $this->getCustomer()->fetch($configOnline);
        }
        $resp['auth_id'] = $this->getAuthID();
        $this->updateSessionAuth();
    }


    public function post_signout (&$resp) {
        $resp['auth_id'] = $this->clearAuthID();
        $this->updateSessionAuth();
    }
}

?>