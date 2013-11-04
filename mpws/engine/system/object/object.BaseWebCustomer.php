<?php

class objectBaseWebCustomer extends objectBaseWeb /*implements iPlugin*/ {
    
    /* Base Implementation */
    public function __construct () {
        parent::__construct(MPWS_CUSTOMER, OBJECT_T_CUSTOMER, MPWS_VERSION);
        debug('objectBaseWebCustomer: __construct => ' . MPWS_CUSTOMER);
    }

    /* public */
    public function getDBConnection ($connectionType = T_CONNECT_DB) {
        switch ($connectionType) {
            case T_CONNECT_DB:
                return array(
                    'DB_HOST' => $this->objectConfiguration_mdbc_host,
                    'DB_USERNAME' => $this->objectConfiguration_mdbc_username,
                    'DB_PASSWORD' => $this->objectConfiguration_mdbc_password,
                    'DB_NAME' => $this->objectConfiguration_mdbc_name,
                    'DB_CHARSET' => $this->objectConfiguration_mdbc_charset,
                    'DB_CONNECTION_STRING' => sprintf("mysql:dbname=%s;host=%s", $this->objectConfiguration_mdbc_name, $this->objectConfiguration_mdbc_host)
                );
            case T_CONNECT_ORM:
                return array(
                    "connection_string" => sprintf("mysql:dbname=%s;host=%s;charset=%s", $this->objectConfiguration_mdbc_name, $this->objectConfiguration_mdbc_host, $this->objectConfiguration_mdbc_charset),
                    "id_column" => 'ID',
                    "username" => $this->objectConfiguration_mdbc_username,
                    "password" => $this->objectConfiguration_mdbc_password
                );
            default:
                break;
        }
    }

    public function getActivePlugins () {
        return $this->objectConfiguration_customer_plugins;
    }

    public function getCustomerID () {
        $userInfo = librarySecurity::getUserInfo();
        if (isset($userInfo['CUSTOMER']))
            return $userInfo['CUSTOMER'];
        return null;
    }

    /* common run triggers  */
    protected function _commonRunOnStart () {
        parent::_commonRunOnStart();
        $ctx = contextMPWS::instance();
        // connect to db
        $ctx->contextCustomer->getDBO();
    }

    /* display triggers */
    protected function _displayTriggerAsCustomer () {
        parent::_displayTriggerAsCustomer();

        $ctx = contextMPWS::instance();
        $ctx->pageModel->setSiteObject($this);
        
        debug('objectBaseWebCustomer => _displayTriggerOnActive');
        //echo 'active page is ' . libraryRequest::getPage();
        $ret = false;
        
        //echo '_displayTriggerAsCustomer';
        
        if ($ctx->pageModel->allowToProcessPages()) {
            switch(libraryRequest::getPage()) {
                case 'index':
                    $ret = $this->_displayPage_Index();
                    break;
                case 'about-us':
                    $ret = $this->_displayPage_AboutUs();
                    break;
                case 'terms':
                    $ret = $this->_displayPage_Terms();
                    break;
                case 'contacts':
                    $ret = $this->_displayPage_Contacts();
                    break;
                // do not implement NotFound page
                // should be used in object implementation
            }
        }
        
        return $ret;
    }
    
    protected function _jsapiTriggerAsCustomer () {
        parent::_jsapiTriggerAsCustomer();
        $ctx = contextMPWS::instance();
        $p = libraryRequest::getApiParam();
        $caller = libraryRequest::getApiCaller();

        if (empty($caller))
            throw new Exception('objectBaseWeb => _jsapiTriggerAsCustomer: wrong caller value', $caller);
        
        // check page token
        if (empty($p['token']))
            return;
        
        if (!libraryRequest::getOrValidatePageSecurityToken($p['token'])) {
            // page token is wrong
            // try to verify master key
            // echo 'try to verify master key: ' . $this->objectConfiguration_customer_masterJsApiKey;
            //echo '<br> ' . md5($this->objectConfiguration_customer_masterJsApiKey);
            //echo '<br>token: ' . $p['token'];
            if (md5($this->objectConfiguration_customer_masterJsApiKey) !== $p['token'])
                return;
        }

        // echo '2 Caller is ' . $caller, $p['realm'];
        //echo print_r($p, true);
        // perform request with plugins
        if (!empty($p['realm']) && $p['realm'] == OBJECT_T_PLUGIN) {
            // check if wide js api is allowed
            if ($p['realm'] == '*' && !$this->objectConfiguration_customer_allowWideJsApi)
                throw new Exception('objectBaseWeb => _jsapiTriggerAsCustomer: wide api js request is not allowed');
            // perform request with plugins
            // echo 'OLOLOLOLO = ' . $caller . DOG . 'jsapi:default', 'Toolbox';
            return $ctx->directProcess($caller . DOG . 'jsapi:default', 'Toolbox');
        } else {
            // echo 'ASDF';
            // otherwise proceed with customer
        }
        return false;
    }

    /* display pages */
    protected function _displayPage_Index () {
        debug('objectBaseWebCustomer => _displayPage_Home');
        return true;
    }
    protected function _displayPage_AboutUs () {
        debug('objectBaseWebCustomer => _displayPage_AboutUs');
        return true;
    }
    protected function _displayPage_Terms () {
        debug('objectBaseWebCustomer => _displayPage_Terms');
        return true;
    }
    protected function _displayPage_Contacts () {
        debug('objectBaseWebCustomer => _displayPage_Contacts');
        return true;
    }
    protected function _displayPage_NotFound () {
        debug('objectBaseWebCustomer => _displayPage_Contacts');
        return true;
    }

}

?>