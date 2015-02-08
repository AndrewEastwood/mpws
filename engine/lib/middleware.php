<?php
namespace engine\lib;

use \engine\lib\request as Request;
use \engine\lib\response as Response;
use \engine\lib\utils as Utils;

class middleware {

    private static $regMW = array();
    private static $cacheMWs = array();

    static public function register ($mwName) {
        if (is_array($mwName))
            self::$regMW += $mwName;
        else
            self::$regMW[] = $mwName;
    }

    static public function process () {
        $rez = true;
        foreach (self::$regMW as $mwName) {
            if (isset(self::$cacheMWs[$mwName])) {
                $mw = self::$cacheMWs[$mwName];
            } else {
                $mwClass = Utils::getMWClassName($mwName);
                self::$cacheMWs[$mwName] = new $mwClass();
                $mw = self::$cacheMWs[$mwName];
                if (empty($mw)) {
                    header("HTTP/1.0 500 Internal Server Error");
                    die();
                }
                $mwRez = $mw->process();
                $rez = $rez && is_bool($mwRez) ? $mwRez : true;
            }
        }
        if ($rez !== true) {
            header("HTTP/1.0 500 Internal Server Error");
            die();
        }
    }

}

?>