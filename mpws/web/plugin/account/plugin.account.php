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
                    case "edit": {
                        $data = $this->_custom_api_edit();
                        break;
                    }
                    case "addAddress": {
                        $data = $this->_custom_api_manageAddress(true);
                        break;
                    }
                    case "updateAddress": {
                        $data = $this->_custom_api_manageAddress(false);
                        break;
                    }
                    case "removeAddress": {
                        $AddressID = libraryRequest::getPostValue('AddressID');
                        $data = $this->_custom_api_removeAddress($AddressID);
                        break;
                    }
                    case "updatePassword": {
                        $data = $this->_custom_api_updatePassword();
                        break;
                    }
                }
                break;
            }
        }

        // attach to output
        return $data;
    }

    private function isAccountSignedIn () {
        if (isset($_COOKIE['username']) && isset($_COOKIE['password']) && isset($_SESSION['Account:ProfileID']))
            return true;
        else {
            setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
            setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
            unset($_SESSION['Account:ProfileID']);
            return false;
        }
    }

    private function getActiveProfileID () {
        if ($this->isAccountSignedIn())
            return $_SESSION['Account:ProfileID'];
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
        $account = $this->getActiveProfile();
        $accountObj->setData('profile', $account);
        return $accountObj;
    }

    private function _custom_api_signout () {
        $accountObj = new libraryDataObject();
        setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
        setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
        unset($_SESSION['Account:ProfileID']);
        $accountObj->setData('profile', null);
        return $accountObj;
    }

    private function _custom_api_edit () {
        $account = libraryRequest::getPostValue('account');
        $accountObj = new libraryDataObject();
        $errors = array();

        if (!$this->isAccountSignedIn()) {
            $accountObj->setData("profile", null);
            return $accountObj;
        }

        $account['AccountID'] = $this->getActiveProfileID();

        if (empty($account['AccountID']))
            $errors['AccountID'] = 'Empty';

        if (empty($account['FirstName']))
            $errors['FirstName'] = 'Empty';


        if (count($errors)) {
            $accountObj->setError($errors);
            // $accountObj->setData("values", $dataAccount);
            return $accountObj;
        }

        // get all valid fields
        $dataAccount['AccountID'] = $account['AccountID'];
        $dataAccount['FirstName'] = $account['FirstName'];
        if (isset($account['LastName']))
            $dataAccount['LastName'] = $account['LastName'];
        if (isset($account['Phone']))
            $dataAccount['Phone'] = $account['Phone'];

        $this->getCustomer()->updateAccount($dataAccount);

        $accountObj->setData("profile", $this->getActiveProfile());
        $accountObj->setData("success", true);

        return $accountObj;
    }

    private function _custom_api_manageAddress ($createNew = false) {
        $accountObj = new libraryDataObject();

        if (!$this->isAccountSignedIn()) {
            $accountObj->setData("profile", null);
            return $accountObj;
        }

        $profile = $this->getActiveProfile();
        $accountObj->setData("profile", $profile);

        $errors = array();


        if (count($profile['addresses']) >= 3) {
            $accountObj->setError('MaxAddressesReached');
            return $accountObj;
        }

        $dataAddress = libraryRequest::getPostValue('address');

        $dataAddress['AccountID'] = $this->getActiveProfileID();

        if (empty($dataAddress['AccountID']))
            $errors['AccountID'] = 'Empty';

        if (empty($dataAddress['Address']))
            $errors['Address'] = 'Empty';

        if (empty($dataAddress['Country']))
            $errors['Country'] = 'Empty';

        if (empty($dataAddress['City']))
            $errors['City'] = 'Empty';

        if (!$createNew && empty($dataAddress['AddressID']))
            $errors['AddressID'] = 'Empty';

        if (count($errors)) {
            $accountObj->setError($errors);
            $accountObj->setData("profile", $this->getActiveProfile());
            return $accountObj;
        }

        if ($createNew)
            $this->getCustomer()->addAccountAddress($dataAddress);
        else
            $this->getCustomer()->updateAccountAddress($dataAddress);

        $accountObj->setData("profile", $this->getActiveProfile());
        $accountObj->setData("success", true);

        return $accountObj;
    }

    private function _custom_api_removeAddress ($AddressID) {
        $accountObj = new libraryDataObject();
        $errors = array();
        
        if (!$this->isAccountSignedIn()) {
            $accountObj->setData("profile", null);
            return $accountObj;
        }

        if (empty($AddressID))
            $errors['AddressID'] = 'Empty';

        if (count($errors)) {
            $accountObj->setError($errors);
            $accountObj->setData("profile", $this->getActiveProfile());
            // $accountObj->setData("values", $dataAddress);
            return $accountObj;
        }

        $this->getCustomer()->removeAccountAddress($this->getActiveProfileID(), $AddressID);

        $accountObj->setData("profile", $this->getActiveProfile());
        $accountObj->setData("success", true);

        return $accountObj;
    }

    private function _custom_api_updatePassword () {
        $dataAccount['Password'] = libraryRequest::getPostValue('Password');
        $dataAccount['ConfirmPassword'] = libraryRequest::getPostValue('ConfirmPassword');

        $accountObj = new libraryDataObject();
        $errors = array();

        if (empty($dataAccount['Password']))
            $errors['Password'] = 'Empty';

        if ($dataAccount['Password'] != $dataAccount['ConfirmPassword'])
            $errors['ConfirmPassword'] = 'WrongConfirmPassword';

        if (count($errors)) {
            $accountObj->setError($errors);
            $accountObj->setData("profile", $this->getActiveProfile());
            return $accountObj;
        }

        unset($dataAccount['ConfirmPassword']);

        $password = $this->getCustomer()->updateAccountPassword($dataAccount);

        // var_dump($password);
        setcookie('password', $password, false, '/', $_SERVER['SERVER_NAME']);

        $profile = $this->getActiveProfile($password);

        $accountObj->setData("profile", $profile);
        $accountObj->setData("success", true);

        return $accountObj;
    }

}

?>