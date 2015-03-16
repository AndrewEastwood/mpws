<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;
use \engine\lib\secure as Secure;

class auth {

    private $authKey = 'auth';

    public function getAuthCookieKey () {
        return $this->authKey;
    }

    public function isSignedIn () {
        return !empty($_SESSION[$this->authKey]);
    }

    public function getAuthenticatedUser () {
        if (!isset($_SESSION[$this->authKey]))
            $_SESSION[$this->authKey] = null;
        return $_SESSION[$this->authKey];
    }

    public function isUserIDAuthenticated ($userID) {
        return $this->getAuthenticatedUserID() === $userID;
    }

    public function getAuthenticatedUserID () {
        if (!isset($_SESSION[$this->authKey]))
            $_SESSION[$this->authKey] = null;
        return $_SESSION[$this->authKey]['ID'];
    }

    public function getAuthenticatedUserJSON () {
        $user = $this->getAuthenticatedUser();
        return json_encode($user);
    }

    public function refreshSessionUserInfo () {
        $user = $this->getAuthenticatedUser();
        if (!empty($user)) {
            $user = API::getAPI('system:users')->getUserByID($user['ID']);
        }
        $_SESSION[$this->authKey] = $user;
        $this->updateSessionAuth();
    }

    public function ifYouCan ($action) {
        $user = $this->getAuthenticatedUser();
        if (empty($user)) {
            return false;
        }
        if (!isset($user['p_Can' . $action]))
            return false;
        return $user['p_Can' . $action];
    }

    public function updateSessionAuth () {
        global $app;
        $user = $this->getAuthenticatedUser();
        $prolongation = 0;
        $authID = null;
        if ($user) {
            $user = API::getAPI('system:users')->getUserByID($user['ID']);
            $_SESSION[$this->authKey] = $user;
            $authID = $user['ID'];
            if ($app->isToolbox() && !$this->ifYouCan('Admin')) {
                $this->clearAuthID();
            } else {
                $prolongation = 3600;
            }
        }
        setcookie($this->authKey, $authID, time() + $prolongation, '/');
    }

    public function clearAuthID () {
        if (!empty($_SESSION[$this->authKey])) {
            API::getAPI('system:users')->setOffline($_SESSION[$this->authKey]['ID']);
        }
        $_SESSION[$this->authKey] = null;
        // $this->permissions = array();
        return null;
    }

    public function get (&$resp) {
        $resp[$this->authKey] = $this->getAuthenticatedUser();
        $this->updateSessionAuth();
    }

    public function delete (&$resp, $req) {
        $resp[$this->authKey] = $this->clearAuthID();
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

            $user = API::getAPI('system:users')->getActiveUserByCredentials($email, $password);
            // $UserID = null;
            // var_dump($user);
            // var_dump($config);
            if (empty($user))
                $resp['error'] = 'WrongCredentials';
            else {
                $_SESSION[$this->authKey] = $user;

                // var_dump($user);
                // don't allow non-managment users browse cross-domain sites
                if (!API::getAPI('system:customers')->isRunningCustomerDefault() && !$this->ifYouCan('Maintain')) {
                    $this->clearAuthID();
                    return;
                }
                // $UserID = $user['ID'];
                // var_dump($user);
                unset($user['Addresses']);
                // $_SESSION[$this->authKey] = $UserID;
                // set online state for account
                API::getAPI('system:users')->setOnline($user['ID']);
            }
            // $resp[$this->authKey] = $this->getAuthID();
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