<?php

class pluginAccount extends objectPlugin {

    public function getAddress ($AddressID) {
        $config = configurationCustomerDataSource::jsapiGetAddress($AddressID);
        return $this->getCustomer()->fetch($config);
    }

    public function getAccountByID ($id) {
        $config = configurationCustomerDataSource::jsapiGetAccountByID($id);
        $account = $this->getCustomer()->fetch($config);
        // var_dump('getAccountByID', $id);
        // var_dump($account);
        return $account;
    }

}

?>