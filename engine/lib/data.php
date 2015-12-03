<?php

namespace engine\lib;

class data {

    var $db;

    function __construct () {
        global $app;
        $this->db = $app->getDB();
    }

    public function getSuccessResultObjectWithParams () {
        $data = func_get_args();
        $result = array(
            'success' => true,
            'errors' => array()
        );
        return $data + $result;
    }
    public function getSuccessResultObject (array $data) {
        $result = array(
            'success' => true,
            'errors' => array()
        );
        return $data + $result;
    }
    public function getFailedResultObject ($errors, array $data = array()) {
        if (is_string($errors)) {
            $errors = array($string);
        }
        $result = array(
            'success' => false,
            'errors' => $errors
        );
        return $data + $result;
    }
}

?>