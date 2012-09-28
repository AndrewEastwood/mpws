<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class libraryWebPageModel {

    private $_info;
    private $_site;
    private $_widgets;
    private $_wobs;
    private $_page;
    private $_messages;
    private $_customs;
    private $_templateProviders;
    private static $_instance;
    
    private function __construct() {
        $this->_widgets = array();
        $this->_page = array();
        $this->_info = $this->getStandartInfo();
    }
    
    public static function instance () {
        if (empty(self::$_instance))
            self::$_instance = new libraryWebPageModel();
        return self::$_instance;
    }
    
    public function setCustom ($key, $value) {
        $this->_customs[$key] = $value;
    }


    public function addMessage ($messageKey, $realm = 'COMMON') {
        $this->_messages[$realm] = $messageKey;
    }
    
    public function setInfo ($key, $info = false) {
        if (is_array($key) && empty($info)) {
            $this->_info = array_merge_recursive($this->_info, $info);
            return true;
        }
        if (is_string($key) && isset($info)) {
            $this->_info[makeKey($key)] = $info;
            return true;
        }
        throw new Exception('libraryWebPageModel => setInfo: ERROR is coccured while setting new info value ('.$info.') with key ' . $key);
    }


    public function removeWebObject ($name) {
        delete($this->_wobs[makeKey($name)]);
        return $this;
    }
    public function addWebObject (/* single wob or wobs */) {
        $wob = getArguments(func_get_args());
        if (is_object($wob)) {
            $this->_wobs[makeKey($wob->getObjectName())] = $wob;
        } elseif (is_array($wob)) {
            foreach($wob as $obj)
                $this->addWebObject($obj);
        }
        return $this;
    }
    
    public function setSiteObject($siteObject) {
        $this->_site = $siteObject;
        return $this;
    }

    public function setPageView ($template, $data = array()) {
        $this->_page = array (
            'NAME' => basename($template, EXT_TEMPLATE),
            'DATA' => $data,
            'TEMPLATE' => $template,
            'TYPE' => 'PAGE',
            'HTML' => ''
        );
        return $this;
    }
    
    public function addWidget($name, $template, $data = array()) {
        $this->_widgets[makeKey($name, true)] = array (
            'NAME' => makeKey($name, true),
            'DATA' => $data,
            'TEMPLATE' => $template,
            'TYPE' => 'WIDGET',
            'HTML' => ''
        );
        return $this;
    }
    
    public function fetchHtmlPage () {
        //echo mktime() . '<br>';
        // get context
        $ctx = contextMPWS::instance();
        // fetch wigets
        debug('Fetching wigets: ' . count($this->_widgets));
        // set root block
        $model = array(
            'DEBUG' => $GLOBALS['MPWS_DEBUG'],
            'CONTEXT' => $ctx,
            'SITE' => $this->_site,
            'WOB' => $this->_wobs,
            'MODEL' => array(
                'MESSAGE' => $this->_messages,
                'PAGE' => $this->_page,
                'WIDGET' => &$this->_widgets,
                'CUSTOM' => $this->_customs
            )
        );
        // append info
        if (!empty($this->_info))
            $model['INFO'] = $this->_info;

        // fetch widgets
        foreach ($this->_widgets as $key => $value) {
            debug('fetchHtmlPage: building widget ' . $key);
            $this->_widgets[$key]['HTML'] = $this->fetchTemplate($value, $model);
        }
        //debug($model);
        // fetch page
        debug('Fetching page: ' . $this->_page['TEMPLATE']);
        $ret = $this->fetchTemplate($this->_page, $model);
        //echo mktime() . '<br>';
        return $ret;
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
        /*$currVar = array(
            'CURRENT' => $wgt
        );*/
        // assign context
        //$currVar['CURRENT']['CONTEXT'] = $ctx->getContext($wgt['CONTEXT']);
        // set data
        //$tp->assign($currVar);
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
    
    public function getStandartInfo () {
        $info = array();
        // page token
        $info[makeKey('TOKEN')] = libraryRequest::getOrValidatePageSecurityToken();
        // page
        $info[makeKey('PAGE')] = libraryRequest::getPage();
        // display
        $info[makeKey('DISPLAY')] = libraryRequest::getDisplay();
        // action
        $info[makeKey('ACTION')] = libraryRequest::getAction();
        return $info;
    }

    public function dump() {
        return debug($this->_widgets, 'libraryWebPageModel widgets');
    }
    
    
}

?>
