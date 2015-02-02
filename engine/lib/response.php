<?php

namespace engine\lib;

class response {

    static $_RESPONSE = array();

    static function setResponse ($resp) {
        self::$_RESPONSE = $resp;
    }

    static function getResponse () {
        return self::$_RESPONSE;
    }

    static function getJSONResponse () {
        // $output = new \engine\lib\dataobject(self::$_RESPONSE);
        // $_out = $output->toJSON();
        $_out = json_encode(self::$_RESPONSE);
        if ($_out === "null")
            $_out = "{}";
        return $_out;
    }

    static function setError ($errorMsg, $headerMsg = false) {
        if (!empty($errorMsg))
            self::$_RESPONSE['error'] = $errorMsg;
        if (!empty($headerMsg))
            header($headerMsg);
    }

}

?>