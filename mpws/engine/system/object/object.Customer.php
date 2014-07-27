<?php

class objectCustomer implements ICustomer {

    private $dbo;
    private $plugins;
    private $extensions;
    private $customerInfo;

    function __construct() {

        // init dbo
        $this->dbo = new libraryDataBase(configurationCustomerDatabase::$DBOini);
        $this->extensions['auth'] = new extensionAuth($this);

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
            $this->plugins[$pluginName] = new $pluginObjectName($this);
        }
        $this->customerInfo = $this->getCustomerInfo();
    }

    public function getCustomerID () {
        $info = $this->getCustomerInfo();
        return isset($info['ID']) ? $info['ID'] : null;
    }

    public function getCustomerInfo () {
        if (empty($this->customerInfo)) {
            $config = configurationCustomerDataSource::jsapiGetCustomer();
            $this->customerInfo = $this->getDataBase()->getData($config);
        }
        return $this->customerInfo;
    }

    public function getDataBase () {
        return $this->dbo;
    }

    public function fetch ($config, $skipCustomerID = false) {
        $customerInfo = $this->getCustomerInfo();
        // var_dump($customerInfo);
        if (!isset($config["condition"]["CustomerID"]) && !$skipCustomerID)
            $config["condition"]["CustomerID"] = configurationCustomerDataSource::jsapiCreateDataSourceCondition($customerInfo['ID']);
        // var_dump($config);
        return $this->dbo->getData($config);
    }

    public function getAllPlugins () {
        return $this->plugins;
    }

    public function getPlugin($key) {
        return $this->plugins[$key];
    }

    public function hasPlugin ($pluginName) {
        return !empty($this->plugins[$pluginName]);
    }

    public function getExtension ($extensionName) {
        return $this->extensions[$extensionName];
    }

    public function hasExtension ($extensionName) {
        return !empty($this->extensions[$extensionName]);
    }

    public function runAsAPI () {
        $publicKey = "";
        if (libraryRequest::hasInGet('token'))
            $publicKey = libraryRequest::fromGET('token');

        // // check page token
        // if (empty($publicKey)) {
        //     libraryResponse::setError('EmptyToken', "HTTP/1.0 500 EmptyToken");
        //     return;
        // }

        // if (!libraryRequest::getOrValidatePageSecurityToken(configurationCustomerDisplay::$MasterJsApiKey, $publicKey)) {
        //     libraryResponse::setError('InvalidTokenKey', "HTTP/1.0 500 InvalidTokenKey");
        //     return;
        // }

        // if (MPWS_IS_TOOLBOX && !configurationCustomerDisplay::$IsManaged) {
        //     libraryResponse::setError('AccessDenied', "HTTP/1.0 500 AccessDenied");
        //     return;
        // }

        // libraryResponse::$_RESPONSE['authenticated'] = $this->getPlugin('account')->isAuthenticated();
        // libraryResponse::$_RESPONSE['script'] = libraryRequest::getScriptName();
        foreach ($this->plugins as $plugin)
            $plugin->run();
        libraryResponse::$_RESPONSE['auth_id'] = $this->getExtension('auth')->getAuthID();
    }

    public function runAsAUTH () {
        libraryRequest::processRequest($this->getExtension('auth'));
    }

}

?>