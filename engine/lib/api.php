<?php
namespace \engine\lib;

use \engine\lib\request as Request;
use \engine\lib\response as Response;
use \engine\lib\utils as Utils;

class api {

    private static $cacheApis = array();

    // apiKey must in the following format "pluginName:apiName"
    static public function getAPI ($apiKey = false) {
        if (empty($apiKey)) {
            $apiKey = Request::pickFromGET('api');
        }
        $api = null;
        if (isset(self::cacheApis[$apiKey]))
            $api = self::cacheApis[$apiKey];
        else {
            $apiClass = Utils::getApiClassName($_fn, $_source);
            self::cacheApis[$apiKey] = new $apiClass();
            $api = self::cacheApis[$apiKey];
        }
        return $api;
    }

    static public function execAPI ($apiKey = false, $method = false) {
        // get api
        if (empty($method)) {
            $method = strtolower($_SERVER['REQUEST_METHOD']);
        }
        $api = self::getAPI($apiKey);
        // invoke api request method
        if (isset($api)) {
            $api->$method(Response::$_RESPONSE, $_REQ);
        } else {
            header("HTTP/1.0 500 Internal Server Error");
            die();
        }
    }

}

?>