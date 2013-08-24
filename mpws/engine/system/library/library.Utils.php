<?php

class libraryUtils {

    // ORIGIN: http://stackoverflow.com/a/2313270
    static public function crawl_page($url, $filter = array(), &$seen = array(), $depth = 5)
    {
        //static $seen = array();
        if ($depth === 0)
            return;
        //echo '<br> VISITING: ' . $url;
        $seen[$url] = true;
        $dom = new DOMDocument('1.0');
        
        $pg = libraryRequest::getURL($url);
        //var_dump($pg);
        @$dom->loadHTML($pg['content']);
        
        //@$dom->loadHTMLFile($url);
        
        
        //echo '<br> VISITING: ' . $url;
        $anchors = $dom->getElementsByTagName('a');
        
        //var_dump($dom);
        
        foreach ($anchors as $element) {
            $href = $element->getAttribute('href');
            
            
            //echo '<hr size="2">' . $href . '<hr size="2"><hr size="2">';
            
            if ($href[0] === '#')
                continue;
            $owner_parts = parse_url($url);
            $link_parts = parse_url($href);
            // allow http only
            if (!empty($link_parts['scheme']) && $link_parts['scheme'] !== 'http')
                continue;
            // skip reading link with different host
            if (isset($owner_parts['host']) &&
                isset($link_parts['host']) &&
                $owner_parts['host'] != $link_parts['host'])
                continue;
            /***** custom filtering ****/
            if (!empty($link_parts['query'])) {
                parse_str($link_parts['query'], $link_query);
                $isOK = true;
                foreach ($filter as $key => $val) {
                    if ($link_query[$key] == $val) {
                        $isOK = false;
                        break;
                    }
                }
                if (!$isOK)
                    continue;
            }
            //echo '<pre>' . print_r($owner_parts, true) . '</pre>';
            //echo '<br>'.$href.'<pre>' . print_r($link_parts, true) . '</pre><hr size="2">';
            $urlToVisit = $link_parts;
            // adding scheme
            if (empty($link_parts['scheme']))
                $urlToVisit['scheme'] = 'http';
            // adding host
            if (empty($link_parts['host']))
                $urlToVisit['host'] = $owner_parts['host'];
            // adding path
            if (empty($link_parts['path']))
                $urlToVisit['path'] = $owner_parts['path'];
            // remove fragment
            //if (isset($link_parts['fragment']))
            //    unlink($urlToVisit['fragment']);
            // combine url
            $linkToPage = $urlToVisit['scheme'] . '://';
            $linkToPage .= $urlToVisit['host'];
            $linkToPage .= $urlToVisit['path'];
            if (!empty($urlToVisit['query']))
                $linkToPage .= '?' . $urlToVisit['query'];
            //echo '<hr size="2">' . print_r($urlToVisit, true) . '</pre><hr size="2"><hr size="2">';
            //echo '<hr size="2">' . $linkToPage . '<hr size="2"><hr size="2">';
            // skip already visited page
            if (isset($seen[$linkToPage]))
                continue;
            self::crawl_page($linkToPage, $filter, $seen, $depth - 1);
        }
        //echo "URL:",$url,PHP_EOL,"<br>";//,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;
        return $seen;
    }

    static public function createDateRangeArray($strDateFrom, $strDateTo) {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script
        $aryRange = array();
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }
    
    /**
     * Get value an array by using "root.branch.leaf" notation
     *
     * @param string $value  Array value
     * @param string $path   Path to a specific option to extract
     * @param mixed $default Value to use if the path was not found
     * @return mixed
     */
    static public function getPathValue($value, $path, $default = null) {
        // from http://codeaid.net/php/get-values-of-multi-dimensional-arrays-using-xpath-notation
        // modified: replaced "$this->_pathDelimiter" with "."


        // fail if the path is empty
        if (empty($path) || empty($value)) {
            throw new Exception('Path or value cannot be empty');
        }

        // remove all leading and trailing slashes
        $path = trim($path, '.');

        // extract parts of the path
        $parts = explode('.', $path);

        // loop through each part and extract its value
        foreach ($parts as $part) {
            if (isset($value[$part])) {
                // replace current value with the child
                $value = $value[$part];
            } else {
                // key doesn't exist, fail
                return $default;
            }
        }

        return $value;

    }
    
