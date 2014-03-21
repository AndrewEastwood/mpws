<?php

class pluginToolbox extends objectPlugin {

    public function getResponse () {

        $data = false;

        switch(libraryRequest::getValue('fn')) {
            case "bridge":
                $do = libraryRequest::getValue('action');
                switch($do) {
                    case "signin": {
                        $data = $this->_custom_api_signin();
                        break;
                    }
                    case "signout": {
                        $data = $this->_custom_api_signout();
                        break;
                    }
                    case "status": {
                        $data = $this->_custom_api_status();
                        break;
                    }
                break;
            }
        }

        // if (glIsToolbox()) {
        //     $response->setError('AccessDenied');
        //     $response->setData('redirect', '/toolbox/#login');
        //     return $response;
        // }

        // attach to output
        return $data;
    }

    private function isAccountSignedIn () {
        if (isset($_COOKIE['username']) && isset($_COOKIE['password']) && isset($_SESSION['Toolbox:ProfileID']))
            return true;
        else {
            setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
            setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
            unset($_SESSION['Toolbox:ProfileID']);
            return false;
        }
    }

    private function getActiveProfileID () {
        if ($this->isAccountSignedIn())
            return $_SESSION['Toolbox:ProfileID'];
        return false;
    }

    private function getActiveProfile ($newPassword = false) {
        if ($this->isAccountSignedIn())
            return $this->getCustomer()->getAccount($_COOKIE['username'], !empty($newPassword) ? $newPassword : $_COOKIE['password'], false);
        else
            return null;
    }

    private function _custom_api_signin () {
        $accountObj = new libraryDataObject();

        $errors = array();

        $credentials = libraryRequest::getPostValue('credentials');

        if (empty($credentials['email']))
            $errors['email'] = 'Empty';

        if (empty($credentials['password']))
            $errors['password'] = 'Empty';

        if (count($errors)) {
            $accountObj->setError($errors);
            return $accountObj;
        }

        $account = $this->getCustomer()->getAccount($credentials['email'], $credentials['password']);

        if (empty($account))
            $accountObj->setError('WrongCredentials');
        else {
            $accountObj->setData('profile', $account);

            // keep user logged in
            if ($credentials['remember']) {
                /* Set cookie to last 1 year */
                setcookie('username', $credentials['email'], time()+60*60*24*365, '/', $_SERVER['SERVER_NAME']);
                setcookie('password', $account['Password'], time()+60*60*24*365, '/', $_SERVER['SERVER_NAME']);
            
            } else {
                /* Cookie expires when browser closes */
                setcookie('username', $credentials['email'], false, '/', $_SERVER['SERVER_NAME']);
                setcookie('password', $account['Password'], false, '/', $_SERVER['SERVER_NAME']);
            }

            $_SESSION['Toolbox:ProfileID'] = $account['ID'];
        }

        return $accountObj;
    }

    private function _custom_api_status () {
        $accountObj = new libraryDataObject();
        $account = $this->getActiveProfile();
        $accountObj->setData('profile', $account);
        return $accountObj;
    }

    private function _custom_api_signout () {
        $accountObj = new libraryDataObject();
        setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
        setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
        unset($_SESSION['Toolbox:ProfileID']);
        $accountObj->setData('profile', null);
        return $accountObj;
    }

}

?>