<?php
namespace engine\object;

class customer extends \engine\object\multiExtendable implements \engine\interface\ICustomer {

    private $dbo;
    private $plugins;
    private $configuration;
    // private $extensions;
    private $customerInfo;

    function __construct() {

        // init dbo
        $this->dbo = new \engine\lib\dataBase(configurationCustomerDatabase::$DBOini);

        // init extensions
        $this->addExtension(new \engine\extension\auth($this)); // move to middleware
        $this->addExtension(new \engine\extension\dataInterface($this)); // thinnk to optmize

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
            $this->plugins[$pluginName] = new $pluginObjectName($this, $pluginName);
        }

        // init configuration

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
        return $this->plugins[$key] ?: null;
    }

    public function hasPlugin ($pluginName) {
        return !empty($this->plugins[$pluginName]);
    }

    public function runAsAPI () {

        // if (glIsToolbox()) {
        //     $publicKey = "";
        //     if (\engine\lib\request::hasInGet('token'))
        //         $publicKey = \engine\lib\request::fromGET('token');

        // // // check page token
        // // if (empty($publicKey)) {
        // //     \engine\lib\response::setError('EmptyToken', "HTTP/1.0 500 EmptyToken");
        // //     return;
        // // }

        // // if (!\engine\lib\request::getOrValidatePageSecurityToken(configurationCustomerDisplay::$MasterJsApiKey, $publicKey)) {
        // //     \engine\lib\response::setError('InvalidTokenKey', "HTTP/1.0 500 InvalidTokenKey");
        // //     return;
        // // }

        // // if (MPWS_IS_TOOLBOX && !configurationCustomerDisplay::$IsManaged) {
        // //     \engine\lib\response::setError('AccessDenied', "HTTP/1.0 500 AccessDenied");
        // //     return;
        // // }
        // }

        // \engine\lib\response::$_RESPONSE['authenticated'] = $this->getPlugin('account')->isAuthenticated();
        // \engine\lib\response::$_RESPONSE['script'] = \engine\lib\request::getScriptName();

        // refresh auth
        $this->updateSessionAuth();
        foreach ($this->plugins as $plugin)
            $plugin->run();
    }

    public function runAsAUTH () {
        \engine\lib\request::processRequest($this->getExtension('Auth'));
    }
    public function runAsUPLOAD () {
        /*
         * jQuery File Upload Plugin PHP Example 5.14
         * https://github.com/blueimp/jQuery-File-Upload
         *
         * Copyright 2010, Sebastian Tschan
         * https://blueimp.net
         *
         * Licensed under the MIT license:
         * http://www.opensource.org/licenses/MIT
         */
        $options = array(
            'script_url' => configurationDefaultUrls::$upload,
            'download_via_php' => true,
            'upload_dir' => \engine\lib\utils::getUploadTemporaryDirectory(),
            'print_response' => $_SERVER['REQUEST_METHOD'] === 'GET'
        );
        $upload_handler = new \engine\lib\uploadHandler($options);
        \engine\lib\response::$_RESPONSE = $upload_handler->get_response();
        // refresh auth
        $this->updateSessionAuth();
        // bypass response to all plugins
        foreach ($this->plugins as $plugin)
            $plugin->run();
    }

}

?>