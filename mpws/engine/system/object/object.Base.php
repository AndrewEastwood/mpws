<?php

class objectBase {
    
    private $_name;
    private $_type;
    private $_version;
    private $_meta;
    private $_extenders;
    
    public function __construct ($name = 'baseObject', $type = '', $version = '1.0') {
        echo '<br> Base init: "' . $name. '" as "' . $type . '" v.' . $version . '<br>';
        
        $this->_name = $name;
        $this->_type = $type;
        $this->_version = $version;
        $this->_meta = array(
            'NAME' => $name,
            'CLASS' => strtolower($type.DOT.$name),
            'SCRIPT' => strtolower($type.DOT.$name.EXT_SCRIPT)
        );
        $this->_extenders = array();
        
        /* custom setup */
        $this->objectCustomSetup();
    }
    public function __call ($name, $args) {
        
        list($alias, $fn) = explode('__', $name, 2);
        
        //var_dump($args);
        //echo '<br> Calling: ' . $name . '<pre>' . print_r($args, true) . '</pre>';
        
        if (!empty($this->_extenders[$alias]) && method_exists($this->_extenders[$alias], $fn))
            return call_user_func_array(array($this->_extenders[$alias], $fn), $args);
        
        throw new Exception('MPWS Could not find called method "'.$name.'". Please check if the class has this method.');
    }
    public function __get($name) {
        return $this->objectCustomProperty($name);
    }

    /* get */
    public function getObjectType () { return $this->_type; }
    public function getObjectName () { return $this->_name; }
    public function getObjectVersion () { return $this->_version; }
    public function getExtender ($alias) {
        if (isset($this->_extenders[$alias]))
            return $this->_extenders[$alias];
        return null;
    }
    /* set */
    public function getMeta($key) { return $this->_meta[$key]; }
    public function setMeta($key, $val) { $this->_meta[$key] = $val; }
    public function setExtender($extendObjectName, $alias, $initJsonArgs = false) {
        
        if (isset($this->_extenders[$alias]))
            throw new Exception('Object ' . $this->getObjectName() .
                ' already has extended object: ' . $extendObjectName .
                ' with accessible key (' . $alias . ')');
        
        // add context object at the beginning
        $_objArgs = array($this->_ctx);
        if (!empty($initJsonArgs))
        foreach ($initJsonArgs as $param)
            $_objArgs[] = $param;
        // init extender class with context object
        $this->_extenders[$alias] = new $extendObjectName($_objArgs);
        //var_dump($this->_extenders);
    }
    
    /* methods */
    protected function objectCustomSetup() { /* customize base object */ }
    protected function objectCustomProperty($name) { /* customize base object */ }
}

?>