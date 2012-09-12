<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class libraryWebPageModel {

    private $_widgets;
    private $_page;
    private $_templateProviders;
    private static $_instance;
    
    private function __construct() { }
    
    public static function instance () {
        if (empty(self::$_instance))
            self::$_instance = new libraryWebPageModel();
        return self::$_instance;
    }

    public function setPageView ($template, $contextName, $data = array()) {
        $this->_page = array (
            'DATA' => $data,
            'CONTEXT' => $contextName,
            'TEMPLATE' => $template,
            'HTML' => ''
        );
    }
    
    public function addWidget($name, $template, $contextName, $data = array()) {
        $this->_widgets[makeKey($name, true)] = array (
            'NAME' => makeKey($name, true),
            'DATA' => $data,
            'CONTEXT' => $contextName,
            'TEMPLATE' => $template,
            'HTML' => ''
        );
    }
    
    public function fetchHtmlPage () {
        // get context
        $ctx = contextMPWS::instance();
        // fetch wigets
        debug('Fetching wigets: ' . count($this->_widgets));
        foreach ($this->_widgets as $key => $value) {
            $this->_widgets[$key]['HTML'] = $this->fetchTemplate($value);
        }
        // set root block
        $model = array(
            'debug' => $GLOBALS['MPWS_DEBUG'],
            'model' => array(
                'context' => $ctx,
                'widgets' => $this->_widgets
            )
        );
        
        debug($model);
        
        // fetch page
        debug('Fetching page: ' . $this->_page['TEMPLATE']);
        return $this->fetchTemplate($this->_page, $model);
    }
    
    public function fetchTemplate ($wgt, $model = false) {
        $tp = $this->getTemplateProvider('smarty');
        //echo 'test';
        //var_dump($smarty);
        if (!empty($model))
            $tp->assign($model);
        debug('Rendering template: ' . $wgt['TEMPLATE']);
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
