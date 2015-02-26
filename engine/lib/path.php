<?php

namespace engine\lib;

use \Exception;
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
    public static function getDirNameEngine () {
        return 'engine';
    }
    public static function getDirNameMW () {
        return 'middlewares';
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
        return 'plugins';
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

    public static function createDirPath () {
        $args = func_get_args();
        $p = join(self::getDirectorySeparator(), $args);
        if ($p[strlen($p) - 1] != self::getDirectorySeparator())
            $p .= self::getDirectorySeparator();
        return $p;
    }

    public static function createFilePath () {
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

    public static function deleteUploadedFile ($pathToFile) {
        $filePath = self::rootPath() . self::getUploadDirectory() . $pathToFile;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public static function getWebStaticTemplateFilePath ($displayCustomer, $templateName, $isDebug) {
        // if ($isDebug) {
        //     $templateCustomer = Path::createPathWithRoot(self::getDirNameWeb(), self::getDirNameCustomer(), $displayCustomer, 'hbs', $templateName);
        //     $templateDefault = Path::createPathWithRoot(self::getDirNameWeb(), self::getDirNameDefault(), $version, 'hbs', $templateName);
        // } else {
            $templateCustomer = Path::createPathWithRoot(self::getDirNameWeb(), self::getDirNameCustomer(), $displayCustomer, 'hbs', $templateName);
            $templateDefault = Path::createPathWithRoot(self::getDirNameWeb(), self::getDirNameDefault(), 'hbs', $templateName);
        // }
        // var_dump($templateCustomer);
        // var_dump($templateDefault);
        // return available template file path
        if (file_exists($templateCustomer))
            return $templateCustomer;
        else if (file_exists($templateDefault))
            return $templateDefault;
        return false;
    }

}

?>