<?php

class objectPlugin implements IPlugin {

    private $customer;

    function __construct ($customer) {
        $this->customer = $customer;
    }

    public function getCustomer() {
        return $this->customer;
    }

    public function getCustomerDataBase () {
        return $this->customer->getDataBase();
    }

    public function getPlugin ($pluginName) {
        $anotherPlugin = $this->getCustomer()->getPlugin($pluginName);
        return $anotherPlugin;
    }

    // public function getExtension ($extensionName) {
    //     return $this->getCustomer()->getExtension($extensionName);
    // }

    // public function getSessionAccountID () {
    //     // $extAuth = $this->getExtension('Auth');
    //     // if (empty($extAuth))
    //     //     throw new Exception("Auth extension is missing for plugin", 1);
    //     return $this->getCustomer()->getAuthID();
    // }

    // public function ifYouCan ($action) {
    //     // $extAuth = $this->getExtension('Auth');
    //     // if (empty($extAuth))
    //     //     throw new Exception("Auth extension is missing for plugin", 1);
    //     return $this->getCustomer()->ifYouCan($action);
    // }

    public function run () {
        libraryRequest::processRequest($this);
    }
}

?>