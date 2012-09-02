<?php

class objectPlugin extends objectBase implements iPlugin  {
    
    /* container */
    private $context;
    /* internal storage */
    //private $templates;
    private $configs;
    //private $meta;
    
    function __construct ($context, $owner = 'plugin') {
        parent::__construct('plugin', $owner);
        
        //echo 'objectPlugin CONSTRUCT';
        $this->context = $context;
        $this->setup($owner);
    }
    
    
    /* setup */
    public function setup ($owner) {
        // setup meta objectStorable
        $this->setMeta('NAME', $owner);
        $this->setMeta('SCRIPT', KEY_PLUGIN.DOT.$owner.EXT_SCRIPT);
        $this->setMeta('PATH', DR . '/web/plugin' . DS . $this->getMeta('NAME'));
        $this->setMeta('DEFAULT', DR . '/web/default/' . MPWS_VERSION);
        // init configuration
        $this->configs = array();
        // init templates
        //$this->templates = array();
        // add extender
        $this->addExtender('objectStorable', 'store');
        
        // setup storable namespace
        $this->store_setNamespace('plugin'.DOT.$owner);
        
        //$this->setNamespace('plugin'.DOT.$owner);
    }
    public function setContext ($context) {
        $this->context = $context;
    }
    
    /* get plugin resources */
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
        $_template = libraryStaticResourceManager::getTemplate('plugin', $this->meta['NAME'], $name /*client locale*/);
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
        $_template = libraryStaticResourceManager::getMacro('plugin', $this->meta['NAME'], $name /*client locale*/);
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
        //$_template = $this->meta['PATH'] . DS . 'property' /*client locale*/ . DS . $templatePath;
        //$_plugin = $this->meta['PLUGIN'] . DS . 'property' /*client locale*/ . DS . $templatePath;
        // select path
        //if (file_exists($_plugin))
        //    $_template = $_plugin;
        $_template = libraryStaticResourceManager::getPropery('plugin', $this->meta['NAME'], $name /*client locale*/);
        // save in cache
        $this->store_storeSet($_storageKey, $_template);
        return $_template;
    }
    
    /* running */
    public function runAction ($name, $context) { }

    /* perform */
    public function main() {
        // run common hook on startup
        $this->displayTriggerOnCommonStart();
        // validate access key with plugin name to run in normal mode
        if (libraryRequest::getPage() === $this->getObjectName())
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
        return $libView->getTemplateResult($this->store_storeGet(), $this->templates['LAYOUT']);    
    }
    public function render() {
        //echo '<br>***SHOP RENDER***<br>';
        
        
        //$storeG = $this->storeGlobalGet();
        
        //$this->storeGlobalSet('html', 'demo demo demo');
        //var_dump($this->getStorage());
        //$this->storeGlobalSet('html', 'test test test');
        //var_dump($this->getStorage());
        //$this->storeGlobalSet('html', 'overwrite test', false);
        //var_dump($this->getStorage());
        
        
        //$model = &$this->getModel(false);
        
        
        /* gat all components as html */
        //var_dump($store);


        $store = $this->store_storeGet();
        
        
        //var_dump($store);
        
        //$libView = new libraryView();
        //var_dump(libraryUtils::filterArrayKeys($store, '/^COM?/'));
        $components = libraryUtils::filterArrayKeys($store, '/^'.$this->store_keyPathComponent('') .'/');
        //var_dump($components);
        if (!empty($components))
            foreach ($components as $key => $com) {
                $this->store_storeSet($key, array('HTML'=> libraryView::getMacroResult($com['DATA'], $com['MACROS'])));
        }
        
        $store = $this->store_storeGet();
        $libView = new libraryView();
        
        
        var_dump($store);


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
        $this->store_storeGlobalSet('html.content', $libView->getTemplateResult($this->store_getStorage(), $store['TEMPLATE.PATH']));
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
            
    /* addons */
    public function addComponent ($name, $data, $macros) {
        $_storageKey = $this->store_keyPathComponent($name);
        $_macros = false;
        if (is_array($macros)) {
            $_macros = array();
            foreach ($macros as $macroName)
                $_macros[$macroName] = $this->getMacro($macroName);
        } elseif (is_string($macros))
            $_macros = $this->getMacro($macros);
        $component = array (
            'NAME' => strtoupper($name),
            'DATA' => $data,
            'MACROS' => $_macros
        );
        $this->store_storeSet($_storageKey, $component);
        return $component;
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