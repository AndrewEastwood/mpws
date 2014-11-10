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

    // public static function getPathData () {
    //     return DR . "data";
    // }
    
    // public static function getPathDataObject ($owner) {
    //     return self::getPathData() . DS . 'custom' . DS . $owner;
    // }
    
    // public static function getStandartDataPathWithDBR ($dataBaseRecord, $pathAppend = false, $mkdir = false) {
    //     // $dataBaseRecord - it is dataBase record that:
    //     // - contains DataPath field
    //     // - contains ExternalKey field
    //     // Please not that one of them must be defined
    //     // DataPath field has higher priority than ExternalKey
        
    //     $path = false;
    //     if (!empty($dataBaseRecord['DataPath']))
    //         $path = $dataBaseRecord['DataPath'] . DS . $pathAppend;

    //     //var_dump($dataBaseRecord);
    //     //echo 'CHECK IS = ' . $path;

    //     if (!empty($path) && file_exists($path))
    //         return $path;
        
    //     // create file if it does not exists
    //     if ($mkdir) {
    //         $dir = dirname($path);
    //         if(!file_exists($dir))
    //             mkdir ($dir, 0777, true);
    //         file_put_contents($path, '/* mpws autocreated empty file*/');
    //         return $path;
    //     }
        
    //     // try to resolve path with ExternalKey
    //     if (empty($dataBaseRecord['ExternalKey']))
    //         throw new Exception('libraryPath => getStandartDataPathWithDBR: Can not resolve standart data path with DataPath nor ExternalKey');
        
    //     $path = self::getPathDataObject($dataBaseRecord['ExternalKey']) . DS . $pathAppend;

    //     if (!empty($path) && file_exists($path))
    //         return $path;

    //     throw new Exception('libraryPath => getStandartDataPathWithDBR: Wrong dataBaseRecord value passed. Expected path is: ' . $path);
    // }

    // function glWrap ($key, $value) {
    //   $rez = array();
    //   $rez[$key] = $value;
    //   return $rez;
    // }

    // function fromGET ($value, $default = '') {
    //     if (isset($value))
    //         return $value;
    //     return $default;
    // }

    // function glGetFullPath () {
    //     return DR . call_user_func_array('glGetPath', func_get_args());
    // }
    // function glGetPath () {
    //     // debug (func_get_args());
    //     // var_dump(debug_backtrace());
    //     $numargs = func_num_args();
    //     $_isFile = strrpos(func_get_arg($numargs - 1), '.') > 1;
    //     if ($_isFile)
    //         return join(DS, func_get_args());
    //     else
    //         return join(DS, func_get_args()) . DS;
    // }

    // // get debug level
    // function glGetDebugLevel (){
    //     return MPWS_LOG_LEVEL;
    // }
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
    //     $args = func_get_args();
    //     if (!is_bool(end($args))) {
    //         $args[] = true;
    //     }
    //     return call_user_func_array(__NAMESPACE__ .'\Path::createPath', $args);
        $args = func_get_args();
        // $asDirectory = false;
        // if (is_bool(end($args))) {
        //     $asDirectory = array_pop($args);
        // }
        // var_dump($args);
        $p = join(self::getDirectorySeparator(), $args);
        if ($p[strlen($p) - 1] != self::getDirectorySeparator())
            $p .= self::getDirectorySeparator();
        return $p;
    }

    public function createFilePath () {
        // $args = func_get_args();
        // if (is_bool(end($args))) {
        //     array_pop($args);
        // }
        // return call_user_func_array(__NAMESPACE__ .'\Path::createPath', $args);
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

    public static function getCustomerConfigurationFiles ($customer) {
        return glob(self::getCustomerDir($customer) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php');
    }

    public static function getDefaultConfigurationFiles ($customer) {
        return glob(self::getDefaultDir($customer) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php');
    }

    public static function getPluginConfigurationFiles ($name) {
        return glob(self::getPluginDir($name) . self::getDirNameConfig() . self::getDirectorySeparator() . '*.php');
    }

    public static function getPluginApiFiles ($name) {
        return glob(self::getPluginDir($name) . self::getDirNameApi() . self::getDirectorySeparator() . '*.php');
    }

    public static function getCustomerConfigurationFilesMap ($customer) {
        return self::listWithPathsToNamePathMap(self::getCustomerConfigurationFiles($customer));
    }

    public static function getDefaultConfigurationFilesMap ($version) {
        return self::listWithPathsToNamePathMap(self::getDefaultConfigurationFiles($version));
    }

    public static function getPluginConfigurationFilesMap ($name) {
        return self::listWithPathsToNamePathMap(self::getPluginConfigurationFiles($name));
    }

    public static function getPluginApiFilesMap ($name) {
        return self::listWithPathsToNamePathMap(self::getPluginApiFiles($name));
    }

    public static function listWithPathsToNamePathMap (array $listWithPaths) {
        $map = array();
        foreach ($listWithPaths as $value) {
            $pInfo = pathinfo($value);
            $map[$pInfo['filename']] = $value;
        }
        return $map;
    }






    public static function getUploadDirectory (/* args */) {
        $args = func_get_args();
        array_unshift($args, Path::getDirNameUploads());
        // $path = ;
        // if (!empty($args)) {
        // return $path . self::getDirectorySeparator() . join(self::getDirectorySeparator(), $args);
        // }
        //  .  self::getDirectorySeparator() . join(self::getDirectorySeparator(), func_get_args());
        // if ($path[strlen($path) - 1] !== self::getDirectorySeparator())
        //     $path .= self::getDirectorySeparator();
        // return $path . self::getDirectorySeparator();
        return call_user_func_array(__NAMESPACE__ .'\Path::createDirPath', $args);
        // return self::createDirPath($args);
    }

    public static function getUploadTemporaryDirectory () {
        return self::getUploadDirectory(self::getDirNameTemp());
    }

    public static function getAppTemporaryDirectory () {
        return self::getUploadDirectory() . self::getDirectorySeparator() . '_tmp_' . date('Ymd_H');
    }


    public static function getUploadedFile ($name) {
        // $args = func_get_args();
        // $fName = array_pop($args);
        // $path = call_user_func_array(array(__NAMESPACE__ .'\Path::getUploadDirectory'), $args);
        // $dir = $this->getOwnUploadDirectory($targetDir);
        return $this->getUploadDirectory() . self::getDirectorySeparator() . $name;
        // return $path . $fName;
    }

    // public static function getUploadWebPath (/* args */) {
    //     $args = func_get_args();
    //     $pluginUploadPath = self::getDirectorySeparator() . self::getDirNameUploads();
    //     if (!empty($args)) {
    //         return $pluginUploadPath . self::getDirectorySeparator() . join(self::getDirectorySeparator(), $args);
    //     }
    //     return $pluginUploadPath . self::getDirectorySeparator();
    // }


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



    // public function getOwnUploadDirectory ($targetDir = null) {
    //     if (empty($targetDir)) {
    //         return Path::getUploadDirectory($this->getName());
    //     }
    //     return Path::getUploadDirectory($this->getName() . Path::getDirectorySeparator() . $targetDir);
    // }
    // public function getOwnUploadPathWeb ($targetDir = null) {
    //     if (empty($targetDir)) {
    //         return Path::getUploadWebPath($this->getName());
    //     }
    //     return Path::getUploadWebPath($this->getName() . Path::getDirectorySeparator() . $targetDir);
    // }

    // public function getOwnUploadedFileWeb ($name, $targetDir = null) {
    //     $dir = $this->getOwnUploadPathWeb($targetDir);
    //     return $dir . $name;
    // }

    // public function saveOwnTemporaryUploadedFile ($tmpFileName, $targetDir, $name = false) {
    //     $uniqueName = empty($name) ? mktime() : $name;
    //     $uploadedFileInfo = Path::moveTemporaryFile($tmpFileName, $this->getName() . Path::getDirectorySeparator() . $targetDir, $uniqueName);
    //     return $uploadedFileInfo;
    // }

    // public function deleteOwnUploadedFile ($name, $targetDir) {
    //     $dir = $this->getOwnUploadDirectory($targetDir);
    //     $filePath = $dir . $name;
    //     if (file_exists($filePath)) {
    //         return unlink($filePath);
    //     }
    //     return false;
    // }






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