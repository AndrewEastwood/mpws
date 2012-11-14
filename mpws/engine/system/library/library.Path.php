<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of libraryPath
 *
 * @author aivaskev
 */
class libraryPath {

    public static function getPathData () {
        return DR . "data";
    }
    
    public static function getPathDataObject ($owner) {
        return self::getPathData() . DS . 'custom' . DS . $owner;
    }
    
    public static function getStandartDataPathWithDBR ($dataBaseRecord, $pathAppend = false, $mkdir = false) {
        // $dataBaseRecord - it is dataBase record that:
        // - contains DataPath field
        // - contains ExternalKey field
        // Please not that one of them must be defined
        // DataPath field has higher priority than ExternalKey
        
        $path = false;
        if (!empty($dataBaseRecord['DataPath']))
            $path = $dataBaseRecord['DataPath'] . DS . $pathAppend;

        //var_dump($dataBaseRecord);
        //echo 'CHECK IS = ' . $path;

        if (!empty($path) && file_exists($path))
            return $path;
        
        // create file if it does not exists
        if ($mkdir) {
            $dir = dirname($path);
            if(!file_exists($dir))
                mkdir ($dir, 0777, true);
            file_put_contents($path, '/* mpws autocreated empty file*/');
            return $path;
        }
        
        // try to resolve path with ExternalKey
        if (empty($dataBaseRecord['ExternalKey']))
            throw new Exception('libraryPath => getStandartDataPathWithDBR: Can not resolve standart data path with DataPath nor ExternalKey');
        
        $path = self::getPathDataObject($dataBaseRecord['ExternalKey']) . DS . $pathAppend;

        if (!empty($path) && file_exists($path))
            return $path;

        throw new Exception('libraryPath => getStandartDataPathWithDBR: Wrong dataBaseRecord value passed. Expected path is: ' . $path);
    }
}

?>