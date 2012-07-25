<?php

class libraryUtils {


    static public function  valueSelect ($value, $match, $valueOnMatch, $valueOnUnmatch) {
        if ($value == $match)
            return $valueOnMatch;
        return $valueOnUnmatch;
    }
    
    static public function wrapArrayKeys(&$array, $wrapper){
        $_array = array();
        foreach ($array as $k => $v)
            $_array[$wrapper.$k.$wrapper] = $v;
        $array = $_array;
    }
    
    static public function getArrayFromDBEnum ($string) {
        $matches = null;
        $returnValue = preg_match_all('/\'(.*?)\'/', $string, $matches);
        if ($returnValue == 0)
            return array();
        return $matches[1];
    }
    
    static public function arrayHtmlDump ($array, &$stringDump = '', $level = 0) {
        foreach ($array as $key => $val) {
            $stringDump .= '<div>';
            $stringDump .= str_pad('', 5 * $level, '. ');
            $stringDump .= $key . ': ';
            if (is_array($val)) {
                $stringDump .= '<br>' . PHP_EOL;
                self::arrayHtmlDump($val, $stringDump, $level + 1);
            } else
                $stringDump .= $val;
            $stringDump .= '</div>' . PHP_EOL;
        }
        
        if ($level == 0)
            return $stringDump;
        return true;
    }

    static public function groupArrayRowsByField($array, $key) {
        $_groups = array();
        foreach ($array as $rowEntry) {
           if (isset($rowEntry[$key]))
               $_groups[$rowEntry[$key]][] = $rowEntry;
           else
               $_groups['UNGROUPED'][] = $rowEntry;
        }
        return $_groups;
    }
    
    static public function getDateTimeHoursDiff ($targetDate) {
        $target_m = strtotime($targetDate);
        $diff = ($target_m - mktime());
        $toHours = 3600;
        return round($diff / $toHours, 1);
    }
    
    static public function subDateHours($date, $hours, $format = false) {
        $date_m = strtotime($date);
        $date_m -= $hours * 3600;
        if (!$format)
            return $date_m;
        return date($format, $date_m);
    }
    
    static public function generatePassword($length=9, $strength=0) {
        // src: http://www.webtoolkit.info/php-random-password-generator.html
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
    }
    
    static public function genRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = "";    
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }
    
    static public function cleanQueryArray (&$array) {
        foreach ($array as $k => $v)
            $array[$k] = self::cleanQuery($v);
        return $array;
    }
    static public function cleanQuery ($string) {
        if(get_magic_quotes_gpc()) {  // prevents duplicate backslashes
            $string = stripslashes($string);
        }
        if (phpversion() >= '4.3.0') {
            $string = mysql_real_escape_string($string);
        } else {
            $string = mysql_escape_string($string);
        }
        return $string;
    }
    
    static public function convertJSON ($data) {
        return json_decode($data);
    }
    
    static public function getJSON ($a = false) {
        
        /*
        * Convert a PHP scalar, array or hash to JS scalar/array/hash. This function is
        * an analog of json_encode(), but it can work with a non-UTF8 input and does not
        * analyze the passed data. Output format must be fully JSON compatible.
        *
        * @param mixed $a Any structure to convert to JS.
        * @return string JavaScript equivalent structure.
        *
        * This library is free software; you can redistribute it and/or
        * modify it under the terms of the GNU Lesser General Public
        * License as published by the Free Software Foundation; either
        * version 2.1 of the License, or (at your option) any later version.
        * See http://www.gnu.org/copyleft/lesser.html
        * 
        * Do not remove this comment if you want to use the script!
        * �� �������� ������ �����������, ���� �� ������ ������������ ������!
        *
        * This backend library also supports POST requests additionally to GET.
        *
        * @author Dmitry Koterov
        * @version 5.x $Id$
        * 
        */
        
        if (is_null($a))
            return 'null';
        if ($a === false)
            return 'false';
        if ($a === true)
            return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use "." for floats.
                $a = str_replace(",", ".", strval($a));
            }
            // All scalars are converted to strings to avoid indeterminism.
            // PHP's "1" and 1 are equal for all PHP operators, but
            // JS's "1" and 1 are not. So if we pass "1" or 1 from the PHP backend,
            // we should get the same result in the JS frontend (string).
            // Character replacements for JSON.
            static $jsonReplaces = array(
                array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
                array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
            );

            return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a).'"';
        }

        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a))
        {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }

        $result = array();
        if ($isList)
        {
            foreach ($a as $v)
                $result[] = self::getJSON($v);

            return '['.join(',', $result).']';
        }
        else
        {
            foreach ($a as $k => $v)
                $result[] = self::getJSON($k).':'.self::getJSON($v);

            return '{'.join(',', $result).'}';
        }
    }

}

?>