    static public function getWithEOL ($text, $unstrip = false) {
        if ($unstrip)
            return stripcslashes(self::getWithEOL($text));
        return str_replace(array('\r\n', '\n', '\r'), PHP_EOL, $text);
    }

    static public function  valueSelect ($value, $match, $valueOnMatch, $valueOnUnmatch) {
        if ($value == $match)
            return $valueOnMatch;
        return $valueOnUnmatch;
    }
    
    static public function filterArrayKeys ($array, $pattern) {
        $keys = array_keys($array);
        $filtered = array();
        for ($i = 0; ($k = $keys[$i]); $i++)
            if (preg_match($pattern, $k))
                $filtered[$k] = $array[$k];
        return $filtered;
    }
    
    static public function wrapArrayKeys(&$array, $wrapper){
        $_array = array();
        foreach ($array as $k => $v)
            $_array[$wrapper.$k.$wrapper] = $v;
        $array = $_array;
    }
    
    static public function convertDBDataToMap($dbdata, $key, $value) {
        $map = array();
        foreach ($dbdata as $row) {
            if (isset($row[$key]) && isset($row[$value]))
                $map[$row[$key]] = $row[$value];
        }
        return $map;
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
    
    static public function htmlValues(&$array) {
        foreach ($array as &$item)
            $item = htmlentities($item, ENT_QUOTES);
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
    
    static public function dtFormatSwitch($datetime, $to24h, $format = false) {
        if (empty($format)) 
            $format = 'Y-m-d ' . (($to24h)?' H:i:s':' h:i:s A');
        else {
            if ($to24h) {
                // replace 'H' to 'h'
                if (strpos($format, 'H') >= 0)
                    $format = str_replace('H', 'h', $format);
                if (strpos($format, 'A') < 0)
                    $format .= ' A';
            } else {
                // replace 'h' to 'H'
                if (strpos($format, 'h') >= 0)
                    $format = str_replace('h', 'H', $format);
                // remove AM\PM format
                $format = str_replace(array('A', 'a'), '', $format);
            }
        }
            
        return date($format, strtotime($datetime));
    }
    
    static public function getDateTimeHoursDiff ($targetDate, $startDate = false) {
        $target_m = strtotime($targetDate);
        if (empty($startDate))
            $diff = ($target_m - mktime());
        else
            $diff = ($target_m - strtotime($startDate));
        $toHours = 3600;
        return round($diff / $toHours, 1);
    }
    
    static public function formatOffset($offset) {
        $hours = $offset / 3600;
        $remainder = $offset % 3600;
        $sign = $hours > 0 ? '+' : '-';
        $hour = (int) abs($hours);
        $minutes = (int) abs($remainder / 60);

        if ($hour == 0 AND $minutes == 0) {
            $sign = ' ';
        }
        return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');
    }
    
    static public function getTimeZones ($useDefined = false) {
        
        $timezones = array();
        
        if ($useDefined) {
            // create an array listing the time zones
            // source ahs been taken from the
            // oringin page: http://www.ultramegatech.com/2009/04/working-with-time-zones-in-php/
            // additional resource: http://redmine.samo.ru/timezone/?offset=10800
            $zonelist = array('Kwajalein' => '(GMT-12:00) Eniwetok, Kwajalein:',
                'Pacific/Midway' => '(GMT-11:00) Midway Island, Samoa:',
                //'Pacific/Samoa' => '(GMT-11:00) Samoa',
                'Pacific/Honolulu' => '(GMT-10:00) Hawaii:',
                'America/Anchorage' => '(GMT-09:00) Alaska:',
                'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US &amp; Canada):',
                //'America/Tijuana' => '(GMT-08:00) Tijuana, Baja California',
                'America/Denver' => '(GMT-07:00) Mountain Time (US &amp; Canada):',
                //'America/Chihuahua' => '(GMT-07:00) Chihuahua',
                //'America/Mazatlan' => '(GMT-07:00) Mazatlan',
                //'America/Phoenix' => '(GMT-07:00) Arizona',
                //'America/Regina' => '(GMT-06:00) Saskatchewan',
                //'America/Tegucigalpa' => '(GMT-06:00) Central America',
                'America/Chicago' => '(GMT-06:00) Central Time (US &amp; Canada), Mexico City:',
                //'America/Mexico_City' => '(GMT-06:00) Mexico City',
                //'America/Monterrey' => '(GMT-06:00) Monterrey',
                'America/New_York' => '(GMT-05:00) Eastern Time (US &amp; Canada), Bogota, Lima:',
                //'America/Bogota' => '(GMT-05:00) Bogota',
                //'America/Lima' => '(GMT-05:00) Lima',
                //'America/Rio_Branco' => '(GMT-05:00) Rio Branco',
                //'America/Indiana/Indianapolis' => '(GMT-05:00) Indiana (East)',
                //'America/Caracas' => '(GMT-04:30) Caracas',
                'America/Halifax' => '(GMT-04:00) Atlantic Time (Canada), Caracas, La Paz:',
                //'America/Manaus' => '(GMT-04:00) Manaus',
                //'America/Santiago' => '(GMT-04:00) Santiago',
                //'America/La_Paz' => '(GMT-04:00) La Paz',
                'America/St_Johns' => '(GMT-03:30) Newfoundland:',
                'America/Argentina/Buenos_Aires' => '(GMT-03:00) Brazil, Buenos Aires, Georgetown:',
                //'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
                //'America/Godthab' => '(GMT-03:00) Greenland',
                //'America/Montevideo' => '(GMT-03:00) Montevideo',
                'Atlantic/South_Georgia' => '(GMT-02:00) Mid-Atlantic:',
                'Atlantic/Azores' => '(GMT-01:00) Azores, Cape Verde Islands:',
                //'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
                'Europe/Dublin' => '(GMT) Western Europe Time, London, Lisbon, Casablanca:', // added
                //'Europe/Dublin' => '(GMT) Dublin',
                //'Europe/Lisbon' => '(GMT) Lisbon',
                //'Europe/London' => '(GMT) London',
                //'Africa/Monrovia' => '(GMT) Monrovia',
                //'Atlantic/Reykjavik' => '(GMT) Reykjavik',
                //'Africa/Casablanca' => '(GMT) Casablanca',
                'Europe/Madrid' => '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris:', // added
                //'Europe/Belgrade' => '(GMT+01:00) Belgrade',
                //'Europe/Bratislava' => '(GMT+01:00) Bratislava',
                //'Europe/Budapest' => '(GMT+01:00) Budapest',
                //'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
                //'Europe/Prague' => '(GMT+01:00) Prague',
                //'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
                //'Europe/Skopje' => '(GMT+01:00) Skopje',
                //'Europe/Warsaw' => '(GMT+01:00) Warsaw',
                //'Europe/Zagreb' => '(GMT+01:00) Zagreb',
                //'Europe/Brussels' => '(GMT+01:00) Brussels',
                //'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
                //'Europe/Madrid' => '(GMT+01:00) Madrid',
                //'Europe/Paris' => '(GMT+01:00) Paris',
                //'Africa/Algiers' => '(GMT+01:00) West Central Africa',
                //'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
                //'Europe/Berlin' => '(GMT+01:00) Berlin',
                //'Europe/Rome' => '(GMT+01:00) Rome',
                //'Europe/Stockholm' => '(GMT+01:00) Stockholm',
                //'Europe/Vienna' => '(GMT+01:00) Vienna',
                'Europe/Kaliningrad' => '(GMT+02:00) Kaliningrad, South Africa:',
                //'Europe/Minsk' => '(GMT+02:00) Minsk',
                //'Africa/Cairo' => '(GMT+02:00) Cairo',
                //'Europe/Helsinki' => '(GMT+02:00) Helsinki',
                //'Europe/Riga' => '(GMT+02:00) Riga',
                //'Europe/Sofia' => '(GMT+02:00) Sofia',
                //'Europe/Tallinn' => '(GMT+02:00) Tallinn',
                //'Europe/Vilnius' => '(GMT+02:00) Vilnius',
                //'Europe/Athens' => '(GMT+02:00) Athens',
                //'Europe/Bucharest' => '(GMT+02:00) Bucharest',
                //'Europe/Istanbul' => '(GMT+02:00) Istanbul',
                //'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
                //'Asia/Amman' => '(GMT+02:00) Amman',
                //'Asia/Beirut' => '(GMT+02:00) Beirut',
                //'Africa/Windhoek' => '(GMT+02:00) Windhoek',
                //'Africa/Harare' => '(GMT+02:00) Harare',
                //'Asia/Kuwait' => '(GMT+03:00) Kuwait',
                //'Asia/Riyadh' => '(GMT+03:00) Riyadh',
                //'Asia/Baghdad' => '(GMT+03:00) Baghdad',
                //'Africa/Nairobi' => '(GMT+03:00) Nairobi',
                //'Asia/Tbilisi' => '(GMT+03:00) Tbilisi',
                'Europe/Moscow' => '(GMT+03:00) Baghdad, Riyadh, Moscow, St. Petersburg:',
                //'Europe/Volgograd' => '(GMT+03:00) Volgograd',
                'Asia/Tehran' => '(GMT+03:30) Tehran:',
                //'Asia/Muscat' => '(GMT+04:00) Muscat',
                'Asia/Baku' => '(GMT+04:00) Abu Dhabi, Muscat, Baku, Tbilisi:',
                'Asia/Kabul' => '(GMT+04:30) Kabul:',
                //'Asia/Yerevan' => '(GMT+04:00) Yerevan',
                //'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
                //'Asia/Karachi' => '(GMT+05:00) Karachi',
                'Asia/Tashkent' => '(GMT+05:00) Ekaterinburg, Islamabad, Karachi, Tashkent:',
                'Asia/Kolkata' => '(GMT+05:30) Bombay, Calcutta, Madras, New Delhi:',
                //'Asia/Colombo' => '(GMT+05:30) Sri Jayawardenepura',
                'Asia/Katmandu' => '(GMT+05:45) Kathmandu:',
                'Asia/Dhaka' => '(GMT+06:00) Almaty, Dhaka, Colombo:',
                //'Asia/Almaty' => '(GMT+06:00) Almaty',
                //'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
                //'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
                //'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
                'Asia/Bangkok' => '(GMT+07:00) Bangkok, Hanoi, Jakarta:',
                //'Asia/Jakarta' => '(GMT+07:00) Jakarta',
                //'Asia/Brunei' => '(GMT+08:00) Beijing',
                //'Asia/Chongqing' => '(GMT+08:00) Chongqing',
                'Asia/Hong_Kong' => '(GMT+08:00) Beijing, Perth, Singapore, Hong Kong:',
                //'Asia/Urumqi' => '(GMT+08:00) Urumqi',
                //'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
                //'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
                //'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
                //'Asia/Singapore' => '(GMT+08:00) Singapore',
                //'Asia/Taipei' => '(GMT+08:00) Taipei',
                //'Australia/Perth' => '(GMT+08:00) Perth',
                //'Asia/Seoul' => '(GMT+09:00) Seoul',
                'Asia/Tokyo' => '(GMT+09:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk:',
                //'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
                //'Australia/Darwin' => '(GMT+09:30) Darwin',
                'Australia/Adelaide' => '(GMT+09:30) Adelaide, Darwin:',
                //'Australia/Canberra' => '(GMT+10:00) Canberra',
                //'Australia/Melbourne' => '(GMT+10:00) Melbourne',
                //'Australia/Sydney' => '(GMT+10:00) Sydney',
                //'Australia/Brisbane' => '(GMT+10:00) Brisbane',
                //'Australia/Hobart' => '(GMT+10:00) Hobart',
                //'Asia/Vladivostok' => '(GMT+10:00) Eastern Australia, Guam, Vladivostok:',
                'Pacific/Guam' => '(GMT+10:00) Eastern Australia, Guam, Vladivostok:',
                //'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
                'Asia/Magadan' => '(GMT+11:00) Magadan, Solomon Islands, New Caledonia:',
                //'Pacific/Fiji' => '(GMT+12:00) Fiji',
                'Asia/Kamchatka' => '(GMT+12:00) Auckland, Wellington, Fiji, Kamchatka'
                //'Pacific/Auckland' => '(GMT+12:00) Auckland'
                );
            
            // transform zones to native format
            foreach ($zonelist as $zoneAlias => $zoneTitle)
                $timezones[] = array(
                    'key' => $zoneAlias,
                    'title' => $zoneTitle);
        } else {
            // get all available time zones
            $utc = new DateTimeZone('UTC');
            $dt = new DateTime('now', $utc);
            foreach(DateTimeZone::listIdentifiers() as $tz) {
                $current_tz = new DateTimeZone($tz);
                $offset =  $current_tz->getOffset($dt);
                $transition =  $current_tz->getTransitions($dt->getTimestamp(), $dt->getTimestamp());
                $abbr = $transition[0]['abbr'];
                $timezones[] = array(
                    'key' => $tz,
                    'title' => $tz. ' [' .$abbr. ' '. self::formatOffset($offset). ']');
            }
        }
        
        return $timezones;
    }
    
    static public function getTimeZoneTitle ($timeZoneAliasName) {
        $tzs = self::getTimeZones(true);
        foreach ($tzs as $tzEntry)
            if ($tzEntry['key'] == $timeZoneAliasName)
                return $tzEntry['title'];
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

    static public function convValue ($value) {
        // number
        if (is_numeric($value))
            return +$value;
        
        // boolean type
        if (strcasecmp($value, 'true') === 0)
            return true;
        if (strcasecmp($value, 'false') === 0)
            return false;

        // array/object
        if (startsWith($value, '[') && endsWith($value, ']')) {
            return str_getcsv(substr($value, 1, strlen($value) - 2));
        }

        if (startsWith($value, '{') && endsWith($value, '}')) {
            return json_decode($value, true);
        }

        // default (string)
        return $value;
    }

    static public function getDebugInfo () {
        return isset($GLOBALS['MPWS_DEBUG'])?$GLOBALS['MPWS_DEBUG']:false;
    }

    static public function getBroserType() {
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||
        preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent,0,4)))
            return "MOBILE";
    }    

