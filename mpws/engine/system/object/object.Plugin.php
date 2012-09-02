<?php

class objectPlugin extends objectStorable implements iPlugin  {
    
    /* container */
    private $context;
    /* internal storage */
    private $templates;
    private $configs;
    private $meta;
    private $version;
    
    function __construct ($context, $owner = 'plugin') {
        //echo 'objectPlugin CONSTRUCT';
        $this->context = $context;
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
        // setup storable namespace
        $this->setNamespace('plugin'.DOT.$owner);
    }
    public function setContext ($context) {
        $this->context = $context;
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
    public function getTemplate ($name) {
        echo 'getting template: ' . $name;
        
        
        //libraryStorage::cache('demo','<br><br><br>test gfgdfg');
        
        
        $templatePath = str_replace(DOT, DS, $name) . '.html';
        
        // check in cache
        if (!empty($this->templates[$name]))
            return $this->templates[$name];
        
        // load template
        $_template = $this->meta['PATH'] . DS . 'templates' . DS . $templatePath;
        $_plugin = $this->meta['PLUGIN'] . DS . 'templates' . DS . $templatePath;
        
        if (file_exists($_plugin))
            $_template = $_plugin;
        
        // save in cache
        $this->templates[$name] = $_template;
        
        //echo $_template;
        
        return $_template;
    }
    public function getName () { return $this->meta['NAME']; }
    public function getVersion () { return $this->version; }
    
    /* running */
    public function runAction ($name, $context) { }

    /* perform */
    public function main() {
        // run common hook on startup
        $this->displayTriggerOnCommonStart();
        // validate access key with plugin name to run in normal mode
        if (libraryRequest::getPage() === $this->getName())
            $this->displayTriggerOnActive(); // run on active
        else
            $this->displayTriggerOnInActive(); // run in background
        // run common hook in end up
        $this->displayTriggerOnCommonEnd();
    }
    public function layout() {
        //echo '***SHOP LAYOUT***';
        $libView = new libraryView();
        //$model = &$this->getModel();
        return $libView->getTemplateResult($this->storeGet(), $this->templates['LAYOUT']);    
    }
    public function render() {
        echo '<br>***SHOP RENDER***<br>';
        
        
        //$storeG = $this->storeGlobalGet();
        $store = $this->storeGet();
        
        //$this->storeGlobalSet('html', 'demo demo demo');
        //var_dump($this->getStorage());
        //$this->storeGlobalSet('html', 'test test test');
        //var_dump($this->getStorage());
        //$this->storeGlobalSet('html', 'overwrite test', false);
        //var_dump($this->getStorage());
        
        
        //$model = &$this->getModel(false);
        $libView = new libraryView();
        
        
        /* gat all components as html * /
        if ($model['USER']['ACTIVE'] && !empty($model['PLUGINS']['context']['COM'])) {
            foreach ($model['PLUGINS']['WRITER']['COM'] as $key => $component)
                $model['html']['writer']['com'][strtolower($key)] = $libView->getTemplateResult($model, $model['PLUGINS']['WRITER']['COM'][$key]['template']);
            $model['html']['menu'] .= $model['html']['writer']['com']['menu'];
        }*/
        
        //var_dump($model);
        //var_dump($storeG);
        //var_dump($store);
        
        /* set html data */
        $this->storeGlobalSet('html.content', $libView->getTemplateResult($this->getStorage(), $store['TEMPLATE.PATH']));
        //$storeG['HTML.CONTENT'] .= $libView->getTemplateResult($this->getStorage(), $store['TEMPLATE.PATH']);
        //$storeG = $this->storeGlobalGet();
    }
    public function api() {
        $model = &$this->getModel();
        $p = libraryRequest::getApiParam();
        if (!$model['USER']['ACTIVE'] || empty($p['token']) || !libraryRequest::getOrValidatePageSecurityToken($p['token']))
            return;
    }
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
            $dump .= '<li>' . $key . ': <pre>' . print_r($val, true) . '</pre></li>';
        $dump .= '</ul>';
        $dump .= '<br>Templates:';
        foreach ($this->templates as $key => $val)
            $dump .= '<li>' . $key . ': ' . $val . '</li>';
        $dump .= '</ul>';
        return '<pre>' . $dump . '</pre>';
    }
            
    /* hooks */
    protected function displayTriggerOnCommonStart () {}
    protected function displayTriggerOnActive () {
        $_SESSION['MPWS_PLUGIN_ACTIVE'] = 'WRITER';
    }
    protected function displayTriggerOnInActive () {}
    protected function displayTriggerOnCommonEnd () {}
    
    /* internal */
}

?>