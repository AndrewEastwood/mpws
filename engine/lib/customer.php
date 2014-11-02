<?php
namespace engine\lib;

class customer {

    private static $_customer;

    static function getCustomer () {
        if (!empty(self::$_customer))
            return self::$_customer;
        $_customerScript = glGetFullPath(DIR_WEB, DIR_CUSTOMER, MPWS_CUSTOMER, OBJECT_T_CUSTOMER . DOT . MPWS_CUSTOMER . EXT_SCRIPT);
        $_customerClass = OBJECT_T_CUSTOMER . BS . MPWS_CUSTOMER;
        include_once $_customerScript;
        self::$_customer = new $_customerClass();
        return self::$_customer;
    }

    static function runCustomer () {
        $customer = self::getCustomer();
        switch (MPWS_REQUEST) {
            case 'API':
                $customer->runAsAPI();
                break;
            case 'AUTH':
                $customer->runAsAUTH();
                break;
            case 'UPLOAD':
                $customer->runAsUPLOAD();
                break;
            default:
                throw new Exception("Error Processing Request: Unknown request type", 1);
        }
    }
}

?>