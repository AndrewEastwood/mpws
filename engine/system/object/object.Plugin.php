<?php

class objectPlugin implements IPlugin {

    private $customer;
    public $api;

    function __construct ($customer, $pluginName) {
        $this->customer = $customer;
        // init apis
        $api = array();
        $_pluginPath = glGetFullPath('web', 'plugin', $pluginName, 'api');
        $_apiFiles = glob($_pluginPath . '*.php');
        foreach ($_apiFiles as $apiFilePath) {

            $path_parts = pathinfo($apiFilePath);
            $apiFileName = $path_parts['filename'];

            // load plugin
            include $apiFilePath;
            $apiObjectName = 'api'.ucfirst($pluginName).ucfirst($apiFileName);

            // save plugin instance
            $api[$apiFileName] = new $apiObjectName($customer, $this, $pluginName);
        }
        $this->api = (object)$api;
    }

    public function getName() {
        return 'base';
    }

    public function getCustomer() {
        return $this->customer;
    }

    public function getCustomerDataBase () {
        return $this->customer->getDataBase();
    }

    public function getAnotherPlugin ($pluginName) {
        $anotherPlugin = $this->getCustomer()->getPlugin($pluginName);
        return $anotherPlugin;
    }

    public function beforeRun () {}
    public function afterRun () {}

    public function getUploadDirectory ($targetDir = null) {
        if (empty($targetDir)) {
            return libraryUtils::getUploadDirectory($this->getName());
        }
        return libraryUtils::getUploadDirectory($this->getName() . DS . $targetDir);
    }
    public function getUploadWebPath ($targetDir = null) {
        if (empty($targetDir)) {
            return libraryUtils::getUploadWebPath($this->getName());
        }
        return libraryUtils::getUploadWebPath($this->getName() . DS . $targetDir);
    }

    public function getUploadedFile ($name, $targetDir = null) {
        $dir = $this->getUploadDirectory($targetDir);
        return $dir . DS . $name;
    }

    public function getUploadedFileForWeb ($name, $targetDir = null) {
        $dir = $this->getUploadWebPath($targetDir);
        return $dir . DS . $name;
    }

    public function saveUploadedFile ($tmpFilePath, $targetDir, $name = false) {
        $uniqueName = empty($name) ? mktime() : $name;
        $uploadedFileInfo = libraryUtils::moveTemporaryFile($tmpFilePath, $this->getName() . DS . $targetDir, $uniqueName);
        // $uploadedFileInfo['name'] = $uniqueName;
        return $uploadedFileInfo;
    }

    public function deleteUploadedFile ($name, $targetDir) {
        $dir = $this->getUploadDirectory($targetDir);
        $filePath = $dir . DS . $name;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public function moveUploadedFile ($sourceDir, $targetDir, $name) {
        $dirSrc = $this->getUploadDirectory($sourceDir);
        $dirTarget = $this->getUploadDirectory($targetDir);
        $filePathOld = $dirSrc . DS . $name;
        $filePathNew = $dirTarget . DS . $name;
        if (file_exists($dirTarget)) {
            mkdir($dirTarget, 0777, true);
        }
        rename($filePathOld, $filePathNew);
        return false;
    }

    public function run () {
        $this->beforeRun();
        libraryRequest::processRequest($this);
        $this->afterRun();
    }
}

?>