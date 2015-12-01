<?php

namespace engine\lib;

use \engine\lib\response as Response;

class request {

    private static $PHP_INPUT = false;

    static function setPhpInput ($data) {
        self::$PHP_INPUT = $data;
    }

    static function getScriptName () {
        $name = $_SERVER['REDIRECT_URL'];
        return basename($name, '.js');
    }

    static function getRequestData () {
        $req = array(
            "get" => $_GET,
            "post" => $_POST,
            "data" => json_decode(self::$PHP_INPUT, true),
            "id" => self::getRequestedID(),
            "externalKey" => self::getRequestedExternalKey()
        );
        return (object) $req;
    }

    static function getRequestedExternalKey () {
        $req = $_GET;
        if (isset($req->get['id']) && !is_numeric($req->get['id'])) {
            return $req->get['id'];
        }
        return null;
    }

    static function getRequestedID () {
        $req = $_GET;
        if (isset($req->get['id']) && is_numeric($req->get['id'])) {
            return intval($req->get['id']);
        }
        return null;
    }

    static function hasRequestedID () {
        return is_numeric(self::getRequestedID());
    }

    static function hasRequestedExternalKey () {
        return !empty(self::getRequestedExternalKey()) && !is_numeric(self::getRequestedExternalKey());
    }

    static function noRequestedItem () {
        return !isset($_GET['id']);
    }

    /* get values */
    static function pickFromGET($key, $defaultValue = null) {
        if (isset($_GET[$key]))
            return $_GET[$key];
        return $defaultValue;
    }

    static function hasInGet() {
        for ($i = 0, $num = func_num_args(); $i < $num; $i++)
            if (!isset($_GET[func_get_arg($i)]))
                return false;
        return true;
    }

    static function isPOST () {
        return $_SERVER['REQUEST_METHOD'] === "POST";
    }

    static function isGET () {
        return $_SERVER['REQUEST_METHOD'] === "GET";
    }

    static function isPATCH () {
        return $_SERVER['REQUEST_METHOD'] === "PATCH";
    }

    static function isPUT () {
        return $_SERVER['REQUEST_METHOD'] === "PUT";
    }

    static function isDELETE () {
        return $_SERVER['REQUEST_METHOD'] === "DELETE";
    }
}

?>