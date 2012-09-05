<?php

class objectPlugin extends objectBaseWeb implements iPlugin  {
    
    /* container */
    //private $_ctx;
    /* internal storage */
    //private $templates;
    private $configs;
    //private $meta;
    
    public function __construct ($context, $owner = 'plugin') {
        parent::__construct($context, 'plugin', $owner);
        
        //echo 'objectPlugin CONSTRUCT';
        //$this->_ctx = $context;
        $this->setup($owner);
    }
    
    
    /* setup */
    public function setup ($owner) {
    }
    
    /* get plugin resources *
    public function getConfiguration ($name, $key = null) {
        //echo 'getConfiguration: ' . $name . ' ===> ' . $key;
        $name = strtoupper($name);
        
        $_storageKey = $this->store_keyPathConfiguration($name);
        
        if (!empty($key))
            $key = strtoupper($key);
        // config
        $_requestedConfig = false;
        // check in storage
        if ($this->store_storeGet($_storageKey))
            $_requestedConfig = $this->store_storeGet($_storageKey);
        else {
            $configFileName = strtolower($name);
            // startup items
            $default = array();
            $plugin = array();
            // we have to load config file
            $_plugin = $this->meta['PATH'] . DS . 'config'. DS . $configFileName .  EXT_SCRIPT;
            $_default = $this->meta['DEFAULT'] . DS . 'config' . DS . $configFileName .  EXT_SCRIPT;
            //echo '<br>reading files:<br>';
            //echo '<br>'.$_plugin;
            //echo '<br>'.$_default;
            // eval configs
            if (file_exists($_plugin))
                eval(file_get_contents($_plugin));
            if (file_exists($_default))
                eval(file_get_contents($_default));
            // merge configs
            $_requestedConfig = array_merge_recursive($default, $plugin);
            //var_dump($_requestedConfig);
            // store config 
            $this->store_storeSet($_storageKey, $_requestedConfig[$name]);
        }
        // get value by key
        if (isset($key)) {
            if (isset($_requestedConfig[$name][$key]))
                return $_requestedConfig[$name][$key];
            else
                return false;
        }
        // get all section
        return $_requestedConfig[$name];
    }
    public function getTemplate ($name) {
        //echo 'getting template: ' . $name;
        $_storageKey = $this->store_keyPathTemplate($name);
        //libraryStorage::cache('demo','<br><br><br>test gfgdfg');
        //$templatePath = str_replace(DOT, DS, $name) . '.html';
        // check in cache
        if ($this->store_storeGet($_storageKey))
            return $this->store_storeGet($_storageKey);
        // get paths
        //$_template = $this->meta['PATH'] . DS . 'templates' . DS . $templatePath;
        //$_plugin = $this->meta['PLUGIN'] . DS . 'templates' . DS . $templatePath;
        // select path
        //if (file_exists($_plugin))
        //    $_template = $_plugin;
        $_template = libraryStaticResourceManager::getTemplate('plugin', $this->meta['NAME'], $name /*client locale* /);
        // save in cache
        $this->store_storeSet($_storageKey, $_template);
        return $_template;
    }
    public function getMacro ($name) {
        //echo 'getting template: ' . $name;
        $_storageKey = $this->store_keyPathMacro($name);
        //libraryStorage::cache('demo','<br><br><br>test gfgdfg');
        //$templatePath = str_replace(DOT, DS, $name) . '.html';
        // check in cache
        if ($this->store_storeGet($_storageKey))
            return $this->store_storeGet($_storageKey);
        // select path
        //$_template = $this->meta['DEFAULT'] . DS . 'templates' . DS . 'macro' . DS . $templatePath;
        //$_plugin = $this->meta['PATH'] . DS . 'templates' . DS . 'macro' . DS . $templatePath;
        // select path
        //if (file_exists($_plugin))
        //    $_template = $_plugin;
        // save in cache
        $_template = libraryStaticResourceManager::getMacro('plugin', $this->meta['NAME'], $name /*client locale* /);
        $this->store_storeSet($_storageKey, $_template);
        return $_template;
    }
    public function getProperty ($name) {
        $_storageKey = $this->store_keyPathProperty($name);
        //$templatePath = str_replace(DOT, DS, $name) . '.property';
        // check in cache
        if ($this->store_storeGet($_storageKey))
            return $this->store_storeGet($_storageKey);
        // get paths
        //$_template = $this->meta['PATH'] . DS . 'property' /*client locale* / . DS . $templatePath;
        //$_plugin = $this->meta['PLUGIN'] . DS . 'property' /*client locale* / . DS . $templatePath;
        // select path
        //if (file_exists($_plugin))
        //    $_template = $_plugin;
        $_template = libraryStaticResourceManager::getPropery('plugin', $this->meta['NAME'], $name /*client locale* /);
        // save in cache
        $this->store_storeSet($_storageKey, $_template);
        return $_template;
    }
    */

}

?>