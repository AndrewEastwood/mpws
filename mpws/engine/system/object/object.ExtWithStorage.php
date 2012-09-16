<?php

class objectExtWithStorage extends objectExtension {
    
    private $_namespace;

    public function __construct($baseMetaInit) {
        parent::__construct($baseMetaInit);
        debug('objectExtWithStorage', '__construct', true);
        // setup storable namespace
        $this->setNamespace($this->_baseMeta['CLASS']);
    }
    
    public function setNamespace($namespace) {
        //echo '<br>setting NS: ' . $namespace;
        $this->_namespace = strtoupper($namespace);
        $o = &$this->_getDOB();
        //$o::storage($namespace, array());
    }
    
    public function storeGlobalSet($keypath, $obj, $append = true){
        $o = &$this->_getDOB();
        $data = array(strtoupper($keypath) => $obj);
        $o::storage("GLOBAL", $data, $append);
    }
    
    public function storeGlobalGet($keypath = false){
        $o = &$this->_getDOB();
        $_storage = $o::storage("GLOBAL");
        if (!empty($keypath)) {
            $keypath = strtoupper($keypath);
            if (isset($_storage[$keypath]))
                return $_storage[$keypath];
            else
                return null;
        }
        return $_storage;
    }

    public function storeSet ($keypath, $obj, $append = true) {
        $o = &$this->_getDOB();
        $data = array(strtoupper($keypath) => $obj);
        $o::storage($this->_namespace, $data, $append);
    }
    
    public function storeGet ($keypath = false) {
        //echo '<br>Store get: ' . $keypath;
        $o = &$this->_getDOB();
        $_storage = $o::storage($this->_namespace);
        if (!empty($keypath)) {
            //echo 'storeGet';
            $keypath = strtoupper($keypath);
            if (isset($_storage[$keypath]))
                return $_storage[$keypath];
            else
                return null;
        }
        return $_storage;
    }
    
    public function &getStorage () {
        $o = &$this->_getDOB();
        return $o::storage('__all__');
    }
    
    // get data object
    
    private function &_getDOB () {
        return libraryStorage::getInstance();
    }
    
    
    // keypath definitions
    public function keyPathConfiguration($kp) { return '__configuration__' . EQ . strtoupper(trim($kp));}
    public function keyPathTemplate($kp) { return '__template__' . EQ . strtoupper(trim($kp));}
    public function keyPathProperty($kp) { return '__prop__' . EQ . strtoupper(trim($kp));}
    public function keyPathMacro($kp) { return '__macro__' . EQ . strtoupper(trim($kp));}
    public function keyPathComponent($kp) { return '__COM__' . EQ . strtoupper(trim($kp));}
}

?>