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

    public function getExtension ($extensionName) {
        return $this->getCustomer()->getExtension($extensionName);
    }

    public function getSessionAccountID () {
        $extAuth = $this->getExtension('auth');
        if (empty($extAuth))
            throw new Exception("Auth extension is missing for plugin", 1);
        return $extAuth->getAuthID();
    }

    public function ifYou ($canDoThis) {
        $extAuth = $this->getExtension('auth');
        if (empty($extAuth))
            throw new Exception("Auth extension is missing for plugin", 1);
        return $extAuth->ifYou($canDoThis);
    }

    public function run () {
        libraryRequest::processRequest($this);
    }
}

?>