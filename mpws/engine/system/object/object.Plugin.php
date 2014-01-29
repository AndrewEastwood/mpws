<?php

class objectPlugin {

    private $customer;

    function __construct ($customer) {
        $this->customer = $customer;
    }

    public function getCustomer() {
        return $this->customer;
    }

    public function getDataBase () {
        return $this->customer->getDataBase();
    }

    public function getResponse () {
        return "";
    }

}

?>