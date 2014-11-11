<?php

namespace engine\lib;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of libraryPath
 *
 * @author aivaskev
 */
class path {

    public static function getDirectorySeparator () {
        return '/';
    }
    public static function getDirNameWeb () {
        return 'web';
    }
    public static function getDirNameDefault () {
        return 'base';
    }
    public static function getDirNameCustomer () {
        return 'customers';
    }
    public static function getDirNamePlugin () {
        return 'plugin';
    }
    public static function getDirNameConfig () {
        return 'config';
    }
    public static function getDirNameApi () {
        return 'api';
    }
    public static function getDirNameTask () {
        return 'task';
    }
    public static function getDirNameTemp () {
        return 'temp';
    }
    public static function getDirNameUploads () {
        return 'uploads';
    }

    public static function rootPath () {
        $_dr = strtolower($_SERVER['DOCUMENT_ROOT']);
        if ($_dr[strlen($_dr) - 1] != self::getDirectorySeparator())
            $_dr .= self::getDirectorySeparator();
        return $_dr;
    }

    public static function createPathWithRoot () {
        return self::rootPath() . join(self::getDirectorySeparator(), func_get_args());
    }

    public static function createPath (/* list, with, path, elements, $asDirectory */) {
        $args = func_get_args();
        $asDirectory = false;
        if (is_bool(end($args))) {
            $asDirectory = array_pop($args);
        }
        $p = join(self::getDirectorySeparator(), $args);
        if ($asDirectory && $p[strlen($p) - 1] != self::getDirectorySeparator())
            $p .= self::getDirectorySeparator();
        return $p;
    }

    public function createDirPath () {
        $args = func_get_args();
        $p = join(self::getDirectorySeparator(), $args);
        if ($p[strlen($p) - 1] != self::getDirectorySeparator())
            $p .= self::getDirectorySeparator();
        return $p;
    }

    public function createFilePath () {
        $args = func_get_args();
        $p = join(self::getDirectorySeparator(), $args);
        return $p;
    }

    public static function getDirWeb () {
        return self::createPathWithRoot(self::getDirNameWeb() . self::getDirectorySeparator());
    }

    public static function getDefaultDir ($version) {
        return self::getDirWeb() . self::getDirNameDefault() . self::getDirectorySeparator() . $version . self::getDirectorySeparator();
    }

    public static function getCustomerDir ($customer) {
        return self::getDirWeb() . self::getDirNameCustomer() . self::getDirectorySeparator() . $customer . self::getDirectorySeparator();
    }

    public static function getPluginDir ($name = false) {
        if (!empty($name))
            return self::getDirWeb() . self::getDirNamePlugin() . self::getDirectorySeparator() . $name . self::getDirectorySeparator();
        return self::getDirWeb() . self::getDirNamePlugin() . self::getDirectorySeparator();
    }

    // public static function getCustomerConfigurationFiles ($customer) {
    //     return glob(self::getCustomerDir($customer) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php');
    // }

    // public static function getDefaultConfigurationFiles ($customer) {
    //     return glob(self::getDefaultDir($customer) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php');
    // }

    // public static function getPluginConfigurationFiles ($name) {
    //     return glob(self::getPluginDir($name) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php');
    // }

    // public static function getPluginApiFiles ($name) {
    //     return glob(self::getPluginDir($name) . self::getDirNameApi() . self::getDirectorySeparator() . '*.php');
    // }

    // public static function getCustomerConfigurationFilesMap ($customer) {
    //     return self::listWithPathsToNamePathMap(self::getCustomerConfigurationFiles($customer));
    // }

    // public static function getDefaultConfigurationFilesMap ($version) {
    //     return self::listWithPathsToNamePathMap(self::getDefaultConfigurationFiles($version));
    // }

    // public static function getPluginConfigurationFilesMap ($name) {
    //     return self::listWithPathsToNamePathMap(self::getPluginConfigurationFiles($name));
    // }

    // public static function getPluginApiFilesMap ($name) {
    //     return self::listWithPathsToNamePathMap(self::getPluginApiFiles($name));
    // }

    // public static function getPluginApiFilesMap ($name) {
    //     return self::listWithPathsToNamePathMap(self::getPluginApiFiles($name));
    // }

    // public static function listWithPathsToNamePathMap (array $listWithPaths) {
    //     $map = array();
    //     foreach ($listWithPaths as $value) {
    //         $pInfo = pathinfo($value);
    //         $map[$pInfo['filename']] = $value;
    //     }
    //     return $map;
    // }

