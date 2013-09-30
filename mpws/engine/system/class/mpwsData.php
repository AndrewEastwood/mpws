<?php

class mpwsData {
    private $_data;

    function __construct($data) {
        $this->_data = $data;
    }

    function __destruct() {
        unlink($this->_data);
    }
    
    public function setData($val) { $this->_data = $val; }
    
    public function getData() { return $this->_data; }

    public function toJSON() { return libraryUtils::getJSON($this->getData()); }
    public function toDEFAULT() { return $this->getData(); }
    public function toHASH() { return $this->_inner; }
    public function to($outputType) {
        switch ($outputType) {
            case fmtJSON:
                return $this->toJSON();
                return ;
            case fmtDEFAULT:
                return $this->toDEFAULT();
            default:
                throw new Exception("Error Processing Request: mpwsData: at to(" . $outputType . "). Wrong format selected", 1);
        }
    }
}

?>

