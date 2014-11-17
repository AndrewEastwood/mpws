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
        return (object) array(
            "get" => $_GET,
            "post" => $_POST,
            "data" => json_decode(self::$PHP_INPUT, true)
        );
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