    public static function getCustomerConfigNames ($customer) {
        $files = glob(self::getCustomerDir($customer) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php') ?: array();
        return self::getFileNamesFromFileList($files);
    }
    public static function getDefaultConfigNames ($version) {
        $files = glob(self::getDefaultDir($version) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php') ?: array();
        return self::getFileNamesFromFileList($files);
    }
    public static function getPluginConfigNames ($plugin) {
        $files = glob(self::getPluginDir($plugin) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php') ?: array();
        return self::getFileNamesFromFileList($files);
    }
    public static function getPluginApiNames ($plugin) {
        $files = glob(self::getPluginDir($plugin) . self::getDirNameApi() . self::getDirectorySeparator() . '*.php') ?: array();
        return self::getFileNamesFromFileList($files);
    }
    public static function getPluginTaskNames ($plugin) {
        $files = glob(self::getPluginDir($plugin) . self::getDirNameTask() . self::getDirectorySeparator() . '*.php') ?: array();
        return self::getFileNamesFromFileList($files);
    }

    public static function getFileNamesFromFileList (array $list) {
        $names = array();
        foreach ($list as $value) {
            $pInfo = pathinfo($value);
            $names[] = $pInfo['filename'];
        }
        return $names;
    }



    public static function getUploadDirectory (/* args */) {
        $args = func_get_args();
        array_unshift($args, Path::getDirNameUploads());
        return call_user_func_array(__NAMESPACE__ .'\Path::createDirPath', $args);
    }

    public static function getUploadTemporaryDirectory () {
        return self::getUploadDirectory(self::getDirNameTemp());
    }

    public static function getAppTemporaryDirectory () {
        return self::getUploadDirectory() . self::getDirectorySeparator() . '_tmp_' . date('Ymd_H');
    }


    public static function getUploadedFile ($name) {
        return $this->getUploadDirectory() . self::getDirectorySeparator() . $name;
    }

    public static function moveTemporaryFile ($tmpFileName, $innerUploadTargetDir, $customFileName = null) {
        // var_dump(func_get_args());
        $tmpFilePath = self::rootPath() . self::getUploadTemporaryDirectory() . $tmpFileName;
        // var_dump($tmpFilePath);
        if (file_exists($tmpFilePath)) {
            $info = array();
            $targetDirFullPath = self::rootPath() . self::getUploadDirectory($innerUploadTargetDir);
            if (!file_exists($targetDirFullPath)) {
                mkdir($targetDirFullPath, 0777, true);
            }
            $tempFileInfo = pathinfo($tmpFileName);
            $_fileBaseName = basename(!empty($customFileName) ? $customFileName : $tempFileInfo['filename']);
            $_fileExtension = $tempFileInfo['extension'];
            $_fileName = strtolower($_fileBaseName . '.' . $_fileExtension);
            // var_dump($tmpFilePath);
            // var_dump($targetDirFullPath);
            // var_dump($_fileName);
            rename($tmpFilePath, $targetDirFullPath . $_fileName);
            $info['basename'] = $_fileBaseName;
            $info['extension'] = $_fileExtension;
            $info['filename'] = $_fileName;
            $info['uploadDir'] = $innerUploadTargetDir;
            $info['uploadPath'] = $innerUploadTargetDir . $_fileName;
            return $info;
        }
        return false;
    }

    public function deleteUploadedFile ($pathToFile) {
        $filePath = self::getUploadDirectory() . $pathToFile;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public static function getWebStaticTemplateFilePath ($displayCustomer, $version, $templateName, $isDebug) {
        if ($isDebug) {
            $templateCustomer = Path::createPathWithRoot(self::getDirNameWeb(), self::getDirNameCustomer(), $displayCustomer, 'static', 'hbs', $templateName);
            $templateDefault = Path::createPathWithRoot(self::getDirNameWeb(), self::getDirNameDefault(), $version, 'static', 'hbs', $templateName);
        } else {
            $templateCustomer = Path::createPathWithRoot(self::getDirNameWeb(), self::getDirNameCustomer(), $displayCustomer, 'static', 'hbs', $templateName);
            $templateDefault = Path::createPathWithRoot(self::getDirNameWeb(), self::getDirNameDefault(), $version, 'static', 'hbs', $templateName);
        }
        // return available template file path
        if (file_exists($templateCustomer))
            return $templateCustomer;
        else if (file_exists($templateDefault))
            return $templateDefault;
        return false;
    }

}

?>