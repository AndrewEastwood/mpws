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

    public function withPlugin ($pluginName) {
        // if ($this->getCustomer()->hasPlugin($pluginName)) {
            $anotherPlugin = $this->getCustomer()->getPlugin($pluginName);
            return $anotherPlugin;
        // }
    }

    public function run () {
        libraryRequest::processRequest($this);
    }
}

?>