<?php
namespace engine\objects;

use \engine\lib\utils as Utils;
use \engine\lib\path as Path;
use \engine\lib\request as Request;

class plugin {

    private $api;
    private $customer;
    private $configuration;
    private $app;

    function __construct ($customer, $pluginName, $app) {
        $this->customer = $customer;
        $this->app = $app;

        // init configuration
        $configuration = array();
        $pluginConfigs = Path::getPluginConfigurationFilesMap($pluginName);
        foreach ($pluginConfigs as $configName => $configFilePath) {
            $configClass = Utils::getPluginConfigClassName($configName, $pluginName);// '\\web\\plugin\\' . $pluginName . '\\config\\' . $configName;
            $configuration[$configName] = new $configClass();
        }
        $this->configuration = (object)$configuration;

        // init apis
        $api = array();
        $pluginApis = Path::getPluginApiFilesMap($pluginName);
        foreach ($pluginApis as $apiName => $apiFilePath) {
            $apiClass = Utils::getApiClassName($apiName, $pluginName);
            // $apiClass = '\\web\\plugin\\' . $pluginName . '\\api\\' . $apiName;
            // save plugin instance
            $api[$apiName] = new $apiClass($customer, $this, $pluginName, $app);
        }
        $this->api = (object)$api;
    }

    public function getName () {
        return 'base';
    }

    public function getConfiguration () {
        return $this->configuration;
    }

    public function getAPI () {
        return $this->api;
    }

    public function getApp () {
        return $this->app;
    }

    public function getCustomer () {
        return $this->customer;
    }

    public function getCustomerDataBase () {
        return $this->customer->getDataBase();
    }

    public function getCustomerConfiguration () {
        return $this->customer->getConfiguration();
    }

    public function getPlugin ($pluginName = false) {
        if (empty($pluginName))
            return $this->plugin;
        return $this->customer->getPlugin($pluginName);
        
    }

    public function beforeRun () {}
    public function afterRun () {}

    // public function moveUploadedFile ($sourceDir, $targetDir, $name) {
    //     $dirSrc = $this->getUploadDirectory($sourceDir);
    //     $dirTarget = $this->getUploadDirectory($targetDir);
    //     $filePathOld = $dirSrc . DS . $name;
    //     $filePathNew = $dirTarget . DS . $name;
    //     if (file_exists($dirTarget)) {
    //         mkdir($dirTarget, 0777, true);
    //     }
    //     rename($filePathOld, $filePathNew);
    //     return false;
    // }

    public function run () {
        $this->beforeRun();
        Request::processRequest($this);
        $this->afterRun();
    }
}

?>