    static public function getArrayValueByPath ($path, $array) {
        if (empty($path))
            return;
        
        if (is_string($path))
            $path = explode('.', $path);
        
        //echo '<br>';
        //var_dump($path);
        //echo '<br>-------------------------------------<br>';
        
        $index = 0;
        foreach ($path as $lookingKey) {
            $index++;
            if (isset($array[$lookingKey])) {
                
                // check if it is last key
                if ($index == count($path))
                    return $array[$lookingKey];
                
                if (is_array($array[$lookingKey])) {
                    array_shift($path);
                    return self::getArrayValueByPath($path, $array[$lookingKey]);
                }
            }
        }
        
        return null;
    }
    
    /**
     * source: http://www.ermshaus.org/2011/04/php-circular-shift-array
     * Circularly shifts an array
     *
     * Shifts to right for $steps > 0. Shifts to left for $steps < 0. Keys are
     * preserved.
     *
     * @param  array $array Array to shift
     * @param  int   $steps Steps to shift array by
     * @return array Resulting array
     */
    static public function array_shift_circular(array $array, $steps = 1)
    {
        if (!is_int($steps)) {
            throw new InvalidArgumentException(
                    'steps has to be an (int)');
        }

        if ($steps === 0) {
            return $array;
        }

        $l = count($array);

        if ($l === 0) {
            return $array;
        }
        
        $steps = $steps % $l;
        $steps *= -1;

        return array_merge(array_slice($array, $steps),
                           array_slice($array, 0, $steps));
    }
    
}

?>
