<?php

class libraryAccountManager {

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

    private function _clearSessionAccount () {
        setcookie('username', null, false, '/', $_SERVER['SERVER_NAME']);
        setcookie('password', null, false, '/', $_SERVER['SERVER_NAME']);
        unset($_SESSION['Account']);
        return null;
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
}

?>