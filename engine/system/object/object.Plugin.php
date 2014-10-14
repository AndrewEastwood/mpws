<?php

class objectPlugin implements IPlugin {

    private $customer;

    function __construct ($customer) {
        $this->customer = $customer;
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

    public function getPlugin ($pluginName) {
        $anotherPlugin = $this->getCustomer()->getPlugin($pluginName);
        return $anotherPlugin;
    }

    // public function getExtension ($extensionName) {
    //     return $this->getCustomer()->getExtension($extensionName);
    // }

    // public function getSessionAccountID () {
    //     // $extAuth = $this->getExtension('Auth');
    //     // if (empty($extAuth))
    //     //     throw new Exception("Auth extension is missing for plugin", 1);
    //     return $this->getCustomer()->getAuthID();
    // }

    // public function ifYouCan ($action) {
    //     // $extAuth = $this->getExtension('Auth');
    //     // if (empty($extAuth))
    //     //     throw new Exception("Auth extension is missing for plugin", 1);
    //     return $this->getCustomer()->ifYouCan($action);
    // }

    public function beforeRun () {}
    public function afterRun () {}

    public function getUploadDirectory ($realm = null) {
        if (empty($realm)) {
            return libraryUtils::getUploadDirectory($this->getName());
        }
        return libraryUtils::getUploadDirectory($this->getName() . DS . $realm);
    }

    public function saveUploadedFile ($tmpFilePath, $targetDir, $name = false) {
        $uniqueName = empty($name) ? mktime() : $name;
        $uploadedFileInfo = libraryUtils::moveTemporaryFile($tmpFilePath, $this->getName() . DS . $targetDir, $uniqueName);
        // $uploadedFileInfo['name'] = $uniqueName;
        return $uploadedFileInfo;
    }

    public function deleteUploadedFile ($targetDir, $name) {
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

    // public function deleteFile ($filePath) {
    //     $filePath = $this->getUploadDirectory($filePath);
    //     if (file_exists($filePath)) {
    //         return unlink($filePath);
    //     }
    //     return false;
    // }
    
    // public function moveFile ($filePath, $newDir) {
    //     $filePath = $this->getUploadDirectory($filePath);
    //     $fileNewDir = $this->getUploadDirectory($newDir);
    //     $fileInfo = pathinfo($filePath);
    //     if (!file_exists($fileNewDir)) {
    //         mkdir($fileNewDir, 0777, true);
    //     }
    //     $fileNewPath = $fileNewDir . DS . $fileInfo['basename'];
    //     rename($filePath, $fileNewPath);
    // }

    // public function clearPluginUploadsInRealm ($realm) {
    //     // libraryUtils::moveTemporaryFile($tmpImageName, $this->getUploadDirectory($realm));
    // }

    public function run () {
        $this->beforeRun();
        libraryRequest::processRequest($this);
        $this->afterRun();
    }
}

?>