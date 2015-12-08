<?php

namespace engine\lib;

class response {

    var $_RESPONSE = array();
    protected static $_instance;

    private function __construct () { }
    private function __clone () {}

    public static function getInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function setResponse ($resp) {
        $this->_RESPONSE = $resp;
    }

    public function getResponse () {
        return $this->_RESPONSE;
    }

    public function getJSONResponse () {
        // $output = new \engine\lib\dataobject($this->_RESPONSE);
        // $_out = $output->toJSON();
        $_out = json_encode($this->_RESPONSE);
        if ($_out === "null")
            $_out = "{}";
        return $_out;
    }

    public function setError ($errorMsg, $headerMsg = false) {
        if (!empty($errorMsg))
            $this->_RESPONSE['error'] = $errorMsg;
        if (!empty($headerMsg))
            header($headerMsg);
    }

}

?>