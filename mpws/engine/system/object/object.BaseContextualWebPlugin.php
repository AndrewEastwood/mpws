<?php

class objectBaseContextualWebPlugin extends objectBaseContextualWeb implements iPlugin {
    
    /* Base Implementation */
    public function __construct ($context, $name, $version = '1.0') {
        parent::__construct($context, $name, self::$BASE_OBJECT_T_PLUGIN, $version);
    }
    
    final protected function objectCustomSetup() {

        // setup meta objectStorable
        $this->setMeta('PATH_OWN', DR . '/web/plugin' . DS . $this->getMeta('NAME'));
        $this->setMeta('PATH_DEF', DR . '/web/default/' . MPWS_VERSION);
        // init configuration
        //$this->configs = array();
        // init templates
        //$this->templates = array();
        // add extender
        $this->setExtender('objectExtWithStorage', '_ex_store');
        $this->setExtender('objectExtWithResource', '_ex_resource');
        
        // setup storable namespace
        $this->_ex_store__setNamespace($this->getMeta('CLASS'));
        
        //$this->setNamespace('plugin'.DOT.$owner);
    }
    
    /* iPlugin Implementation */
    
    /* running */
    public function runAction ($actionName) { }

    /* perform */
    public function main() {
        echo '<br>***objectBaseContextualWebPlugin MAIN***<br>';
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
        //$libView = new libraryView();
        //$model = &$this->getModel();
        //return $libView->getTemplateResult($this->store_storeGet(), $this->templates['LAYOUT']);    
    }
    public function render() {
        echo '<br>***objectBaseContextualWebPlugin RENDER***<br>';
        
        
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


        /*$store = $this->store_storeGet();
        
        
        //var_dump($store);
        
        //$libView = new libraryView();
        //var_dump(libraryUtils::filterArrayKeys($store, '/^COM?/'));
        /*$components = libraryUtils::filterArrayKeys($store, '/^'.$this->store_keyPathComponent('') .'/');
        //var_dump($components);
        if (!empty($components))
            foreach ($components as $key => $com) {
                $this->store_storeSet($key, array('HTML'=> libraryView::getMacroResult($com['DATA'], $com['MACROS'])));
        }
        
        $store = $this->store_storeGet();
        $libView = new libraryView();
        
        
        var_dump($store);
*/

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
        //$this->store_storeGlobalSet('html.content', $libView->getTemplateResult($this->store_getStorage(), $store['TEMPLATE.PATH']));
        //$storeG['HTML.CONTENT'] .= $libView->getTemplateResult($this->getStorage(), $store['TEMPLATE.PATH']);
        //$storeG = $this->storeGlobalGet();
    }
    public function api() {
        /*$model = &$this->getModel();
        $p = libraryRequest::getApiParam();
        if (!$model['USER']['ACTIVE'] || empty($p['token']) || !libraryRequest::getOrValidatePageSecurityToken($p['token']))
            return;*/
    }
    public function cross() { }
    public function dump () {
        /*$dump = '<h2>Plugin Dump:</h2>';
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
        return '<pre>' . $dump . '</pre>';*/
    }
            
    /* addons */
    public function addComponent ($name, $data, $macros) {
        /*$_storageKey = $this->store_keyPathComponent($name);
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
        return $component;*/
    }
    
    /* hooks */
    protected function displayTriggerOnCommonStart () {}
    protected function displayTriggerOnActive () {
        $_SESSION['MPWS_PLUGIN_ACTIVE'] = $this->getObjectName();
    }
    protected function displayTriggerOnInActive () {}
    protected function displayTriggerOnCommonEnd () {}
    
    /* internal */
    
    
}

?>