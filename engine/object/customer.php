<?php
namespace engine\object;

use \engine\lib\utils as Utils;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\response as Response;
use \engine\lib\database as DB;
use \engine\lib\uploadHandler as JqUploadLib;

class customer extends \engine\object\multiExtendable implements \engine\interface\ICustomer {

    private $version = 'atlantis';
    private $app;
    private $dbo;
    private $plugins;
    private $configuration;
    // private $extensions;
    private $customerInfo;
    private $htmlPage;

    function __construct($app) {

        $this->app = $app;

        // init configuration
        $configuration = array();
        $defaultConfigs = Path::getDefaultConfigurationFilesMap($this->getVersion());
        $customerConfigs = Path::getCustomerConfigurationFilesMap($this->getApp()->customerName());
        foreach ($defaultConfigs as $configName => $configFilePath) {
            if (isset($customerConfigs[$configName])) {
                $configClass = '\\web\\plugin\\' . $pluginName . '\\config\\' . $pInfo['filename'];
            } else {
                $configClass = '\\web\\plugin\\' . $this->getVersion() . '\\config\\' . $pInfo['filename'];
            }
            $configuration[$configName] = new $configClass();
        }
        $this->configuration = (object)$configuration;

        // init dbo
        $this->dbo = new DB($this->getConfiguration()->db->getConnectionParams($this->getApp()->isDebug()));

        // init extensions
        $this->addExtension(new \engine\extension\auth($this)); // move to middleware
        $this->addExtension(new \engine\extension\dataInterface($this)); // thinnk to optmize

        // init plugins
        $_pluginPath = Path::createPathWithRoot('web', 'plugin');
        foreach ($this->getConfiguration()->display->Plugins as $pluginName) {
            // load plugin
            $pluginClass = '\\web\\plugin\\' . $pluginName . '\\plugin';
            // save plugin instance
            $this->plugins[$pluginName] = new $pluginClass($this, $pluginName, $app);
        }

        $this->customerInfo = $this->getCustomerInfo();
    }

    public function getApp () {
        return $this->app;
    }

    public function getConfiguration () {
        return $this->configuration;
    }

    public function getVersion () {
        return $this->version;
    }

    public function getHtmlPage () {
        $displayCustomer = $this->getApp()->displayCustoner();
        $layout = 'layout.hbs';
        $layoutBody = 'layoutBody.hbs';

        // get customer or default index layout
        if ($this->getApp()->isDebug()) {
            // get layout
            $layoutCustomer = Path::createPathWithRoot('web', 'customer', $displayCustomer, 'static', 'hbs', $layout);
            $layoutDefault = Path::createPathWithRoot('web', 'default', $this->getVersion(), 'static', 'hbs', $layout);
            // get layout body
            $layoutBodyCustomer = Path::createPathWithRoot('web', 'customer', $displayCustomer, 'static', 'hbs', $layoutBody);
            $layoutBodyDefault = Path::createPathWithRoot('web', 'default', $this->getVersion(), 'static', 'hbs', $layoutBody);
        } else {
            // get layout
            $layoutCustomer = Path::createPathWithRoot('web', 'build', 'customer', $displayCustomer, 'static', 'hbs', $layout);
            $layoutDefault = Path::createPathWithRoot('web', 'build', 'default', $this->getVersion(), 'static', 'hbs', $layout);
            // get layout body
            $layoutBodyCustomer = Path::createPathWithRoot('web', 'build', 'customer', $displayCustomer, 'static', 'hbs', $layoutBody);
            $layoutBodyDefault = Path::createPathWithRoot('web', 'build', 'default', $this->getVersion(), 'static', 'hbs', $layoutBody);
        }

        debug($layoutCustomer, 'layoutCustomer');
        debug($layoutDefault, 'layoutDefault');

        $staticPath = 'static';
        $initialJS = "{
            LOCALE: '" . $this->getConfiguration()->display->Locale . "',
            BUILD: " . ($this->getApp()->isDebug() ? 'null' : $this->getApp()->getBuildVersion()) . ",
            ISDEV: " . ($this->getApp()->isDebug() ? 'true' : 'false') . ",
            TOKEN: '" . Request::getOrValidatePageSecurityToken($this->getConfiguration()->display->MasterJsApiKey) . "',
            ISTOOLBOX: " . ($this->getApp()->isToolbox() ? 'true' : 'false') . ",
            PLUGINS: ['" . implode("', '", $this->getConfiguration()->display->Plugins) . "'],
            MPWS_VERSION: '" . $this->getVersion() . "',
            MPWS_CUSTOMER: '" . $displayCustomer . "',
            PATH_STATIC_BASE: '/',
            URL_PUBLIC_HOMEPAGE: '" . $this->getConfiguration()->display->Homepage . "',
            URL_PUBLIC_TITLE: '" . $this->getConfiguration()->display->Title . "',
            URL_API: '/api.js',
            URL_AUTH: '/auth.js',
            URL_UPLOAD: '/upload.js',
            URL_STATIC_CUSTOMER: '/" . Path::createPath($staticPath, 'customer', $displayCustomer, true) . "',
            URL_STATIC_WEBSITE: '/" . Path::createPath($staticPath, 'customer', $displayCustomer, true) . "',
            URL_STATIC_PLUGIN: '/" . Path::createPath($staticPath, 'plugin', true) . "',
            URL_STATIC_DEFAULT: '/" . Path::createPath($staticPath, 'default', $this->getVersion(), true) . "',
            ROUTER: '" . join(Path::getDirectorySeparator(), array('customer', 'js', 'router')) . "'
        }";
        $initialJS = str_replace(array("\r","\n", '  '), '', $initialJS);

