<?php
namespace engine\object;

use \engine\lib\utils as Utils;
use \engine\lib\path as Path;
use \engine\lib\request as Request;

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
        $pluginConfigs = Path::getPluginConfigurationFilesMap($pluginName);
        foreach ($pluginConfigs as $configName => $configFilePath) {
            $configClass = '\\web\\plugin\\' . $pluginName . '\\config\\' . $configName;
            $configuration[$configName] = new $configClass();
        }
        $this->configuration = (object)$configuration;

        // init apis
        $api = array();
        $pluginApis = Path::getPluginApiFilesMap($pluginName);
        foreach ($pluginApis as $apiName => $apiFilePath) {
            $apiClass = '\\web\\plugin\\' . $pluginName . '\\api\\' . $apiName;
            // save plugin instance
            $api[$apiName] = new $apiClass($customer, $this, $pluginName, $app);
        }
        $this->api = (object)$api;

        // init tasks
        $task = array();
        $pluginApis = Path::getPluginTasksFilesMap($pluginName);
        foreach ($pluginApis as $taskName => $apiFilePath) {
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

    public function getAPI ($key) {
        if (isset($key))
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

    public function getAnotherPlugin ($pluginName) {
        $anotherPlugin = $this->getCustomer()->getPlugin($pluginName);
        return $anotherPlugin;
    }

    // public function beforeRun () {}
    // public function afterRun () {}

    public function getOwnUploadDirectory ($targetDir = null) {
        if (empty($targetDir)) {
            return Path::getUploadDirectory($this->getName());
        }
        return Path::getUploadDirectory($this->getName() . Path::getDirectorySeparator() . $targetDir);
    }
    public function getOwnUploadPathWeb ($targetDir = null) {
        if (empty($targetDir)) {
            return Path::getUploadWebPath($this->getName());
        }
        return Path::getUploadWebPath($this->getName() . Path::getDirectorySeparator() . $targetDir);
    }

    public function getOwnUploadedFile ($name, $targetDir = null) {
        $dir = $this->getOwnUploadDirectory($targetDir);
        return $dir . $name;
    }

    public function getOwnUploadedFileWeb ($name, $targetDir = null) {
        $dir = $this->getOwnUploadPathWeb($targetDir);
        return $dir . $name;
    }

    public function saveOwnTemporaryUploadedFile ($tmpFileName, $targetDir, $name = false) {
        $uniqueName = empty($name) ? mktime() : $name;
        $uploadedFileInfo = Path::moveTemporaryFile($tmpFileName, $this->getName() . Path::getDirectorySeparator() . $targetDir, $uniqueName);
        return $uploadedFileInfo;
    }

    public function deleteOwnUploadedFile ($name, $targetDir) {
        $dir = $this->getOwnUploadDirectory($targetDir);
        $filePath = $dir . $name;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

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
        $_REQ = self::getRequestData();
        $_source = self::fromGET('source');
        $_fn = self::fromGET('fn');
        $_method = strtolower($_SERVER['REQUEST_METHOD']);
        $requestFnElements = array($_method);

        if (self::hasInGet('source'))
            $requestFnElements[] = $_source;
        
        if (self::hasInGet('fn'))
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