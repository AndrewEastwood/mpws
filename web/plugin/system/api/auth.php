<?php
namespace web\plugin\system\api;

class auth {

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
            $configPermissions = $app->getSettings()->data->jsapiGetPermissions($_SESSION['AccountID']);
            $permissions = $this->fetch($configPermissions, true) ?: array();
            $this->_setPermissions($permissions);
            if ($app->isToolbox() && !$this->ifYouCan('Admin')) {
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
            $configOffline = $app->getSettings()->data->jsapiSetOnlineAccount($_SESSION['AccountID']);
            $this->fetch($configOffline);
        }
        $_SESSION['AccountID'] = null;
        $this->_setPermissions(array());
        return null;
    }

    public function get (&$resp) {
        $resp['auth_id'] = $this->getAuthID();
        $this->updateSessionAuth();
    }

    public function post (&$resp, $req) {
        if (isset($req->get['signin'])) {
            $password = $req->post['password'];
            $email = $req->post['email'];
            $remember = $req->post['remember'];

            if (empty($email) || empty($password)) {
                $resp['error'] = 'WrongCredentials';
                return;
            }

            $password = Secure::EncodeAccountPassword($password);

            $config = $app->getSettings()->data->jsapiGetAccountByCredentials($email, $password);
            // avoid removed account
            $config["fields"] = array("ID");
            $config["condition"]["Status"] = $app->getDB()->createCondition('REMOVED', '!=');
            $account = $this->fetch($config);
            $AccountID = null;
            // var_dump($config);
            if (empty($account))
                $resp['error'] = 'WrongCredentials';
            else {
                $AccountID = intval($account['ID']);
                $_SESSION['AccountID'] = $AccountID;
                // set online state for account
                $configOnline = $app->getSettings()->data->jsapiSetOnlineAccount($AccountID);
                $this->fetch($configOnline);
            }
            $resp['auth_id'] = $this->getAuthID();
            $this->updateSessionAuth();
        }
        if (isset($req->get['signout'])) {
            $resp['auth_id'] = $this->clearAuthID();
            $this->updateSessionAuth();
        }
    }

    // public function post_signout (&$resp) {
    //     $resp['auth_id'] = $this->clearAuthID();
    //     $this->updateSessionAuth();
    // }

}

?>