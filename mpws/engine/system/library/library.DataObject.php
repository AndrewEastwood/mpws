<?php

class libraryDataObject {

    private $_data;

    function __construct() {
        $this->_data = array(
            "error" => null
        );
    }

    // data
    public function setError($errorMessage) {
        $this->_data['error'] = $errorMessage;
        return $this;
    }
    public function getError() {
        return $this->_data['error'];
    }

    public function setData($key, $val) {
        $this->_data[$key] = $val;
        return $this;
    }

    public function getData($key = null) {
        if (isset($key))
            return $this->_data[$key];
        return $this->_data;
    }

    public function hasError() {
        return !empty($this->_data['error']);
    }

    // converters
    public function toJSON() { return json_encode($this->getData());}
    public function toNative() { return $this->getData();}
}

?>