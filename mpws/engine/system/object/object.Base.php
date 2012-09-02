<?php

class objectBase {
    
    private $_type;
    private $_name;
    private $_version;
    private $_meta;
    private $_extenders;
    
    public function __construct ($type, $name, $version = '1.0') {
        $this->_type = $type;
        $this->_name = $name;
        $this->_version = $version;
        $this->_extenders = array();
    }
    public function __call ($name, $args) {
        
        list($alias, $fn) = explode('_', $name, 2);
        
        //var_dump($args);
        //echo '<br> Calling: ' . $name . '<pre>' . print_r($args, true) . '</pre>';
        
        if (!empty($this->_extenders[$alias]) && method_exists($this->_extenders[$alias], $fn))
            return call_user_func_array(array($this->_extenders[$alias], $fn), $args);
        
        //throw new Exception('MPWS Could not find called method "'.$fn.'". Please check if the class has this method.');
    }
    
    /* get */
    public function getObjectType () { return $this->_type; }
    public function getObjectName () { return $this->_name; }
    public function getObjectVersion () { return $this->_version; }
    /* set + get */
    public function getMeta($key) { return $this->_meta[$key]; }
    public function setMeta($key, $val) { $this->_meta[$key] = $val; }
    /* methods */
    public function addExtender($extendObjectName, $alias, $initArgs = false) {
        $this->_extenders[$alias] = new $extendObjectName($initArgs);
        
        
        //var_dump($this->_extenders);
    }
}

?>