        $html = '';
        $layoutBodyContent = '';

        // init html with layout content
        if (file_exists($layoutCustomer))
            $html = file_get_contents($layoutCustomer);
        else if (file_exists($layoutDefault))
            $html = file_get_contents($layoutDefault);

        // get layout body content
        if (file_exists($layoutBodyCustomer))
            $layoutBodyContent = file_get_contents($layoutBodyCustomer);
        else if (file_exists($layoutBodyDefault))
            $layoutBodyContent = file_get_contents($layoutBodyDefault);

        // add system data
        $html = str_replace("{{BODY}}", $layoutBodyContent, $html);
        $html = str_replace("{{LANG}}", $this->getConfiguration()->display->Lang, $html);
        $html = str_replace("{{SYSTEMJS}}", $initialJS, $html);
        $html = str_replace("{{MPWS_VERSION}}", $this->getVersion(), $html);
        $html = str_replace("{{MPWS_CUSTOMER}}", $displayCustomer, $html);
        $html = str_replace("{{PATH_STATIC}}", $staticPath, $html);
        return $html;
    }

    public function getCustomerID () {
        $info = $this->getCustomerInfo();
        return isset($info['ID']) ? $info['ID'] : null;
    }

    public function getCustomerInfo () {
        if (empty($this->customerInfo)) {
            $config = $this->getConfiguration()->data->jsapiGetCustomer();
            $this->customerInfo = $this->getDataBase()->getData($config);
        }
        return $this->customerInfo;
    }

    public function getDataBase () {
        return $this->dbo;
    }

    public function fetch ($config, $skipCustomerID = false) {
        $customerInfo = $this->getCustomerInfo();
        if (!isset($config["condition"]["CustomerID"]) && !$skipCustomerID)
            $config["condition"]["CustomerID"] = $this->getConfiguration()->data->jsapiCreateDataSourceCondition($customerInfo['ID']);
        return $this->dbo->getData($config);
    }

    public function getAllPlugins () {
        return $this->plugins;
    }

    public function getPlugin ($key) {
        return $this->plugins[$key] ?: null;
    }

    public function hasPlugin ($pluginName) {
        return !empty($this->plugins[$pluginName]);
    }

    public function runAsDISPLAY () {
        Response::setResponse($this->getHtmlPage());
    }

    public function runAsAPI () {

        // if (glIsToolbox()) {
        //     $publicKey = "";
        //     if (Request::hasInGet('token'))
        //         $publicKey = Request::fromGET('token');

        // // // check page token
        // // if (empty($publicKey)) {
        // //     \engine\lib\response::setError('EmptyToken', "HTTP/1.0 500 EmptyToken");
        // //     return;
        // // }

        // // if (!Request::getOrValidatePageSecurityToken($this->getConfiguration()->display->MasterJsApiKey, $publicKey)) {
        // //     \engine\lib\response::setError('InvalidTokenKey', "HTTP/1.0 500 InvalidTokenKey");
        // //     return;
        // // }

        // // if (MPWS_IS_TOOLBOX && !$this->getConfiguration()->display->IsManaged) {
        // //     \engine\lib\response::setError('AccessDenied', "HTTP/1.0 500 AccessDenied");
        // //     return;
        // // }
        // }

        // \engine\lib\response::$_RESPONSE['authenticated'] = $this->getPlugin('account')->isAuthenticated();
        // \engine\lib\response::$_RESPONSE['script'] = Request::getScriptName();

        // refresh auth
        $this->updateSessionAuth();
        foreach ($this->plugins as $plugin)
            $plugin->run();
    }

    public function runAsAUTH () {
        Request::processRequest($this->getExtension('Auth'));
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
            'upload_dir' => Utils::getUploadTemporaryDirectory(),
            'print_response' => $_SERVER['REQUEST_METHOD'] === 'GET'
        );
        $upload_handler = new JqUploadLib($options);
        Response::setResponse($upload_handler->get_response());
        // refresh auth
        $this->updateSessionAuth();
        // bypass response to all plugins
        foreach ($this->plugins as $plugin)
            $plugin->run();
    }

}

?>