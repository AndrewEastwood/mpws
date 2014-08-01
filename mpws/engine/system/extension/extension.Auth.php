<?php

class extensionAuth extends objectExtension {

    public function getAuthID ($refresh = true) {
        if (!isset($_SESSION['AccountID']))
            $_SESSION['AccountID'] = null;
        if (!$refresh)
            return $_SESSION['AccountID'];
        if (isset($_SESSION['AccountID'])) {
            $configPermissions = configurationCustomerDataSource::jsapiGetPermissions($_SESSION['AccountID']);
            $permissions = $this->getCustomer()->fetch($configPermissions, true) ?: array();
            if (glIsToolbox() && empty($account['IsAdmin']))
                return $this->clearAuthID();
        }
        return $_SESSION['AccountID'];
    }

    public function clearAuthID () {
        if (!empty($_SESSION['AccountID'])) {
            $configOffline = configurationCustomerDataSource::jsapiSetOnlineAccount($_SESSION['AccountID']);
            $this->getCustomer()->fetch($configOffline);
        }
        $_SESSION['AccountID'] = null;
        return null;
    }

    public function get_status (&$resp) {
        $resp['auth_id'] = $this->getAuthID();
    }

    public function post_signin (&$resp, $req) {

        $password = $req->post['password'];
        $email = $req->post['email'];
        $remember = $req->post['remember'];

        if (empty($email) || empty($password)) {
            $resp['error'] = 'WrongCredentials';
            return;
        }

        $password = librarySecure::EncodeAccountPassword($password);

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
            // $accountObj->setData('profile', $account);
            // var_dump($account);
            // if (glIsToolbox() && empty($account['IsAdmin']))
            //     return $this->post_signout($resp);

            // keep user logged in
            // if (!empty($remember)) {
            //     /* Set cookie to last 1 year */
            //     // setcookie('username', $email, time()+60*60*24*365, '/', $_SERVER['SERVER_NAME']);
            //     // setcookie('password', $account['Password'], time()+60*60*24*365, '/', $_SERVER['SERVER_NAME']);
            
            // } else {
            //     /* Cookie expires when browser closes */
            //     // setcookie('username', $email, false, '/', $_SERVER['SERVER_NAME']);
            //     // setcookie('password', $account['Password'], false, '/', $_SERVER['SERVER_NAME']);
            // }

            $_SESSION['AccountID'] = $AccountID;
            $configOnline = configurationCustomerDataSource::jsapiSetOnlineAccount($AccountID);
            $this->getCustomer()->fetch($configOnline);
        }

        // $resp['account_id'] = $AccountID;
        $resp['auth_id'] = $this->getAuthID();
    }


    public function post_signout (&$resp) {
        $resp['auth_id'] = $this->clearAuthID();
        // $this->getAuthID();
        // $resp['authenticated'] = false;
    }
}

?>