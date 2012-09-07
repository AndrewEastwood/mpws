<?php

class objectBaseContextual extends objectBase {
    
    private $_ctx = false;
    private $_currentCtx = false;
    
    public function __construct ($context, $name, $type, $version) {
        parent::__construct($name, $type, $version);
        $this->_ctx = $context;
    }
    
    protected function objectCustomProperty ($name) {
        if ($name === 'context')
            return $this->_ctx;
        // return default value
        return parent::objectCustomProperty($name);
    } 
    
    public function withContext ($context) {
        $this->_currentCtx = $context;
    }
    
    /* get */
    //public function getContext() { return $this->_ctx; }
    
}

?>