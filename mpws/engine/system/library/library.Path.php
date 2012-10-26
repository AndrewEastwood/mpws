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
}

?>
