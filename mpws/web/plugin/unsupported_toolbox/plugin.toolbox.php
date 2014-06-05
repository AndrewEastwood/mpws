<?php

class pluginToolbox extends objectPlugin {

    public function get_auth () {
        $data = new libraryDataObject();
        $do = libraryRequest::fromGET('action');
        switch($do) {
            case "status": {
                $data = $this->_custom_api_status();
                break;
            }
        }
        return $data;
    }

    public function post_auth () {
        $data = new libraryDataObject();
        $do = libraryRequest::fromGET('action');
        switch($do) {
            case "signin": {
                $data = $this->_custom_api_signin();
                break;
            }
            case "signout": {
                $data = $this->_custom_api_signout();
                break;
            }
        }
        return $data;
    }

    private function getAdminAccount ($email, $password, $encodePassword) {
        if ($encodePassword)
            $password = $this->getCustomer()->getAccountPassword($password);

        // var_dump($password);
        // var_dump($passwordS);
        $config = configurationToolboxDataSource::jsapiGetAdminAccount($email, $password);
        // var_dump($config);
        return $this->getCustomer()->fetch($config);
    }

    public function isAccountSignedIn () {
        if (isset($_SESSION['Toolbox:ProfileID']))
            return true;
        else {
            setcookie('tu', null, false, '/', $_SERVER['SERVER_NAME']);
            setcookie('tp', null, false, '/', $_SERVER['SERVER_NAME']);
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
            return $this->getAdminAccount($_COOKIE['tu'], !empty($newPassword) ? $newPassword : $_COOKIE['tp'], false);
        else
            return null;
    }

    private function _custom_api_signin () {
        $accountObj = new libraryDataObject();

        $errors = array();

        $credentials = libraryRequest::fromREQUEST('credentials');

        if (empty($credentials['email']))
            $errors['email'] = 'Empty';

        if (empty($credentials['password']))
            $errors['password'] = 'Empty';

        if (count($errors)) {
            $accountObj->setError($errors);
            return $accountObj;
        }

        $account = $this->getAdminAccount($credentials['email'], $credentials['password'], true);

        // var_dump($account);

        if (empty($account))
            $accountObj->setError('WrongCredentials');
        else {
            $accountObj->setData('profile', $account);

            // keep user logged in
            if ($credentials['remember']) {
                /* Set cookie to last 1 year */
                setcookie('tu', $credentials['email'], time()+60*60*24*365, '/', $_SERVER['SERVER_NAME'], false, true);
                setcookie('tp', $account['Password'], time()+60*60*24*365, '/', $_SERVER['SERVER_NAME'], false, true);
            
            } else {
                /* Cookie expires when browser closes */
                setcookie('tu', $credentials['email'], false, '/', $_SERVER['SERVER_NAME'], false, true);
                setcookie('tp', $account['Password'], false, '/', $_SERVER['SERVER_NAME'], false, true);
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
        setcookie('tu', null, false, '/', $_SERVER['SERVER_NAME']);
        setcookie('tp', null, false, '/', $_SERVER['SERVER_NAME']);
        unset($_SESSION['Toolbox:ProfileID']);
        $accountObj->setData('profile', null);
        return $accountObj;
    }

}

?>