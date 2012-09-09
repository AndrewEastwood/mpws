<?php

class objectBaseWebPlugin extends objectBaseWeb /*implements iPlugin*/ {
    
    /* Base Implementation */
    public function __construct ($name, $version = '1.0') {
        parent::__construct($name, OBJECT_T_PLUGIN, $version);
        debug('objectBaseWebPlugin: __construct => ' . $name);
    }

    /* iPlugin */
    /* iPlugin : public api */
    public function run ($command) { 
        debug($command, 'objectBaseWebPlugin: run function:');
        
        switch ($command[makeKey('method')]) {
            case 'main':
                $this->_run_main();
                break;
            case 'layout':
                $this->_run_layout();
                break;
            case 'render':
                $this->_run_render();
                break;
            case 'jsapi':
                $this->_run_jsapi();
                break;
            case 'cross':
                $this->_run_cross();
                break;
        }
        
    }
    /* iPlugin : private structure */
    public function _run_main() {
        debug('objectBaseWebPlugin => _run_main');
        // run common hook on startup
        $this->_displayTriggerOnCommonStart();
        // validate access key with plugin name to run in normal mode
        if (libraryRequest::getPage() === $this->getObjectName())
            $this->_displayTriggerOnActive(); // run on active
        else
            $this->_displayTriggerOnInActive(); // run in background
        // run common hook in end up
        $this->_displayTriggerOnCommonEnd();
    }
    public function _run_layout() {
        debug('objectBaseWebPlugin => _run_layout');
        //echo '***SHOP LAYOUT***';
        //$libView = new libraryView();
        //$model = &$this->getModel();
        //return $libView->getTemplateResult($this->store_storeGet(), $this->templates['LAYOUT']);    
    }
    public function _run_render() {
        debug('objectBaseWebPlugin => _run_render');
        
        
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
    public function _run_jsapi() {
        debug('objectBaseWebPlugin => _run_jsapi');
        /*$model = &$this->getModel();
        $p = libraryRequest::getApiParam();
        if (!$model['USER']['ACTIVE'] || empty($p['token']) || !libraryRequest::getOrValidatePageSecurityToken($p['token']))
            return;*/
    }
    public function _run_cross() {
        debug('objectBaseWebPlugin => _run_cross');
    }

    /* hooks */
    protected function _displayTriggerOnCommonStart () {
        debug('objectBaseWebPlugin => _displayTriggerOnCommonStart');
    }
    protected function _displayTriggerOnActive () {
        debug('objectBaseWebPlugin => _displayTriggerOnActive');
        $_SESSION['MPWS_PLUGIN_ACTIVE'] = $this->getObjectName();
    }
    protected function _displayTriggerOnInActive () {
        debug('objectBaseWebPlugin => _displayTriggerOnInActive');
    }
    protected function _displayTriggerOnCommonEnd () {
        debug('objectBaseWebPlugin => _displayTriggerOnCommonEnd');
    }
    
    /* internal */
    
    
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
    
    
    
}

?>