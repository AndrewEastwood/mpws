<?php
namespace engine\objects;

class middleware {

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

    public function getCustomerConfiguration () {
        return $this->customer->getConfiguration();
    }

}

?>