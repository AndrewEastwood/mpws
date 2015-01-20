<?php
namespace web\plugin\system\api;

use \engine\lib\api as API;

class auth {

    private $permissions = array();
    private $authKey = 'auth_id';

    public function getAuthID () {
        if (!isset($_SESSION[$authKey]))
            $_SESSION[$authKey] = null;
        if (isset($_SESSION[$authKey])) {
            $this->permissions = API::getAPI('account:permissions')->getPermissions($_SESSION[$authKey]);
            // $configPermissions = $app->getSettings()->data->getPermissions($_SESSION['AccountID']);
            // $permissions = $this->fetch($configPermissions, true) ?: array();
            if ($app->isToolbox() && !$this->ifYouCan('Admin')) {
                return $this->clearAuthID();
            }
        }
        return $_SESSION[$authKey];
    }

    public function ifYouCan ($action) {
        if (!isset($this->permissions['Can' . $action]))
            return false;
        return $this->permissions['Can' . $action];
    }

    public function updateSessionAuth () {
        $authID = $this->getAuthID();
        setcookie($authKey, $authID, time() + 3600, '/');
    }

    public function clearAuthID () {
        if (!empty($_SESSION[$authKey])) {
            API::getAPI('account:user')->setOffline($_SESSION[$authKey]);
        }
        $_SESSION[$authKey] = null;
        $this->permissions = array();
        return null;
    }

    public function get (&$resp) {
        $resp[$authKey] = $this->getAuthID();
        $this->updateSessionAuth();
    }

    public function delete (&$resp, $req) {
        $resp[$authKey] = $this->clearAuthID();
        $this->updateSessionAuth();
    }

    public function post (&$resp, $req) {
        // if (isset($req->get['signin'])) {
            $password = $req->post['password'];
            $email = $req->post['email'];
            $remember = $req->post['remember'];

            if (empty($email) || empty($password)) {
                $resp['error'] = 'WrongCredentials';
                return;
            }

            $password = Secure::EncodeUserPassword($password);

            $user = API::getAPI('account:user')->getActiveUserByCredentials($email, $password);
            $UserID = null;
            // var_dump($config);
            if (empty($user))
                $resp['error'] = 'WrongCredentials';
            else {
                $UserID = intval($user['ID']);
                $_SESSION[$authKey] = $user;
                // set online state for account
                API::getAPI('account:user')->setOnline($UserID);
            }
            $resp[$authKey] = $this->getAuthID();
            $this->updateSessionAuth();
        // }
        // if (isset($req->get['signout'])) {
        //     $resp[$authKey] = $this->clearAuthID();
        //     $this->updateSessionAuth();
        // }
    }

    // public function post_signout (&$resp) {
    //     $resp[$authKey] = $this->clearAuthID();
    //     $this->updateSessionAuth();
    // }

}

?>