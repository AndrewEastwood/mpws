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
        // $this->setExtender('objectExtWithConfiguration', '_ex_config');

        //var_dump($this->getMeta());
        $locale = libraryRequest::getLocale($this->objectConfiguration_customer_locale);
        // if ($this->getObjectLocale() != $locale) {
            $this->setObjectLocale($locale);
            // $this->updateExtenders();
        // }

        // different versions
        // all plugins must use version that customer uses
        // if ($this->getObjectVersion() != $this->objectConfiguration_customer_version) {
        //     $this->setMeta('PATH_DEF', DR . '/web/default/' . $this->objectConfiguration_customer_version);
        //     $this->setMeta('VERSION', $this->objectConfiguration_customer_version);
        //     $this->updateExtenders();
        // }
        
        // apply system settings
        $pgH = $this->objectConfiguration_system_pageHeaders;
        debug($pgH, 'objectBaseWeb: >> Applying system settings: headers');
        foreach ($pgH as $pageHeader)
            header($pageHeader);
        debug($this->objectConfiguration_system_pageTimeZone, 'objectBaseWeb: >> Applying system settings: time zone');
        date_default_timezone_set($this->objectConfiguration_system_pageTimeZone);

    }
    // protected function objectCustomProperty($name) {
    //     if (startsWith($name, 'objectConfiguration'))
    //         return $this->getConfiguration(str_replace(array('objectConfiguration_', '_'), array('', '.'), $name));
    //     return parent::objectCustomProperty($name);
    // }

    /* resource access */
    // protected function getConfiguration ($metapath) {
    //     debug('objectBaseWeb: getConfiguration: ' . $metapath);
    //     $_kp = $this->_ex_store__keyPathConfiguration($metapath);
    //     $_cache = $this->_ex_store__storeGet($_kp);
    //     if (!empty($_cache)) {
    //         debug('objectBaseWeb: getConfiguration: Get Configuration From Cache: ' . $metapath);
    //         return $_cache;
    //     }
    //     $resValue = $this->_ex_config__getConfigurationValue($metapath);
    //     debug('objectBaseWeb: getConfiguration: Downloaded Configuration: ' . $metapath);
    //     $this->_ex_store__storeSet($_kp, $resValue);
    //     return $resValue;
    // }
    
    /* public api */
    public function run ($command) { 
        debug($command, 'objectBaseWeb: run function:');
        // echo "<br> Running: " . $this->getObjectName(); 
        //echo "<br> With command: " . $command;
        //$ctx = contextMPWS::instance();
        //echo "<br> Last commmad: " . $ctx->getLastCommand();
        //echo "<br>";
        $this->_commonRunOnStart();
        $ret = false;
        switch ($command->getMethod()) {
            case 'jsapi':
                $ret = $this->_run_jsapi();
                break;
        }
        $this->_commonRunOnEnd();
        return $ret;
    }
    
    /* execute modes */
    private function _run_jsapi() {
        debug('objectBaseWeb => _run_jsapi');
        // echo "LLLLLL", $this->getObjectType();
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
}

?>