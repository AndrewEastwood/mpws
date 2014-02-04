<?php

class libraryCustomer {

    private $_customer;

    function __construct() {
        $_customerScript = glGetFullPath(DIR_WEB, DIR_CUSTOMER, MPWS_CUSTOMER, OBJECT_T_CUSTOMER . DOT . MPWS_CUSTOMER . EXT_SCRIPT);
        $_customerClass = OBJECT_T_CUSTOMER . str_replace(' ', '', ucwords(str_replace(array('.', '-'), ' ', MPWS_CUSTOMER)));
        include_once $_customerScript;
        $this->_customer = new $_customerClass();
    }

    function getResponse () {
        return $this->_customer->getResponse();
    }

    function getCustomer () {
        return $this->_customer;
    }

}

?>