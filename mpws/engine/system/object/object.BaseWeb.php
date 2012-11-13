<?php

/* Web Object Has The Following Features:
 *   configuration
 *   templates
 *   properties
 *   setup scripts
 */

class objectBaseWeb extends objectBase {
    
    /* custom setup */
    protected function objectCustomSetup() {
        // setup locale
        //$this->setObjectLocale($this->objectConfiguration['DISPLAY']['LOCALE']);

        // setup meta object
        // non "en_us" locale attribute must be changed at this point
        $this->setMeta('PATH_WEB', DR . 'web/customer/' . MPWS_CUSTOMER);
        $this->setMeta('PATH_DEF', DR . 'web/default/' . $this->getObjectVersion());
        // do not use identical paths for WEB and OWN
        //if ($this->getObjectType() !== OBJECT_T_CUSTOMER)
        $this->setMeta('PATH_OWN', DR . 'web/' . $this->getObjectType() . DS . $this->getMeta('NAME'));
        
        // use storage to store
        // templates, config and properties
        $this->setExtender('objectExtWithStorage', '_ex_store');
        $this->setExtender('objectExtWithResource', '_ex_resource');
        $this->setExtender('objectExtWithConfiguration', '_ex_config');

        //var_dump($this->getMeta());
        $locale = libraryRequest::getLocale($this->objectConfiguration_customer_locale);
        if ($this->getObjectLocale() != $locale) {
            $this->setObjectLocale($locale);
            $this->updateExtenders();
        }

        // different versions
        // all plugins must use version that customer uses
        if ($this->getObjectVersion() != $this->objectConfiguration_customer_version) {
            $this->setMeta('PATH_DEF', DR . '/web/default/' . $this->objectConfiguration_customer_version);
            $this->setMeta('VERSION', $this->objectConfiguration_customer_version);
            $this->updateExtenders();
        }
        
       
    }
    protected function objectCustomProperty($name) {
        if (startsWith($name, 'objectConfiguration'))
            return $this->getConfiguration(str_replace(array('objectConfiguration_', '_'), array('', '.'), $name));
        if (startsWith($name, 'objectTemplatePath'))
            return $this->getTemplatePath(str_replace(array('objectTemplatePath_', '_'), array('', '.'), $name));
        if (startsWith($name, 'objectProperty'))
            return $this->getProperty(str_replace(array('objectProperty_', '_'), array('', '.'), $name));

        return parent::objectCustomProperty($name);
    }

    /* resource access */
    protected function getConfiguration ($metapath) {
        debug('objectBaseWeb: getConfiguration: ' . $metapath);
        $_kp = $this->_ex_store__keyPathConfiguration($metapath);
        $_cache = $this->_ex_store__storeGet($_kp);
        if (!empty($_cache)) {
            debug('objectBaseWeb: getConfiguration: Get Configuration From Cache: ' . $metapath);
            return $_cache;
        }
        $resValue = $this->_ex_config__getConfigurationValue($metapath);
        debug('objectBaseWeb: getConfiguration: Downloaded Configuration: ' . $metapath);
        $this->_ex_store__storeSet($_kp, $resValue);
        return $resValue;
    }
    protected function getTemplatePath ($metapath) {
        debug('objectBaseWeb: getTemplatePath: ' . $metapath);
        $_kp = $this->_ex_store__keyPathTemplate($metapath);
        $_cache = $this->_ex_store__storeGet($_kp);
        if (!empty($_cache)) {
            debug('objectBaseWeb: getTemplatePath: Get Template From Cache: ' . $metapath);
            return $_cache;
        }
        $resPath = $this->_ex_resource__getResourcePath(objectExtWithResource::TEMPLATE, $metapath);
        debug('objectBaseWeb: getTemplatePath: Downloaded Template: ' . $metapath);
        $this->_ex_store__storeSet($_kp, $resPath);
        return $resPath;
    }
    protected function getProperty ($metapath) {
        debug('objectBaseWeb: getProperty: ' . $metapath);
        $_kp = $this->_ex_store__keyPathProperty($metapath);
        $_cache = $this->_ex_store__storeGet($_kp);
        if (!empty($_cache)) {
            debug('objectBaseWeb: getProperty: Get Property From Cache: ' . $metapath);
            return $_cache;
        }
        $resValue = $this->_ex_resource__getResourceValue(objectExtWithResource::PROPERTY, $metapath);
        debug('objectBaseWeb: getProperty: Read Property: ' . $resValue . ' from ' . $metapath);
        $this->_ex_store__storeSet($_kp, $resValue);
        return $resValue;
    }
    
