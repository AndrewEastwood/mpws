<?php
namespace engine\object;

class plugin implements \engine\interface\IPlugin {

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
            $apiObjectName = 'api' . ucfirst($pluginName) . ucfirst($apiFileName);
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

    public function getOwnUploadDirectory ($targetDir = null) {
        if (empty($targetDir)) {
            return \engine\lib\utils::getUploadDirectory($this->getName());
        }
        return \engine\lib\utils::getUploadDirectory($this->getName() . DS . $targetDir);
    }
    public function getOwnUploadPathWeb ($targetDir = null) {
        if (empty($targetDir)) {
            return \engine\lib\utils::getUploadWebPath($this->getName());
        }
        return \engine\lib\utils::getUploadWebPath($this->getName() . DS . $targetDir);
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
        $uploadedFileInfo = \engine\lib\utils::moveTemporaryFile($tmpFileName, $this->getName() . DS . $targetDir, $uniqueName);
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
        $this->beforeRun();
        \engine\lib\request::processRequest($this);
        $this->afterRun();
    }
}

?>