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

    public static function getDirWeb () {
        return DR . 'web' . self::getDirectorySeparator();
    }

    public static function getDefaultDir ($version) {
        return self::getDirWeb() . 'default' . self::getDirectorySeparator() . $version . self::getDirectorySeparator();
    }

    public static function getCustomerDir ($customer) {
        return self::getDirWeb() . 'customer' . self::getDirectorySeparator() . $customer . self::getDirectorySeparator();
    }

    public static function getPluginDir ($name = false) {
        if (!empty($name))
            return self::getDirWeb() . 'plugin' . self::getDirectorySeparator() . $name . self::getDirectorySeparator();
        return self::getDirWeb() . 'plugin' . self::getDirectorySeparator();
    }

    public static function getCustomerConfigurationFiles ($version) {
        return glob(self::getDefaultDir($version) . 'config' . self::getDirectorySeparator() . '*.php');
    }

    public static function getDefaultConfigurationFiles ($customer) {
        return glob(self::getCustomerDir($custome) . 'config' . self::getDirectorySeparator() . '*.php');
    }

    public static function getPluginConfigurationFiles ($name) {
        return glob(self::getPluginDir($name) . 'config' . self::getDirectorySeparator() . '*.php');
    }

    public static function getPluginApiFiles ($name) {
        return glob(self::getPluginDir($name) . 'api' . self::getDirectorySeparator() . '*.php');
    }

    public static function getCustomerConfigurationFilesMap ($version) {
        return self::listWithPathsToNamePathMap(self::getCustomerConfigurationFiles($version));
    }

    public static function getDefaultConfigurationFilesMap ($customer) {
        return self::listWithPathsToNamePathMap(self::getDefaultConfigurationFiles($customer));
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

    public static function getUploadTemporaryDirectory () {
        return self::getUploadDirectory('temp') . self::getDirectorySeparator();
    }

    public static function getAppTemporaryDirectory () {
        return self::getUploadDirectory('_tmp_' . date('Ymd_H')) . self::getDirectorySeparator();
    }

    public static function getUploadDirectory ($realm = null) {
        $pluginUploadPath = self::rootPath() . 'uploads';
        if (!empty($realm)) {
            return $pluginUploadPath . self::getDirectorySeparator() . $realm .self::getDirectorySeparator();
        }
        return $pluginUploadPath . self::getDirectorySeparator();
    }
    public static function getUploadWebPath ($realm = null) {
        $pluginUploadPath = self::getDirectorySeparator() . 'uploads';
        if (!empty($realm)) {
            return $pluginUploadPath . self::getDirectorySeparator() . $realm . self::getDirectorySeparator();
        }
        return $pluginUploadPath . self::getDirectorySeparator();
    }

    public static function moveTemporaryFile ($tmpFileName, $targetDir, $customFileName = null) {
        $tmpFilePath = self::getUploadTemporaryDirectory() . $tmpFileName;
        if (file_exists($tmpFilePath)) {
            $info = array();
            $targetDirFullPath = self::getUploadDirectory($targetDir);
            if (!file_exists($targetDirFullPath)) {
                mkdir($targetDirFullPath, 0777, true);
            }
            $tempFileInfo = pathinfo($tmpFileName);
            $_fileBaseName = basename(!empty($customFileName) ? $customFileName : $tempFileInfo['filename']);
            $_fileExtension = $tempFileInfo['extension'];
            $_fileName = strtolower($_fileBaseName . '.' . $_fileExtension);
            rename($tmpFilePath, $targetDirFullPath . $_fileName);
            $info['basename'] = $_fileBaseName;
            $info['extension'] = $_fileExtension;
            $info['filename'] = $_fileName;
            $info['uploadDir'] = $targetDir;
            $info['uploadPath'] = $targetDir . $_fileName;
            return $info;
        }
        return false;
    }

    public static function getInstanceByFilePath ($filePath) {
        $ns = substr($filePath, strlen(self::rootPath()) - 1);
        new $ns();
    }
}

?>