    /* public api */
    public function run ($command) { 
        debug($command, 'objectBaseWeb: run function:');
        //echo "<br> Running: " . $this->getObjectName();
        //echo "<br> With command: " . $command;
        //$ctx = contextMPWS::instance();
        //echo "<br> Last commmad: " . $ctx->getLastCommand();
        //echo "<br>";
        $this->_commonRunOnStart();
        $ret = false;
        switch ($command->getMethod()) {
            case 'main':
                $ret = $this->_run_main();
                break;
            case 'jsapi':
                $ret = $this->_run_jsapi();
                break;
        }
        $this->_commonRunOnEnd();
        return $ret;
    }
    
    /* execute modes */
    private function _run_main() {
        debug('objectBaseWeb => _run_main');
        $ret = false;
        // run common hook on startup
        $this->_displayTriggerOnCommonStart();
        // validate access key with plugin name to run in normal mode
        // or run customer
        //if (libraryRequest::getPage() === $this->getObjectName() || 
        if ($this->getObjectType() === OBJECT_T_CUSTOMER)
            $ret = $this->_displayTriggerAsCustomer(); // run on active
        //else
        if ($this->getObjectType() === OBJECT_T_PLUGIN)
            $ret = $this->_displayTriggerAsPlugin(); // run in background
        // run common hook in end up
        $this->_displayTriggerOnCommonEnd();
        return $ret;
    }
    private function _run_jsapi() {
        debug('objectBaseWeb => _run_jsapi');
        $ret = false;
        // run common hook on startup
        $this->_jsapiTriggerOnCommonStart();
        // validate access key with plugin name to run in normal mode
        // or run customer
        //if (libraryRequest::getPage() === $this->getObjectName() || 
        if ($this->getObjectType() === OBJECT_T_CUSTOMER)
            $ret = $this->_jsapiTriggerAsCustomer(); // run on active
        //else
        if ($this->getObjectType() === OBJECT_T_PLUGIN)
            $ret = $this->_jsapiTriggerAsPlugin(); // run in background
        // run common hook in end up
        $this->_jsapiTriggerOnCommonEnd();
        return $ret;
    }

    /* display triggers */
    protected function _displayTriggerOnCommonStart () {
        debug('objectBaseWeb => _displayTriggerOnCommonStart');
    }
    protected function _displayTriggerAsCustomer () {
        debug('objectBaseWeb => _displayTriggerAsCustomer');
        $_SESSION['MPWS_'.makeKey($this->getObjectType()).'_ACTIVE'] = $this->getObjectName();
        // reset active plugin
        $_SESSION['MPWS_'.makeKey(OBJECT_T_PLUGIN).'_ACTIVE'] = false;
        return false;
    }
    protected function _displayTriggerAsPlugin () {
        debug('objectBaseWeb => _displayTriggerAsPlugin');
        $_SESSION['MPWS_'.makeKey($this->getObjectType()).'_ACTIVE'] = libraryRequest::getPlugin(false);
        return false;
    }
    protected function _displayTriggerOnCommonEnd () {
        debug('objectBaseWeb => _displayTriggerOnCommonEnd');
    }

    /* js api triggers */
    protected function _jsapiTriggerOnCommonStart () {
        debug('objectBaseWeb => _jsapiTriggerOnCommonStart');
    }
    protected function _jsapiTriggerAsCustomer () {
        debug('objectBaseWeb => _jsapiTriggerAsCustomer');
    }
    protected function _jsapiTriggerAsPlugin () {
        debug('objectBaseWeb => _jsapiTriggerAsPlugin');
    }
    protected function _jsapiTriggerOnCommonEnd () {
        debug('objectBaseWeb => _jsapiTriggerOnCommonEnd');
    }

    /* running top common triggers */
    protected function _commonRunOnStart () {
        debug('objectBaseWeb => _commonRunOnStart');
    }
    protected function _commonRunOnEnd () {
        debug('objectBaseWeb => _commonRunOnEnd');
    }

    /*
     *  Web UI Methods
     */
    
    /* standart actions handlers */
    public function actionHandlerStandartDataTableManager ($widgetName, $events = array()) {
        switch (libraryRequest::getAction()) {
            case "new" :
            case "edit" : {
                $e = array();
                if (isset($events['EDIT']))
                    $e = $events['EDIT'];
                $this->widgetAddDataEditor($widgetName, $e);
                break;
            }
            case "delete" : {
                $this->widgetAddDataRecordRemoval($widgetName);
                break;
            }
            case "view" : {
                $this->widgetAddDataRecordViewer($widgetName);
                break;
            }
            case "manage" : {
                $this->widgetAddDataRecordManager($widgetName);
                break;
            }
            default : {
                libraryRequest::storeOrGetRefererUrl();
                $this->widgetAddDataTableView($widgetName);
                break;
            }
        }
    }
    
