<?php
namespace static_\plugins\system\api;

use \engine\lib\api as API;
use \engine\lib\secure as Secure;

class auth extends API {

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
            $user = $this->data->fetchUserByID($user['ID']);
        }
        $_SESSION[$this->authKey] = $user;
        $this->updateSessionAuth();
    }

    // public function ifYouCan (/* action or actions */) {
    //     $actions = func_get_args();
    //     $user = $this->getAuthenticatedUser();
    //     if (empty($user)) {
    //         return false;
    //     }
    //     $youCan = true;

    //     foreach ($action as $action) {
    //         if (!isset($user['p_Can' . $action])) {
    //             if (isset($user['p_Others']) && in_array($action, $user['p_Others'])) {
    //                 return true;
    //             }
    //             return false;
    //         }
    //         $youCan = $youCan && $user['p_Can' . $action];
    //     }
        
    //     return $youCan;
    // }

    public function ifYouCan ($p) {
        return $this->ifYouCanAll($p);
    }

    public function ifYouCanAny (/* action or actions */) {
        $actions = func_get_args();
        $user = $this->getAuthenticatedUser();
        if (empty($user)) {
            return false;
        }
        $youCan = false;

        foreach ($action as $action) {
            if (isset($user['p_Can' . $action])) {
                // return false;
                $youCan = $youCan || $user['p_Can' . $action];
            }
            if (isset($user['p_Others']) && in_array($action, $user['p_Others'])) {
                // return true;
                $youCan = true;
            }
        }
        return $youCan;
    }
    public function ifYouCanAll (/* action or actions */) {
        $actions = func_get_args();
        $user = $this->getAuthenticatedUser();
        if (empty($user)) {
            return false;
        }
        $youCan = true;

        foreach ($action as $action) {
            if (isset($user['p_Can' . $action])) {
                // return false;
                $youCan = $youCan && $user['p_Can' . $action];
            }
            if (isset($user['p_Others']) && in_array($action, $user['p_Others'])) {
                // return true;
                $youCan = $youCan && true;
            }
        }
        
        return $youCan;
    }

    public function ifYouCanDoAnythingYouWant () {
        return $this->ifYouCan('Maintain');
    }
    public function ifYouCanCreate () {
        if (!$this->ifYouCan('Maintain') || !$this->ifYouCanAll('Admin', 'Create')) {
            return false;
        }
        return true;
    }
    public function ifYouCanEdit () {
        if (!$this->ifYouCan('Maintain') || !$this->ifYouCanAll('Admin', 'Edit')) {
            return false;
        }
        return true;
    }
    public function ifYouCanCreateWithAnyOther (/* action or actions */) {
        $withAny = call_user_func_array(array($this, 'ifYouCanAny'), func_get_args());
        if (!$this->ifYouCanCreate() && !$withAny) {
            return false;
        }
        return true;
    }
    public function ifYouCanCreateWithAllOthers (/* action or actions */) {
        $withAll = call_user_func_array(array($this, 'ifYouCanAll'), func_get_args());
        if (!$this->ifYouCanCreate() && !$withAll) {
            return false;
        }
        return true;
    }
    public function ifYouCanEditWithAnyOther (/* action or actions */) {
        $withAny = call_user_func_array(array($this, 'ifYouCanAny'), func_get_args());
        if (!$this->ifYouCanEdit() && !$withAny) {
            return false;
        }
        return true;
    }
    public function ifYouCanEditWithAllOthers (/* action or actions */) {
        $withAll = call_user_func_array(array($this, 'ifYouCanAll'), func_get_args());
        if (!$this->ifYouCanEdit() && !$withAll) {
            return false;
        }
        return true;
    }

    public function updateSessionAuth () {
        global $app;
        $user = $this->getAuthenticatedUser();
        $prolongation = 0;
        $authID = null;
        if ($user) {
            $user = $this->data->fetchUserByID($user['ID']);
            $_SESSION[$this->authKey] = $user;
            $authID = $user['ValidationString'];
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
            // API::getAPI('system:users')->setOffline($_SESSION[$this->authKey]['ID']);
            $this->data->setUserOffline($_SESSION[$this->authKey]['ID']);
        }
        $_SESSION[$this->authKey] = null;
        // $this->permissions = array();
        return null;
    }

    public function get ($req, $resp) {
        // var_dump($_SESSION[$this->authKey]);
        $resp->setResponse($this->getAuthenticatedUser());
        $this->updateSessionAuth();
    }

    public function delete ($req, $resp) {
        // $resp[$this->authKey] = $this->clearAuthID();
        $this->clearAuthID();
        $this->updateSessionAuth();
    }

    public function post ($req, $resp) {
        global $app;
        // if (isset($req->get['signin'])) {

            $password = $req->post['password'];
            $email = $req->post['email'];
            $remember = $req->post['remember'];

            if (empty($email) || empty($password)) {
                $resp->setError('WrongCredentials');
                $this->updateSessionAuth();
                return;
            }

            $password = Secure::EncodeUserPassword($password);

            $user = $this->data->fetchUserByCredentials($email, $password);
            // $UserID = null;
            // var_dump($user);
            // var_dump($config);
            // return;
            if (empty($user)) {
                $resp->setError('WrongCredentials');
                $this->updateSessionAuth();
                return;
            } else {
                $_SESSION[$this->authKey] = $user;
                if ($app->isToolbox()) {
                    // don't allow non-managment users browse cross-domain sites
                    if ($this->ifYouCan('Admin') || $this->ifYouCan('Maintain')) {
                        // detect users site
                        if (!API::getAPI('system:customers')->switchToCustomerByID($user['CustomerID'])) {
                            return;
                        }
                    } else {
                        return $resp->setAccessError();
                        $this->clearAuthID();
                        return;
                    }
                }
                // $UserID = $user['ID'];
                // var_dump($user);
                // unset($user['Addresses']);
                // $_SESSION[$this->authKey] = $UserID;
                // set online state for account
                $this->data->setUserOnline($user['ID']);
                // API::getAPI('system:users')->setOnline($user['ID']);
            }
            // $resp[$this->authKey] = $this->getAuthID();
            // $resp->setResponse($this->getAuthenticatedUser());
            // $this->updateSessionAuth();
            $this->get($resp);

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