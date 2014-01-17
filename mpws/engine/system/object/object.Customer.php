<?php

class objectCustomer {

    private $dbo;
    private $plugins;

    function __construct() {

        // init dbo
        $this->dbo = new libraryDataBase(configurationCustomerDatabase::$DBOini);

        // init plugins
        $_pluginPath = glGetFullPath('web', 'plugin');
        foreach (configurationCustomerDisplay::$Plugins as $pluginName) {

            $pluginFileName = OBJECT_T_PLUGIN . DOT . $pluginName . EXT_SCRIPT;
            $pluginFilePath = $_pluginPath . DS . $pluginName . DS . $pluginFileName;

            debug('libraryPluginManager: getPluginWithContext plugin path: ' . $pluginFilePath);

            if (!file_exists($pluginFilePath))
                throw new Exception('MPWS PluginManager library: path does not exists: ' . $pluginFilePath);
            
            // load plugin
            include $pluginFilePath;
            $matches = null;
            preg_match('/^(\\w+).(\\w+).(\\w+)$/', $pluginFileName, $matches);
            $pluginObjectName = trim($matches[1]).trim($matches[2]);

            // save plugin instance
            $this->plugins[$pluginName] = new $pluginObjectName();
        }
    }

    public function getDBO () {
        return $this->dbo;
    }

    public function getPlugins () {
        return $this->plugins;
    }

    public function getResponse () {

        $response = array();

        $p = libraryRequest::getApiParam();
        $caller = libraryRequest::getApiCaller();

        if (empty($caller))
            throw new Exception('objectBaseWeb => _jsapiTriggerAsCustomer: wrong caller value', $caller);

        // check page token
        if (empty($p['token']))
            return $response;

        // check request realm
        if (empty($p['realm']))
            return $response;

        if (!libraryRequest::getOrValidatePageSecurityToken($p['token'])) {
            if (md5(configurationCustomerDatabase::$MasterJsApiKey) !== $p['token'])
                return $response;
        }

        // perform request with plugins
        if ($p['realm'] == OBJECT_T_PLUGIN) {
            if ($caller == '*' && !configurationCustomerDatabase::$AllowWideJsApi)
                throw new Exception('objectBaseWeb => _jsapiTriggerAsCustomer: wide api js request is not allowed');

            if ($caller == '*')
                foreach ($this->plugins as $key => $plugin)
                    $response[$key] = $plugin->getResponse();
            elseif (isset($this->plugins[$caller])) {
                $plugin = $this->plugins[$caller];
                $response[$caller] = $plugin->getResponse();
            }
        }

        if ($p['realm'] == OBJECT_T_CUSTOMER) {
            // otherwise proceed with customer
            // $response[OBJECT_T_CUSTOMER] = array();
        }

        return json_encode($response);


        // $p = libraryRequest::getApiParam();
        // $caller = libraryRequest::getApiCaller();

        // if (empty($caller))
        //     throw new Exception('objectBaseWeb => _jsapiTriggerAsCustomer: wrong caller value', $caller);
        
        // // check page token
        // if (empty($p['token']))
        //     return;
        
        // if (!libraryRequest::getOrValidatePageSecurityToken($p['token'])) {
        //     // page token is wrong
        //     // try to verify master key
        //     // echo 'try to verify master key: ' . $this->objectConfiguration_customer_masterJsApiKey;
        //     //echo '<br> ' . md5($this->objectConfiguration_customer_masterJsApiKey);
        //     //echo '<br>token: ' . $p['token'];

        //     if (md5($this->objectConfiguration_customer_masterJsApiKey) !== $p['token'])
        //         return;
        // }

        // // echo '2 Caller is ' . $caller, $p['realm'];
        // //echo print_r($p, true);
        // // perform request with plugins
        // if (!empty($p['realm']) && $p['realm'] == OBJECT_T_PLUGIN) {
        //     // check if wide js api is allowed
        //     if ($p['realm'] == '*' && !$this->objectConfiguration_customer_allowWideJsApi)
        //         throw new Exception('objectBaseWeb => _jsapiTriggerAsCustomer: wide api js request is not allowed');
        //     // perform request with plugins
        //     // echo 'OLOLOLOLO = ' . $caller . DOG . 'jsapi:default', 'Toolbox';
        //     // return $ctx->directProcess($caller . DOG . 'jsapi:default', 'Toolbox');
        // } else {
        //     // echo 'ASDF';
        //     // otherwise proceed with customer
        // }
        // return false;
    }

}

?>