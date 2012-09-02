<?php

class libraryStorage {
    
    static private $instance = NULL;
    static private $CACHE = array();
    static private $STORAGE = array();
    
    private function __construct () { }

    
    public static function getInstance () {
        if (self::$instance == NULL) {
            self::$instance = new libraryStorage();
        }
        return self::$instance;
    }
    
    public static function &storage ($key, $obj = null, $append = true) {
        //$chachedObj = self::_cache($key);
        
        if ($key == '__all__' && !isset($obj))
            return self::$STORAGE;
        
        echo '<br>libraryStorage::storage [' . $key . '] ';
        $key = strtoupper($key);
        if (isset($obj)) {
            echo ' saving object!';
            echo '<i><pre>' . print_r($obj, true) . '</pre></i>';
            if ($append && isset(self::$STORAGE[$key])) {
                $obj = self::_mergeData(self::$STORAGE[$key], $obj);
            }
            self::$STORAGE[$key] = $obj;
        }
        
        echo '<br>';
        return self::$STORAGE[$key];
    }
    
    public static function clearCache () {
        self::$CACHE = array();
        return true;
    }
    public static function clearStorage () {
        self::$STORAGE = array();
        return true;
    }
    public static function clearAll () {
        self::clearCache();
        self::clearStorage();
    }
    
    private static function _cache ($key) {
        if (isset(self::$CACHE[$key]))
            return self::$CACHE[$key];
        return null;
    }
    
    private static function _mergeData ($arrTgt, $arrSrc) {
        foreach ($arrSrc as $key => $val) {
            // check keys
            if (isset($arrTgt[$key])) {
                if (is_array($arrTgt[$key]) && is_array($val))
                    $arrTgt[$key] = self::_mergeData($arrTgt[$key], $val);
                elseif (is_string($arrTgt[$key]) && is_string($val))
                    $arrTgt[$key] .= $val;
                else
                    $arrTgt[$key] = $val;
            } else
                $arrTgt[$key] = $val;
        }
        return $arrTgt;
    }
}

?>