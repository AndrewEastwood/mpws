<?php

class libraryView
{

    public static function getMacroResult ($data, $macroPath) {
        if (empty($macroPath))
            return false;
        ob_start();
        include $macroPath;
        return ob_get_clean();
    }
    
    public function getTemplateResult ($model, $templateFile) {
        if (empty($templateFile))
            return false;
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }
    
    public function getLinks ($object) {
        $result = array();
        if (is_array($object))
            foreach ($object as $target => $propKey)
                $result[] = array(
                    'NAME' => trim($propKey),
                    'TEXT' => trim($propKey),
                    'TITLE' => trim($propKey),
                    'HREF' => $target,
                    'KEY' => $propKey
                );
        elseif (is_string($object)) {
            /*
                $result = array(
                    'NAME' => trim($propKey),
                    'TEXT' => trim($propKey),
                    'TITLE' => trim($propKey),
                    'HREF' => trim($propKey)
                );*/
        }
        return $result;
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
