<?php
namespace engine\objects;
use \engine\lib\utils as Utils;

class configuration {

    private $customer;
    private $app;

    function __construct ($customer, $app) {
        $this->customer = $customer;
        $this->app = $app;
    }

    public function getCustomer () {
        return $this->customer;
    }

    public function getApp () {
        return $this->app;
    }


}

?>