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
    
    public static function getStandartDataPathWithDBR ($dataBaseRecord, $pathAppend = false) {
        // $dataBaseRecord - it is dataBase record that:
        // - contains DataPath field
        // - contains ExternalKey field
        // Please not that one of them must be defined
        // DataPath field has higher priority than ExternalKey
        
        $path = false;
        if (!empty($dataBaseRecord['DataPath']))
            $path = $dataBaseRecord['DataPath'] . DS . $pathAppend;
        
        if (!empty($path) && file_exists($path))
            return $path;
        
        // try to resolve path with ExternalKey
        if (empty($dataBaseRecord['ExternalKey']))
            throw new Exception('libraryPath => getStandartDataPathWithDBR: Can not resolve standart data path with DataPath nor ExternalKey');
        
        $path = self::getPathDataObject($dataBaseRecord['ExternalKey']) . DS . $pathAppend;

        if (!empty($path) && file_exists($path))
            return $path;
        
        throw new Exception('libraryPath => getStandartDataPathWithDBR: Wrong dataBaseRecord value passed');
    }
}

?>
