<?php

class pluginAccount extends objectPlugin {



    // public function get_auth () {
    //     $data = new libraryDataObject();
    //     $do = libraryRequest::fromGET('action');
    //     switch($do) {
    //         case "status": {
    //             $data = $this->_custom_api_status();
    //             break;
    //         }
    //     }
    //     return $data;
    // }

    // public function post_auth () {
    //     $data = new libraryDataObject();
    //     $do = libraryRequest::fromGET('action');
    //     switch($do) {
    //         case "signin": {
    //             $data = $this->_custom_api_signin();
    //             break;
    //         }
    //         case "signout": {
    //             $data = $this->_custom_api_signout();
    //             break;
    //         }
    //     }
    //     return $data;
    // }

    // private function getAdminAccount ($email, $password, $encodePassword) {
    //     if ($encodePassword)
    //         $password = $this->getCustomer()->encodeAccountPassword($password);

    //     // var_dump($password);
    //     // var_dump($passwordS);
    //     $config = configurationToolboxDataSource::jsapiGetAdminAccount($email, $password);
    //     // var_dump($config);
    //     return $this->getCustomer()->processData($config);
    // }


    // private function _isAccountSignedIn () {
    //     if (isset($_COOKIE['username']) && isset($_COOKIE['password']) && isset($_SESSION['Account:ProfileID']))
    //         return true;
    //     else {
    //         setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
    //         setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
    //         unset($_SESSION['Account:ProfileID']);
    //         return false;
    //     }
    // }



