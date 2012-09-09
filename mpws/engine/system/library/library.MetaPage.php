<?php

class libraryMetaPage {
    
    private static $_components = array();
    
    public static function addComponent($name, $data) {
        self::$_components[$name] = $data;
    }
    
    public static function getComponent ($name) {
        return self::$_components[$name];
    }
    
    
    public static function getAllComponents () {
        return self::$_components;
    }
}

?>