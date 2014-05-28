<?php

class objectCustomer {

    private $dbo;
    private $plugins;
    private $customerInfo;

    function __construct() {

        // init dbo
        $this->dbo = new libraryDataBase(configurationCustomerDatabase::$DBOini);
        $this->accountManager = new libraryAccountManager();

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

    public function getAccountManager () {
        return $this->accountManager;
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

    public function getAccount () {
        return $this->dbo;
    }

    public function processData ($config) {
        $customerInfo = $this->getCustomerInfo();
        // var_dump($customerInfo);
        if (!isset($config["condition"]["CustomerID"]))
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

    public function getPluginData ($source, $function, $params = null) {
        $data = new libraryDataObject();
        $plugin = $this->getPlugin($source);
        if (empty($plugin))
            return $data;
        if (!method_exists($plugin, $function))
            return $data;
        return $plugin->$function($params);
    }

    public function getResponse () {

        $response = new libraryDataObject();
        $publicKey = libraryRequest::fromGET('token');
        $source = libraryRequest::fromGET('source');

        if (empty($source)) {
            $response->setError('WrongSource');
            header("HTTP/1.0 404 WrongSource");
            return $response;
        }

        if (!isset($this->plugins[$source])) {
            $response->setError('UnknownSource');
            header("HTTP/1.0 404 UnknownSource");
            return $response;
        }

        // check page token
        if (empty($publicKey)) {
            $response->setError('EmptyToken');
            header("HTTP/1.0 500 EmptyToken");
            return $response;
        }

        if (!libraryRequest::getOrValidatePageSecurityToken(configurationCustomerDisplay::$MasterJsApiKey, $publicKey)) {
            $response->setError('InvalidTokenKey');
            header("HTTP/1.0 500 InvalidTokenKey");
            return $response;
        }

        if (MPWS_IS_TOOLBOX && !configurationCustomerDisplay::$IsManaged) {
            $response->setError('AccessDenied');
            header("HTTP/1.0 500 AccessDenied");
            // $response->setData('redirect', 'signin');
            return $response;
        }
        // if ($source == '*' && !configurationCustomerDatabase::$AllowWideJsApi)
        //     throw new Exception('objectCustomer => getResponse: wide api js request is not allowed');

        // this section must be located when all plugins are performed
        // becuse the toolbox plugin does user validation and authorizations
        // $dataAccount = $this->isAuthenticated();
        // $accountIsEmpty = $dataAccountStatus->isEmpty('account');

        // if (MPWS_IS_TOOLBOX && $source === 'account') {
        //     if (libraryRequest::hasInGET('fn') && libraryRequest::fromGET('fn') === "status")
        //         return $dataAccountStatus;
        //     if (!libraryRequest::hasInGET('fn') || libraryRequest::fromGET('fn') !== "signin") {
        //         $response->setError('LoginRequired');
        //         header("HTTP/1.0 500 LoginRequired");
        //         // $response->setData('redirect', 'signin');
        //         return $response;
        //     }
        // }

        if ($source == '*')
            foreach ($this->plugins as $key => $plugin)
                $response->setData($key, $plugin->getResponse()->toNative());
        else {
            $data = $this->getPluginData($source, libraryRequest::getRequestMethodName());
            $response->overwriteData($data->toNative());
        }


        $response->setData('authenticated', $this->isAuthenticated());

        return $response;
    }

    // Admin status (requires toolbox plugin)
    public function isAuthenticated () {
        return $this->getPluginData('account', 'get_status')->hasKey('account');
    }

}

?>