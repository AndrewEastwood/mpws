<?php
namespace engine\lib;

use \engine\lib\request as Request;
use \engine\lib\response as Response;
use \engine\lib\route as Route;
use \engine\lib\utils as Utils;

class api {

    private static $cacheApis = array();

    // apiKey must in the following format "pluginName:apiName"
    static public function getAPI ($apiKey = false) {
        if (empty($apiKey)) {
            $apiKey = Request::pickFromGET('api');
        }
        $api = null;
        if (isset(self::$cacheApis[$apiKey])) {
            $api = self::$cacheApis[$apiKey];
        } else {
            if (empty($apiKey)) {
                return $api;
            }
            $parts = explode(':', $apiKey);
            $apiClass = Utils::getApiClassName($parts[0], $parts[1]);
            // echo $apiClass . '.php';
            // if (!file_exists($apiClass . '.php')) {
            //     return null;
            // }
            self::$cacheApis[$apiKey] = new $apiClass();
            $api = self::$cacheApis[$apiKey];
            if (empty($api)) {
                header("HTTP/1.0 404 Not Found");
                die();
            }
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
        $_REQ = Request::getRequestData();
        if (isset($api)) {
            // $r = new Route($method, xxx)
            // $api->route(Response::$_RESPONSE, $_REQ);
            $api->$method(Response::$_RESPONSE, $_REQ);
        } else {
            header("HTTP/1.0 404 Not Found");
            die();
        }
    }

}

?>