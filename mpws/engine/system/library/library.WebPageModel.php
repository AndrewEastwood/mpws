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
    private $_staticData;
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
        $this->_messages[$realm][] = $messageKey;
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
    
    public function getInfo ($key) {
        return $this->_info[makeKey($key)];
    }
    
    public function allowToProcessPages () {
        /* depends on user auth */
        $_user = $this->_info[makeKey('USER')];
        $_allow = false;
        if (isset($_user[makeKey('ACTIVE')]))
            $_allow = $_user[makeKey('ACTIVE')];
        return $_allow;
    }


    public function removeWebObject ($name) {
        delete($this->_wobs[makeKey($name)]);
        return $this;
    }
    public function addWebObject (/* single wob or wobs */) {
        $wob = getArguments(func_get_args());
        if (func_num_args() == 1)
            $wob = func_get_arg(0);
        if (is_object($wob)) {
            $_wobKey = makeKey($wob->getObjectName());
            if (!isset($this->_wobs[$_wobKey]))
                $this->_wobs[$_wobKey] = $wob;
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
            'OWNER_TYPE' => OBJECT_T_CUSTOMER,
            'HTML' => ''
        );
        return $this;
    }
    
    public function addWidget($owner, $name, $template, $data = array()) {
        // check for extended resource
        $tplResMode = explode(DOG, $name);
        //echo '<br>================================<br>';
        //echo 'ADDING WGT<br>RAW NAME: ' . $name;
        $name = $tplResMode[0];
        $resource = $tplResMode[0];
        // name has PARENT@WIDGETNAME
        if (!empty($tplResMode[1])) {
            $name = $tplResMode[1];
            $resourceMap[] = $tplResMode[0];
            $resourceMap[] = $tplResMode[0] . $tplResMode[1];
            $resource = '[' . join(',', $resourceMap) . ']';
        }
        //echo '<br>OWNER: ' . $owner;
        //echo '<br>WOB NAME: ' . $owner->getObjectName();
        //echo '<br>NAME: ' . $name;
        //echo '<br>RESOURCE: ' . $resource;
        //echo '<br>================================<br>';
        $this->_widgets[makeKey($name, true)] = array (
            'NAME' => $name,
            'RESOURCE' => $resource,
            'WOB_NAME' => $owner->getObjectName(),
            'DATA' => $data,
            'TEMPLATE' => $template,
            'TYPE' => 'WIDGET',
            'OWNER_TYPE' => $owner->getObjectType(),
            'HTML' => ''
        );
        //echo '<br> addWidget => {name:"'.$name.'", resource:"'.$resource.'"}'; 
        // add wob
        if ($owner->isObjectTypeEquals(OBJECT_T_PLUGIN))
            $this->addWebObject($owner);
        return $this;
    }
    
    public function addStaticData($data) {
        $this->_staticData .= $data;
    }
    
    public function fetchStaticData () {
        return $this->_staticData;
    }
    public function fetchHtmlPage () {
        //echo mktime() . '<br>';
        // get context
        $ctx = contextMPWS::instance();
        // fetch wigets
        debug('Fetching wigets: ' . count($this->_widgets));

        // set root block
        $model = array(
            'OBJECT' => array(
                'CONTEXT' => $ctx,
                'SITE' => $this->_site,
                'WOB' => $this->_wobs,
            ),
            'MODEL' => array(
                'MESSAGE' => $this->_messages,
                'PAGE' => $this->_page,
                'WIDGET' => &$this->_widgets,
                'CUSTOM' => $this->_customs
            )
        );
        
        //var_dump($this->_widgets);
        
        // append info
        if (!empty($this->_info))
            $model['INFO'] = $this->_info;

        // fetch widgets
        //echo '<p>fetching widgets >>>>></p>';
        foreach ($this->_widgets as $key => $value) {
            debug('fetchHtmlPage: building widget ' . $key);
            //echo print_r($value, true);
            //echo '<br>keeeeeeeeeeeeeeeeeey: ' . $key;
            $this->_widgets[$key]['HTML'] = $this->fetchTemplate($value, $model);
        }
        //var_dump($this->_info);
        //echo '<br>++++++++++++++++ END WIDGETS +++++++++++++++++++<br>';
        //debug($model);
        // fetch page
        debug('Fetching page: ' . $this->_page['TEMPLATE']);
        //echo 'Fetching page: ' . $this->_page['TEMPLATE'];
        $html = $this->fetchTemplate($this->_page, $model);
        //echo mktime() . '<br>';
        //echo '<br>++++++++++++++++++ END PAGE +++++++++++++++++<br>';
        return $html;
        //return libraryMinifyHTML::minify($html);
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

        
        //echo '<pre>';
        //var_dump($this->_wobs);
        //var_dump($wgt);
        //echo '</pre>---------------------------------------------------------';

        // set current object
        
        
        $current = array(
            'OBJECT' => (($wgt['TYPE'] == 'PAGE' || $wgt['OWNER_TYPE'] == 'customer')?$this->_site:$this->_wobs[makeKey($wgt['WOB_NAME'])]),
            'SOURCE' => $wgt
        );
        
        /*if ($wgt['OWNER_TYPE'] == 'customer') {
            var_dump($current);
            var_dump($wgt);
        }*/

        $tp->assign('CURRENT', $current);

        
        //echo '<br><br>LOL <pre>';
        //var_dump($this->_wobs);
        //var_dump($current);
        //echo print_r($wgt);
        //echo '</pre>---------------------------------------------------------';
        
        //}
        // get running object
        /*$currVar = array(
            'CURRENT' => $wgt
        );*/
        // assign context
        //$currVar['CURRENT']['CONTEXT'] = $ctx->getContext($wgt['CONTEXT']);
        // set data
        //$tp->assign($currVar);
        
        //echo '<br>RENDERING: ' . $wgt['TEMPLATE'];

        $html =  $tp->fetch($wgt['TEMPLATE']);
        
        
        //echo $html;
        
        return $html;
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

        // common info
        // page token
        //echo 'WebPageModel';
        $info[makeKey('TOKEN')] = libraryRequest::getOrValidatePageSecurityToken();
        $info[makeKey('DEBUG')] = libraryUtils::getDebugInfo();
        $info[makeKey('USER')] = libraryUtils::getDebugInfo();
        $info[makeKey('CUSTOMER')] = MPWS_CUSTOMER;

        // GET parameters

        // page
        $info['GET'][makeKey('PAGE')] = libraryRequest::getPage();
        // display
        $info['GET'][makeKey('DISPLAY')] = libraryRequest::getDisplay();
        // action
        $info['GET'][makeKey('ACTION')] = libraryRequest::getAction();
        // plugin
        $info['GET'][makeKey('PLUGIN')] = libraryRequest::getPlugin();

        // the same things but for post

        // page
        $info['POST'][makeKey('PAGE')] = libraryRequest::getPostPage();
        // display
        $info['POST'][makeKey('DISPLAY')] = libraryRequest::getPostDisplay();
        // action
        $info['POST'][makeKey('ACTION')] = libraryRequest::getPostAction();
        // plugin
        $info['POST'][makeKey('PLUGIN')] = libraryRequest::getPostPlugin();


        $info['URL'][makeKey('STATIC_INTERNAL')] = '/static/i/';
        $info['URL'][makeKey('STATIC_PUBLIC')] = '/static/p/';


        $info['URL'][makeKey('STATIC_INTERNAL_C')] = '/static/i/customer/'.MPWS_CUSTOMER.'/';
        $info['URL'][makeKey('STATIC_INTERNAL_P')] = '/static/i/plugin/'.libraryRequest::getPlugin().'/';
        $info['URL'][makeKey('STATIC_PUBLIC_C')] = '/static/p/customer/'.MPWS_CUSTOMER.'/';
        $info['URL'][makeKey('STATIC_PUBLIC_P')] = '/static/p/plugin/'.libraryRequest::getPlugin().'/';

        return $info;
    }

    public function dump() {
        return debug($this->_widgets, 'libraryWebPageModel widgets');
    }
    
    
}

?>
