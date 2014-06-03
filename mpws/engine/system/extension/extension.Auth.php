<?php

class extensionAuth extends objectExtension {

    public function getSessionAccount () {
        $account = null;
        if (isset($_SESSION['Account']) && !empty($_SESSION['Account']['ID'])) {
            $config = configurationCustomerDataSource::jsapiGetAccountByID($_SESSION['Account']['ID']);
            $account = $this->getCustomer()->processData($config);
            if (MPWS_IS_TOOLBOX && empty($account['Permission_isAdmin']))
                return $this->сlearSessionAccount();
        }

        return $account;
    }

    public function clearSessionAccount () {
        setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
        setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
        unset($_SESSION['Account']);
        return null;
    }

    public function isAuthenticated () {
        $account = $this->getSessionAccount();
        $isAauthenticated = !empty($account);
        return $isAauthenticated;
    }

    public function get_status (&$resp) {
        $account = $this->getSessionAccount();
        $resp['account'] = $account;
        $resp['authenticated'] = !empty($account);
    }

    public function post_signin (&$resp) {
        $credentials = libraryRequest::getObjectFromREQUEST('email', 'password', 'remember');
        if (empty($credentials['email']) || empty($credentials['password'])) {
            $resp['error'] = 'WrongCredentials';
            return;
        }

        $credentials['password'] = librarySecure::EncodeAccountPassword($credentials['password']);

        $config = configurationCustomerDataSource::jsapiGetAccount($credentials['email'], $credentials['password']);
        $account = $this->getCustomer()->processData($config);

        if (empty($account))
            $resp['error'] = 'WrongCredentials';
        else {
            // $accountObj->setData('profile', $account);
            // var_dump($account);
            if (MPWS_IS_TOOLBOX && empty($account['Permission_isAdmin']))
                return $this->post_signout($resp);

            // keep user logged in
            if (!empty($credentials['remember'])) {
                /* Set cookie to last 1 year */
                setcookie('username', $credentials['email'], time()+60*60*24*365, '/', $_SERVER['SERVER_NAME']);
                setcookie('password', $account['Password'], time()+60*60*24*365, '/', $_SERVER['SERVER_NAME']);
            
            } else {
                /* Cookie expires when browser closes */
                setcookie('username', $credentials['email'], false, '/', $_SERVER['SERVER_NAME']);
                setcookie('password', $account['Password'], false, '/', $_SERVER['SERVER_NAME']);
            }

            $_SESSION['Account'] = $account;
        }

        $resp['account'] = $account;
        $resp['authenticated'] = true;
    }


    public function post_signout (&$resp) {
        $this->clearSessionAccount();
        $resp['account'] = null;
        $resp['authenticated'] = false;
    }
}

?>