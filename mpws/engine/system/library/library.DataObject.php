<?php

class libraryDataObject {

    private $_data;

    function __construct() {
        $this->_data = array();
    }

    // data
    public function setError($errorMessage) {
        $this->_data['error'] = $errorMessage;
        return $this;
    }
    public function getError() {
        return isset($this->_data['error']) ? $this->_data['error'] : false;
    }

    public function setData($key, $val) {
        $this->_data[$key] = $val;
        return $this;
    }

    public function overwriteData($data) {
        if (is_array($data)) {
            $this->_data = new ArrayObject($data);
            return true;
        }
        return false;
    }

    public function getData($key = null) {
        if (isset($key))
            return isset($this->_data[$key]) ? $this->_data[$key] : null;
        return $this->_data;
    }

    public function hasError() {
        return !empty($this->_data['error']);
    }

    public function hasData() {
        return count($this->_data) > 1;
    }

    // converters
    public function toJSON($key = null) {
        return json_encode($this->getData($key));
    }
    public function toNative() { return $this->getData();}
}

?>