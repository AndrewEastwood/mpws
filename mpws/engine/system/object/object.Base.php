<?php

class objectBase {
    
   // private $_name;
    //private $_type;
    //private $_version;
    //private $_locale;
    private $_meta;
    private $_extenders;
    
    public function __construct ($name = 'baseObject', $type = '') {
        debug('Base init: "' . $name. '" as "' . $type);
        
        //$this->_name = $name;
        //$this->_type = $type;
        //$this->_version = MPWS_VERSION;
        //$this->_locale = 'en_us';
        $this->_meta = array(
            'NAME' => $name,
            'TYPE' => $type,
            'VERSION' => MPWS_VERSION,
            'LOCALE' => 'en_us',
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
    
    public function __toString() {
        return 'objectBase: ' . $this->getObjectName() . ' t.' . $this->getObjectType();
    }

    /* get */
    public function getObjectName () { return $this->getMeta('NAME'); }
    public function getObjectType () { return $this->getMeta('TYPE'); }
    public function getObjectVersion () { return $this->getMeta('VERSION'); }
    public function getObjectLocale () { return $this->getMeta('LOCALE'); }
    public function getExtender ($alias) {
        if (isset($this->_extenders[$alias]))
            return $this->_extenders[$alias];
        return null;
    }
    public function getMeta($key) {
        if (!isset($key))
            return $this->_meta;
        return $this->_meta[$key];
    }
    /* set */
    public function setObjectLocale ($val) { $this->setMeta('LOCALE', $val); }
    public function setMeta($key, $val) { $this->_meta[$key] = $val; }
    public function setExtender($extendObjectName, $alias, $initJsonArgs = false) {
        
        if (isset($this->_extenders[$alias]))
            throw new Exception('Object ' . $this->getObjectName() .
                ' already has extended object: ' . $extendObjectName .
                ' with accessible key (' . $alias . ')');
        
        // add meta object at the beginning
        $_objArgs = array($this->_meta);
        if (!empty($initJsonArgs))
            foreach ($initJsonArgs as $param)
                $_objArgs[] = $param;
        // init extender class with context object
        debug($_objArgs, 'objectBase: set Extender: ' . $extendObjectName);
        $this->_extenders[$alias] = new $extendObjectName($_objArgs);
        //var_dump($this->_extenders);
    }
    
    /* additional */
    public function updateExtenders ($initJsonArgs = false) {
        foreach ($this->_extenders as $object) {
            // add meta object at the beginning
            $_objArgs = array($this->_meta);
            if (!empty($initJsonArgs))
                foreach ($initJsonArgs as $param)
                    $_objArgs[] = $param;
            
            debug($_objArgs, 'objectBase => updateExtenders');
            $object->updateExtension($_objArgs);
        }
    }
    
    /* methods */
    protected function objectCustomSetup() { /* customize base object */ }
    protected function objectCustomProperty($name) { /* customize base object */ }
}

?>