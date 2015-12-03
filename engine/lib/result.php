<?php

namespace engine\lib;

class result {

    var $errors = array();
    var $success = null;
    var $result = null;

    public function fail () {
        if (!is_null($this->success)) {
            throw new Exception("Result type is set", 1);
        }
        $this->success = false;
        return $this;
    }

    public function success () {
        if (!is_null($this->success)) {
            throw new Exception("Result type is set", 1);
        }
        $this->success = true;
        return $this;
    }

    public function addError ($errMsg) {
        $this->errors[] = $errMsg;
        return $this;
    }

    public function addErrors (array $errors) {
        $this->errors += $errors;
        return $this;
    }

    // public function setResultSimple ($r) {
    //     if (!is_array($r)) {
    //         $this->result = array($r);
    //     } else {
    //         $this->result = $r;
    //     }
    //     return $this;
    // }

    public function setResult (array $r) {
        $this->result = $r;
        return $this;
    }

    public function isSuccess () {
        return $this->success == true;
    }

    public function isFailed () {
        return $this->success == false;
    }

    public function hasResult () {
        return !$this->isEmptyResult();
    }

    public function isEmptyResult () {
        return is_null($this->result);
    }

    public function getResult () {
        return $this->result;
    }

    public function getResultArray () {
        if (!is_array($this->result)) {
            return array($this->result);
        } else {
            return $this->result
        }
    }

    public function toArray () {
        $r = $this->getResultArray();
        return  $r + array(
            'success' => $this->success,
            'errors' => $this->errors
        );
    }


    // public function getSuccessResultObjectWithParams () {
    //     $data = func_get_args();
    //     $result = array(
    //         'success' => true,
    //         'errors' => array()
    //     );
    //     return $data + $result;
    // }
    // public function getSuccessResultObject (array $data) {
    //     $result = array(
    //         'success' => true,
    //         'errors' => array()
    //     );
    //     return $data + $result;
    // }
    // public function getFailedResultObject ($errors, array $data = array()) {
    //     if (is_string($errors)) {
    //         $errors = array($string);
    //     }
    //     $result = array(
    //         'success' => false,
    //         'errors' => $errors
    //     );
    //     return $data + $result;
    // }

}


?>