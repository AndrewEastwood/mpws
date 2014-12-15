<?php
namespace engine\objects;

use \engine\lib\utils as Utils;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\response as Response;

class plugin {

    private $api;
    private $customer;
    private $configuration;
    private $app;
    private $task;

    function __construct ($customer, $pluginName, $app) {
        $this->customer = $customer;
        $this->app = $app;

        // init configuration
        $configuration = array();
        $pluginConfigs = Path::getPluginConfigNames($pluginName);
        foreach ($pluginConfigs as $configName) {
            $configClass = Utils::getPluginConfigClassName($configName, $pluginName);// '\\web\\plugin\\' . $pluginName . '\\config\\' . $configName;
            $configuration[$configName] = new $configClass($customer, $app);
        }
        $this->configuration = (object)$configuration;

        // init apis
        $api = array();
        $pluginApis = Path::getPluginApiNames($pluginName);
        foreach ($pluginApis as $apiName) {
            $apiClass = Utils::getApiClassName($apiName, $pluginName);
            // $apiClass = '\\web\\plugin\\' . $pluginName . '\\api\\' . $apiName;
            // save plugin instance
            $api[$apiName] = new $apiClass($customer, $this, $pluginName, $app);
        }
        $this->api = (object)$api;

        // init tasks
        $task = array();
        $pluginApis = Path::getPluginTaskNames($pluginName);
        foreach ($pluginApis as $taskName) {
            $taskClass = '\\web\\plugin\\' . $pluginName . '\\task\\' . $taskName;
            // save plugin instance
            $task[$taskName] = new $taskClass($customer, $this, $pluginName, $app);
        }
        $this->task = (object)$task;
    }

    public function getName () {
        return 'base';
    }

    public function getConfiguration () {
        return $this->configuration;
    }

    public function getAPI ($key = false) {
        if (isset($key) && isset($this->api->$key))
            return $this->api->$key;
        return $this->api;
    }

    // public function getTASK ($key) {
    //     if (isset($key))
    //         return $this->task->$key;
    //     return $this->task;
    // }

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

    // public function beforeRun () {}
    // public function afterRun () {}

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
        $_REQ = Request::getRequestData();
        $_source = Request::pickFromGET('source');
        $_fn = Request::pickFromGET('fn');
        $_method = strtolower($_SERVER['REQUEST_METHOD']);
        $requestFnElements = array($_method);

        if (Request::hasInGet('source'))
            $requestFnElements[] = $_source;
        
        if (Request::hasInGet('fn'))
            $requestFnElements[] = $_fn;

        $fn = join("_", $requestFnElements);
        if (isset($this->getAPI()->$_fn) && method_exists($this->getAPI()->$_fn, $_method)) {
            $this->getAPI()->$_fn->$_method(Response::$_RESPONSE, $_REQ);
            // var_dump(\engine\lib\response::$_RESPONSE);
        } elseif (method_exists($this, $fn)) {
            $this->$fn(Response::$_RESPONSE, $_REQ);
        }
    }

    public function task ($task) {
        foreach ($this->task as $task) {
            $task->exec();
        }
    }
}

?>