<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class libraryWebPageModel {

    private $_widgets;
    private $_wobs;
    private $_page;
    private $_templateProviders;
    private static $_instance;
    
    private function __construct() {
        $this->_widgets = array();
        $this->_page = array();
    }
    
    public static function instance () {
        if (empty(self::$_instance))
            self::$_instance = new libraryWebPageModel();
        return self::$_instance;
    }
    
    public function addWebObject ($wob) {
        $this->_wobs[makeKey($wob->getObjectName())] = $wob;
        return $this;
    }

    public function setPageView ($template, $contextName, $data = array()) {
        $this->_page = array (
            'NAME' => basename($template, EXT_TEMPLATE),
            'DATA' => $data,
            'OBJECT' => $contextName,
            'TEMPLATE' => $template,
            'TYPE' => 'PAGE',
            'HTML' => ''
        );
        return $this;
    }
    
    public function addWidget($name, $template, $contextName, $data = array()) {
        $this->_widgets[makeKey($name, true)] = array (
            'NAME' => makeKey($name, true),
            'DATA' => $data,
            'OBJECT' => $contextName,
            'TEMPLATE' => $template,
            'TYPE' => 'WIDGET',
            'HTML' => ''
        );
        return $this;
    }
    
    public function fetchHtmlPage () {
        // get context
        $ctx = contextMPWS::instance();
        // fetch wigets
        debug('Fetching wigets: ' . count($this->_widgets));
        // set root block
        $model = array(
            'DEBUG' => $GLOBALS['MPWS_DEBUG'],
            'CONTEXT' => $ctx,
            'WOB' => $this->_wobs,
            'MODEL' => array(
                'PAGE' => $this->_page,
                'WIDGET' => &$this->_widgets
            )
        );
        // fetch widgets
        foreach ($this->_widgets as $key => $value) {
            debug('fetchHtmlPage: building widget ' . $key);
            $this->_widgets[$key]['HTML'] = $this->fetchTemplate($value, $model);
        }
        //debug($model);
        // fetch page
        debug('Fetching page: ' . $this->_page['TEMPLATE']);
        return $this->fetchTemplate($this->_page, $model);
    }
    
    public function fetchTemplate ($wgt, $model) {
        if (empty($wgt['TEMPLATE']))
            throw new Exception('libraryWebPageModel Empty Template Name Requested');

        // get context
        //$ctx = contextMPWS::instance();
        
        $tp = $this->getTemplateProvider('smarty');
        //echo 'test';
        //var_dump($smarty);
        debug('Rendering template: ' . $wgt['TEMPLATE']);
        
        //if (!empty($model)) {
        $tp->assign($model);
        //}

        // get running object
        $currVar = array(
            'CURRENT' => $wgt
        );
        // assign context
        //$currVar['CURRENT']['CONTEXT'] = $ctx->getContext($wgt['CONTEXT']);
        // set data
        $tp->assign($currVar);
        
        return $tp->fetch($wgt['TEMPLATE']);
    }
    
    private function getTemplateProvider ($name) {
        
        if (isset($this->_templateProviders[$name]))
            return $this->_templateProviders[$name];
        
        $tplProvider = null;
        
        switch ($name) {
            case 'smarty':
            default:
                $tplProvider = new extensionSmarty();//::instance();
                $tplProvider->clearAllCache();
                break;
        }

        $this->_templateProviders[$name] = $tplProvider;
        
        return $tplProvider;
    }
    
    public function dump() {
        return debug($this->_widgets, 'libraryWebPageModel widgets');
    }
    
    
}

?>
