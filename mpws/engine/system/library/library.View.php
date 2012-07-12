<?php

class libraryView
{

    public function getTemplateResult ($model, $templateFile) {
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }
    
    public function getFrendlyClassName ($className) {
        
        $class = strtolower($className);
        $class = ucwords($class);
        $class = str_replace(array(' ', '-', '+'), array('','','',''), $class);
        
        return $class;
    }
    
    public static function getFrendlyLabel ($string, $splitByUpper = false, $chartsToRemove = array('_')) {
        if ($splitByUpper)
            $string = self::splitWordsByUpperCase($string);
        $string = str_replace($chartsToRemove, ' ', $string);
        return ucwords(strtolower($string));
    }

    public static function splitWordsByUpperCase($s) {
        return trim(preg_replace('/(?=[A-Z])/', ' ', $s));
    }
    
}

?>