    private function _custom_api_signin2 () {
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

    private function _custom_api_signin ($email, $password) {
        $accountObj = new libraryDataObject();

        $errors = array();

        $credentials = libraryRequest::fromPOST('credentials');

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
    private function _custom_api_status2 () {
        $accountObj = new libraryDataObject();
        $account = $this->getActiveAccount();
        $accountObj->setData('profile', $account);
        return $accountObj;
    }

    private function _custom_api_signout2 () {
        $accountObj = new libraryDataObject();
        setcookie('tu', null, false, '/', $_SERVER['SERVER_NAME']);
        setcookie('tp', null, false, '/', $_SERVER['SERVER_NAME']);
        unset($_SESSION['Toolbox:ProfileID']);
        $accountObj->setData('profile', null);
        return $accountObj;
    }



/******************************************************/
/******************************************************/
/******************************************************/

    public function _getAccountByID ($id) {
        $config = configurationAccountDataSource::jsapiGetAccountByID($id);
        $account = $this->getCustomer()->processData($config);
        // var_dump('_getAccountByID', $id);
        // var_dump($account);
        return $account;
    }

    private function _getSessionAccount () {
        $account = null;
        if (isset($_SESSION['Account']) && !empty($_SESSION['Account']['ID']))
            $account = $this->_getAccountByID($_SESSION['Account']['ID']);

        if (empty($account)) {
            setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
            setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
            unset($_SESSION['Account']);
        }

        return $account;
    }

    public function get_status () {
        $data = new libraryDataObject();
        // if ($this->_isAccountSignedIn()) {
        //     $data->setData('status', 'ok');
        // } else
        //     $data->setData('status', 'none');
        $data->setData('account', $this->_getSessionAccount());
        return $data;
    }

    public function post_signin () {
        $accountObj = new libraryDataObject();

        $errors = array();

        $email = libraryRequest::fromREQUEST('email');
        $password = libraryRequest::fromREQUEST('password');
        $remember = libraryRequest::fromREQUEST('remember');

        if (empty($email))
            $errors['email'] = 'Empty';

        if (empty($password))
            $errors['password'] = 'Empty';

        if (count($errors)) {
            $accountObj->setError($errors);
            return $accountObj;
        }

        $password = $this->encodeAccountPassword($password);

        $account = $this->getAccount($email, $password);

        if (empty($account))
            $accountObj->setError('WrongCredentials');
        else {
            $accountObj->setData('account', $account);

            // keep user logged in
            if (!MPWS_IS_TOOLBOX && $remember) {
                /* Set cookie to last 1 year */
                setcookie('username', $email, time()+60*60*24*365, '/', $_SERVER['SERVER_NAME']);
                setcookie('password', $account['Password'], time()+60*60*24*365, '/', $_SERVER['SERVER_NAME']);
            } else {
                /* Cookie expires when browser closes */
                setcookie('username', $email, false, '/', $_SERVER['SERVER_NAME']);
                setcookie('password', $account['Password'], false, '/', $_SERVER['SERVER_NAME']);
            }

            $_SESSION['Account'] = $account;
        }

        // var_dump($_SESSION);

        return $accountObj;
    }

    public function getAccount ($login, $password) {
        $config = configurationAccountDataSource::jsapiGetAccount($login, $password);
        // var_dump($config);
        $account = $this->getCustomer()->processData($config);
        if (isset($account['ID']))
            $account["addresses"] = $this->getAccountAddresses($account['ID']);
        return $account;
    }

    public function post_logout () {

    }

    public function post_user () {

    }

    public function get_user () {

    }

    public function put_user () {

    }

    public function _getResponse () {

        $data = new libraryDataObject();

        switch(libraryRequest::fromGET('fn')) {
            case "create": {
                $data = $this->_custom_api_createAccount();
                break;
            }
            case "profile": {
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
                        $AddressID = libraryRequest::fromPOST('AddressID');
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



    private function _custom_api_createAccount () {
        $dataAccount = libraryRequest::fromPOST('account');

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
        $account = $this->getActiveAccount();
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
        $account = libraryRequest::fromPOST('account');
        $accountObj = new libraryDataObject();
        $errors = array();

        if (!$this->_isAccountSignedIn()) {
            $accountObj->setData("profile", null);
            return $accountObj;
        }

        $account['AccountID'] = $this->_getSessionAccountID();

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

        $accountObj->setData("profile", $this->getActiveAccount());
        $accountObj->setData("success", true);

        return $accountObj;
    }

    private function _custom_api_manageAddress ($createNew = false) {
        $accountObj = new libraryDataObject();

        if (!$this->_isAccountSignedIn()) {
            $accountObj->setData("profile", null);
            return $accountObj;
        }

        $profile = $this->getActiveAccount();
        $accountObj->setData("profile", $profile);

        $errors = array();


        if (count($profile['addresses']) >= 3 && $createNew) {
            $accountObj->setError('MaxAddressesReached');
            return $accountObj;
        }

        $dataAddress = libraryRequest::fromPOST('address');

        $dataAddress['AccountID'] = $this->_getSessionAccountID();

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
            $accountObj->setData("profile", $this->getActiveAccount());
            return $accountObj;
        }

        if ($createNew)
            $this->getCustomer()->addAccountAddress($dataAddress);
        else
            $this->getCustomer()->updateAccountAddress($dataAddress);

        $accountObj->setData("profile", $this->getActiveAccount());
        $accountObj->setData("success", true);

        return $accountObj;
    }

    private function _custom_api_removeAddress ($AddressID) {
        $accountObj = new libraryDataObject();
        $errors = array();
        
        if (!$this->_isAccountSignedIn()) {
            $accountObj->setData("profile", null);
            return $accountObj;
        }

        if (empty($AddressID))
            $errors['AddressID'] = 'Empty';

        if (count($errors)) {
            $accountObj->setError($errors);
            $accountObj->setData("profile", $this->getActiveAccount());
            // $accountObj->setData("values", $dataAddress);
            return $accountObj;
        }

        $this->getCustomer()->removeAccountAddress($this->_getSessionAccountID(), $AddressID);

        $accountObj->setData("profile", $this->getActiveAccount());
        $accountObj->setData("success", true);

        return $accountObj;
    }

    private function _custom_api_updatePassword () {
        $dataAccount['Password'] = libraryRequest::fromPOST('Password');
        $dataAccount['ConfirmPassword'] = libraryRequest::fromPOST('ConfirmPassword');

        $accountObj = new libraryDataObject();
        $errors = array();

        if (empty($dataAccount['Password']))
            $errors['Password'] = 'Empty';

        if ($dataAccount['Password'] != $dataAccount['ConfirmPassword'])
            $errors['ConfirmPassword'] = 'WrongConfirmPassword';

        if (count($errors)) {
            $accountObj->setError($errors);
            $accountObj->setData("profile", $this->getActiveAccount());
            return $accountObj;
        }

        unset($dataAccount['ConfirmPassword']);

        $password = $this->getCustomer()->updateAccountPassword($dataAccount);

        // var_dump($password);
        setcookie('password', $password, false, '/', $_SERVER['SERVER_NAME']);

        $profile = $this->getActiveAccount($password);

        $accountObj->setData("profile", $profile);
        $accountObj->setData("success", true);

        return $accountObj;
    }

    // Accounts
    public function addAccount ($dataAccount) {

        $dataAccount["CustomerID"] = $this->getCustomerID();
        $dataAccount["ValidationString"] = md5(time());
        $dataAccount['Password'] = $this->encodeAccountPassword($dataAccount['Password']);
        $dataAccount['IsTemporary'] = 1;
        $dataAccount['DateCreated'] = date('Y:m:d H:i:s');
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationAccountDataSource::jsapiAddAccount();
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->getCustomer()->processData($config);
        return $this->getDataBase()->getLastInsertId();
    }



    public function activateAccount ($ValidationString) {
        $config = configurationAccountDataSource::jsapiActivateAccount($ValidationString);
        $this->getCustomer()->processData($config);
    }

    public function removeAccount ($dataAccount) {
        $AccountID = $dataAccount['AccountID'];
        unset($dataAccount['AccountID']);
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationAccountDataSource::jsapiRemoveAccount($AccountID);
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->getCustomer()->processData($config);
    }

    public function updateAccount ($dataAccount) {
        $AccountID = $dataAccount['AccountID'];
        unset($dataAccount['AccountID']);
        if (isset($dataAccount['Password']))
            $dataAccount['Password'] = $this->encodeAccountPassword($dataAccount['Password']);
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationAccountDataSource::jsapiUpdateAccount($AccountID);
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->getCustomer()->processData($config);
    }

    public function updateAccountPassword ($dataAccount) {
        $AccountID = $dataAccount['AccountID'];
        unset($dataAccount['AccountID']);
        if (isset($dataAccount['Password']))
            $dataAccount['Password'] = $this->encodeAccountPassword($dataAccount['Password']);
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationAccountDataSource::jsapiUpdateAccount();
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->getCustomer()->processData($config);
        return $dataAccount['Password'];
    }

    public function encodeAccountPassword ($rawPassword) {
        $key = '!MPWSservice123';
        return md5($key . $rawPassword);
    }

    public function getAccountAddresses ($AccountID) {
        // if (!$this->_isAccountSignedIn() && !$force)
        //     return false;
        $config = configurationAccountDataSource::jsapiGetAccountAddresses($AccountID);
        return $this->getCustomer()->processData($config);
    }

    public function getAccountAddress ($AccountID, $AddressID) {
        $config = configurationAccountDataSource::jsapiGetAccountAddress($AccountID, $AddressID);
        return $this->getCustomer()->processData($config);
    }

    public function getAddress ($AddressID) {
        $config = configurationAccountDataSource::jsapiGetAddress($AddressID);
        return $this->getCustomer()->processData($config);
    }

    public function addAccountAddress ($address) {
        $address['DateCreated'] = date('Y:m:d H:i:s');
        $address['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationAccountDataSource::jsapiAddAccountAddress();
        $config['data'] = array(
            "fields" => array_keys($address),
            "values" => array_values($address)
        );
        // var_dump($config);
        $this->getCustomer()->processData($config);
        return $this->getDataBase()->getLastInsertId();
    }

    public function updateAccountAddress ($address) {
        $AccountID = $address['AccountID'];
        unset($address['AccountID']);
        $AddressID = $address['AddressID'];
        unset($address['AddressID']);
        $address['DateUpdated'] = date('Y:m:d H:i:s');
        // var_dump($address);
        $config = configurationAccountDataSource::jsapiUpdateAccountAddress($AccountID, $AddressID, $address);
        $config['data'] = array(
            "fields" => array_keys($address),
            "values" => array_values($address)
        );
        // var_dump($config);
        $this->getCustomer()->processData($config);
    }

    public function removeAccountAddress ($AccountID, $AddressID) {
        $config = configurationAccountDataSource::jsapiRemoveAccountAddress($AccountID, $AddressID);
        // var_dump($config);
        $this->getCustomer()->processData($config);
    }

    public function getAccountStats () {
        $stats = array();
        $filterProducts = array(
            array("key" => "ByStatusActive", "filter" => "Status (=) ?", "value" => array("ACTIVE")),
            array("key" => "ByStatusRemoved", "filter" => "Status (=) ?", "value" => array("REMOVED")),
            array("key" => "ByStatusActiveAndIsTemporary", "filter" => "Status (=) ? + IsTemporary (=) ?", "value" => array("ACTIVE", 1))
        );
        foreach ($filterProducts as $filterItem) {
            $configCount = configurationAccountDataSource::jsapiUtil_GetTableRecordsCount(configurationAccountDataSource::$Table_SystemAccounts);
            $configCount['condition']['filter'] = $filterItem['filter'];
            $configCount['condition']['values'] = $filterItem['value'];
            $dataCount = $this->getCustomer()->processData($configCount);
            $stats[$filterItem['key']] = $dataCount['ItemsCount'];
        }
        return $stats;
    }
}

?>