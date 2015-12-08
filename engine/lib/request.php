<?php
namespace engine\lib;

class request {

    var $PHP_INPUT = false;
    protected static $_instance;

    private function __construct () {
        $this->PHP_INPUT = file_get_contents('php://input');
    }
    private function __clone () {}

    public static function getInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __get ($name) {
        $data = $this->getRequestObj();
        // echo "Getting '$name'\n";
        if (array_key_exists($name, $data)) {
            return $data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function getScriptName () {
        $name = $_SERVER['REDIRECT_URL'];
        return basename($name, '.js');
    }

    public function getRequestObj () {
        $req = array(
            "get" => $_GET,
            "post" => $_POST,
            "data" => json_decode($this->PHP_INPUT, true),
            "id" => self::getRequestedID(),
            "externalKey" => self::getRequestedExternalKey()
        );
        return (object) $req;
    }

    public function getRequestedExternalKey () {
        $req = $_GET;
        if (isset($req->get['id']) && !is_numeric($req->get['id'])) {
            return $req->get['id'];
        }
        return null;
    }

    public function getRequestedID () {
        $req = $_GET;
        if (isset($req->get['id']) && is_numeric($req->get['id'])) {
            return intval($req->get['id']);
        }
        return null;
    }

    public function hasRequestedID () {
        return is_numeric(self::getRequestedID());
    }

    public function hasRequestedExternalKey () {
        return !empty(self::getRequestedExternalKey()) && !is_numeric(self::getRequestedExternalKey());
    }

    public function noRequestedItem () {
        return !isset($_GET['id']);
    }

    /* get values */
    public function pickFromGET($key, $defaultValue = null) {
        if (isset($_GET[$key]))
            return $_GET[$key];
        return $defaultValue;
    }

    public function hasInGet() {
        for ($i = 0, $num = func_num_args(); $i < $num; $i++)
            if (!isset($_GET[func_get_arg($i)]))
                return false;
        return true;
    }

    public function isPOST () {
        return $_SERVER['REQUEST_METHOD'] === "POST";
    }

    public function isGET () {
        return $_SERVER['REQUEST_METHOD'] === "GET";
    }

    public function isPATCH () {
        return $_SERVER['REQUEST_METHOD'] === "PATCH";
    }

    public function isPUT () {
        return $_SERVER['REQUEST_METHOD'] === "PUT";
    }

    public function isDELETE () {
        return $_SERVER['REQUEST_METHOD'] === "DELETE";
    }
}

?>