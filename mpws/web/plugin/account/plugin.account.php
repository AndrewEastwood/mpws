<?php

class pluginAccount extends objectPlugin {

    public function getResponse () {

        switch(libraryRequest::getValue('fn')) {
            case "create": {
                $data = $this->_custom_api_createAccount();
                break;
            }
            case "profile": {
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
                }
                break;
            }
        }

        // attach to output
        return $data;
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

            $_SESSION['Account:ProfileID'] = $account['ID'];
        }

        return $accountObj;
    }

    private function _custom_api_createAccount () {
        $dataAccount = libraryRequest::getPostValue('account');

        $accountObj = new libraryDataObject();
        $errors = array();

        if (empty($dataAccount['FirstName']))
            $errors['FirstName'] = 'Empty';

        if (empty($dataAccount['LastName']))
            $errors['LastName'] = 'Empty';

        if (empty($dataAccount['EMail']))
            $errors['EMail'] = 'Empty';

        if (empty($dataAccount['Password']))
            $errors['Password'] = 'Empty';

        if ($dataAccount['Password'] != $dataAccount['ConfirmPassword'])
            $errors['ConfirmPassword'] = 'WrongConfirmPassword';

        if (count($errors)) {
            $accountObj->setError($errors);
            $accountObj->setData("values", $dataAccount);
            return $accountObj;
        }

        unset($dataAccount['ConfirmPassword']);

        $this->getCustomer()-> addAccount($dataAccount);

        $accountObj->setData("success", true);

        return $accountObj;
    }

    private function _custom_api_status () {
        $accountObj = new libraryDataObject();
        $account = $this->getCustomer()->isAccountSignedIn();
        $accountObj->setData('profile', $account);
        return $accountObj;
    }

    private function _custom_api_signout () {
        $accountObj = new libraryDataObject();
        setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
        setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
        unset($_SESSION['Account:ProfileID']);
        $accountObj->setData('success', true);
        return $accountObj;
    }

}

?>