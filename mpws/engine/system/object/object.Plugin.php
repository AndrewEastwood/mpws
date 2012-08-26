<?php

class objectPlugin implements iPlugin {
    
    /* container */
    private $toolbox;
    /* internal storage */
    private $templates;
    private $configs;
    private $meta;
    private $version;
    
    function __construct ($toolbox, $owner = 'plugin') {
        //echo 'objectPlugin CONSTRUCT';
        $this->toolbox = $toolbox;
        $this->version = 2;
        $this->setup($owner);
    }
    
    
    /* setup */
    public function setup ($owner) {
        // setup meta
        $this->meta['NAME'] = $owner;
        $this->meta['SCRIPT'] = KEY_PLUGIN.DOT.$owner.EXT_SCRIPT;
        $this->meta['PATH'] = DR . '/web/plugin' . DS . $this->meta['NAME'];
        $this->meta['DEFAULT'] = DR . '/web/default/' . MPWS_VERSION;
        // init configuration
        $this->configs = array();
        // init templates
        $this->templates = array();
    }
    
    /* initail */
    public function getConfiguration ($name, $key = null) {
        //echo 'getConfiguration: ' . $name . ' ===> ' . $key;
        $name = strtoupper($name);
        if (!empty($key))
            $key = strtoupper($key);
        // config
        $_requestedConfig = false;
        // check in storage
        if (!empty($this->configs[$name]))
            $_requestedConfig = $this->configs[$name];
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
            $this->configs[$name] = $_requestedConfig[$name];
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
    public function getTemplate ($name) { }
    public function getName () { return $this->meta['NAME']; }
    public function getVersion () { return $this->version; }
    
    /* running */
    public function runAction ($name, $context) { }

    /* perform */
    public function main() {
        // run common hook on startup
        $this->displayTriggerOnCommonStart();
        // validate access key with plugin name to run in normal mode
        if (libraryRequest::getPage() === strtolower($this->meta['key']))
            $this->displayTriggerOnActive(); // run on active
        else
            $this->displayTriggerOnInActive(); // run in background
        // run common hook in end up
        $this->displayTriggerOnCommonEnd();
    }
    public function layout() { }
    public function render() { }
    public function api() { }
    public function cross() { }
    public function dump () {
        $dump = '<h2>Plugin Dump:</h2>';
        $dump .= '<br>Meta info:';
        $dump .= '<ul>';
        foreach ($this->meta as $key => $val)
            $dump .= '<li>' . $key . ': ' . $val . '</li>';
        $dump .= '</ul>';
        $dump .= '<br>Configuration:';
        foreach ($this->configs as $key => $val)
            $dump .= '<li>' . $key . ': ' . $val . '</li>';
        $dump .= '</ul>';
        $dump .= '<br>Templates:';
        foreach ($this->templates as $key => $val)
            $dump .= '<li>' . $key . ': ' . $val . '</li>';
        $dump .= '</ul>';
        return '<pre>' . $dump . '</pre>';
    }
            
    /* hooks */
    protected function displayTriggerOnCommonStart () {}
    protected function displayTriggerOnActive () {}
    protected function displayTriggerOnInActive () {}
    protected function displayTriggerOnCommonEnd () {}
    
    /* internal */
}

?>