    /* Standart Widget Integration Methods */
    
    public function widgetAddSimple ($widgetName, $wgtData = false) {
       // $ctx = contextMPWS::instance();
        //$wnT = "objectTemplatePath_widget_" . $widgetName;
        //$ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
        $this->widgetAdd($widgetName, false, $wgtData);
    }
    
    public function widgetAddDataApiViewer ($widgetName = 'General') {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataApiViewer" . $widgetName};
        $wgtData = libraryComponents::getApiViewer($wgtConfig, $ctx->contextCustomer->getDBO());
        //var_dump($wgtData);
        $this->widgetAdd($widgetName, $wgtConfig, $wgtData, 'dataApiViewer');
    }
    
    public function widgetAddDataRecordViewer ($widgetName) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataRecordViewer" . $widgetName};
        $wgtData = libraryComponents::getDataRecordViewer($wgtConfig, $ctx->contextCustomer->getDBO());
        //var_dump($wgtData);
        $this->widgetAdd($widgetName, $wgtConfig, $wgtData, 'dataRecordViewer');
    }
    
    public function widgetAddDataRecordRemoval ($widgetName) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataRecordRemoval" . $widgetName};
        $wgtData = libraryComponents::getDataRecordRemoval($wgtConfig, $ctx->contextCustomer->getDBO());
        $this->widgetAdd($widgetName, $wgtConfig, $wgtData, 'dataRecordRemoval');
    }
    
    public function widgetAddDataTableView ($widgetName) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataTableView" . $widgetName};
        $wgtData = libraryComponents::getDataTableView($wgtConfig, $ctx->contextCustomer->getDBO());
        $this->widgetAdd($widgetName, $wgtConfig, $wgtData, 'dataTableView');
    }
    
    public function widgetAddDataEditor ($widgetName, $events = array()) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataEditor" . $widgetName};
        $wgtData = libraryComponents::getDataEditor($wgtConfig, $ctx->contextCustomer->getDBO(), $events);
        $this->widgetAdd($widgetName, $wgtConfig, $wgtData, 'dataEditor');
    }

    public function widgetAddDataRecordManager ($widgetName) {
        $ctx = contextMPWS::instance();
        $wgtConfig = $this->{"objectConfiguration_widget_dataRecordManager" . $widgetName};
        $wgtData = libraryComponents::getDataRecordManager($wgtConfig, $ctx->contextCustomer->getDBO());
        $this->widgetHookDataRecordManager($widgetName, $wgtData, $wgtConfig);
        $this->widgetAdd($widgetName, $wgtConfig, $wgtData, 'dataRecordManager');
    }

    /* hooks */
    protected function widgetHookDataRecordManager($widgetName, &$wgtData = false, $wgtConfig = false) {
        debug('objectBaseWebPlugin: hookBeforeAddWidgetDataRecordManager => ' . $widgetName);
    }
    
    /* internal methods */
    
    private function widgetAdd ($widgetName, $wgtConfig, $wgtData, $widgetParent = '') {
        $ctx = contextMPWS::instance();
        $wnT = "objectTemplatePath_widget_";
        
        $wnTbase = $wnT . "base" . ucfirst($widgetParent);
        $wnTowner = $wnT .$widgetParent . $widgetName;
        
        $widgetOriginalName = $widgetName;

        // check if we use default template
        try {
            if ($this->$wnTowner)
                $wnT = $wnTowner;
        } catch (Exception $exc) {
            debug('Exception at: ' . $exc->getMessage());
            $wnT = $wnTbase; // default widget name and resource to be used
        }
        $widgetName = $widgetParent.DOG.$widgetName;

        //var_dump($wgtConfig);
        //echo '<br>addWidget: '.$widgetName;
        //echo '<br>Template to be used: '.$wnT;
        $ctx->pageModel->addWidget($this, $widgetName, $this->$wnT, $wgtData);
        // add widget message
        if ($this->isObjectTypeEquals(OBJECT_T_PLUGIN))
            $ctx->pageModel->addMessage($widgetOriginalName.'StartupMessage', $this->getObjectName());
        else
            $ctx->pageModel->addMessage($widgetOriginalName.'StartupMessage');
    }
}

?>