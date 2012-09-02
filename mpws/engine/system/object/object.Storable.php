<?php

class objectStorable  {
    
    private $_namespace;
    
    public function setNamespace($namespace) {
        echo '<br>setting NS: ' . $namespace;
        $this->_namespace = strtoupper($namespace);
        $o = &$this->_getDOB();
        //$o::storage($namespace, array());
    }
    
    public function storeGlobalSet($keypath, $obj, $append = true){
        $o = &$this->_getDOB();
        $ctx = array(strtoupper($keypath) => $obj);
        $o::storage("GLOBAL", $ctx, $append);
    }
    
    public function storeGlobalGet(){
        $o = &$this->_getDOB();
        return $o::storage("GLOBAL");
    }

    public function storeSet ($keypath, $obj, $append = true) {
        $o = &$this->_getDOB();
        $ctx = array(strtoupper($keypath) => $obj);
        $o::storage($this->_namespace, $ctx, $append);
    }
    
    public function storeGet () {
        $o = &$this->_getDOB();
        return $o::storage($this->_namespace);
    }
    
    public function &getStorage () {
        $o = &$this->_getDOB();
        return $o::storage('__all__');
    }
    
    // get data object
    
    private function &_getDOB () {
        return libraryStorage::getInstance();
    }
    
}

?>