<?php

    function arrExtend (&$arrSrc, $arrExt) {
        if (empty($arrSrc)) {
            $arrSrc = $arrExt;
            return;
        }
        $arrSrc = array_merge($arrSrc, $arrExt);
    }

    function convDT($dt,  $toTZ, $fromTZ = false, $format = 'Y-m-d H:i:s') {
        
        if (empty($dt))
            $dt = date($format);
        
        $date = false;
        
        /* set time with server TZ or specific TZ */
        if (empty($fromTZ))
            $date = new DateTime($dt);
        else
            $date = new DateTime($dt, new DateTimeZone($fromTZ));
        
        /* set target TZ */
        $date->setTimezone(new DateTimeZone($toTZ));
        
        return $date->format($format);
    }

    function getGreenwichTime ($dateTimeString, $format = 'Y-m-d H:i:s') {
        /* Server Time */
        $date = new DateTime($dateTimeString);
        
        /* London Time */
        $date->setTimezone(new DateTimeZone('GMT0'));
        //echo 'Server Time: ' . $date->format('Y-m-d H:i:sP') . '<br>';
        return $date->format($format);
    }

    function serverTime ($timeString, $userTimeZone) {

        /* Server Time */
        $date = new DateTime($timeString);
        echo 'Server Time: ' . $date->format('Y-m-d H:i:sP') . '<br>';
        
        /* London Time */
        $date->setTimezone(new DateTimeZone('GMT0'));
        echo 'London Time: ' . $date->format('Y-m-d H:i:sP') . '<br>';
        
        /* User Time */
        $date->setTimezone(new DateTimeZone($userTimeZone));
        echo $userTimeZone . ' Time: ' . $date->format('Y-m-d H:i:sP') . '<br>';
    }


    // global methods
    // will be moved to 
    function debug ($value) {
        return false;
        if (MPWS_ENV == 'DEV') {
            $format_short = '<div><b>[DEBUG INFO] '.date('H:i:s').'</b>%s</div>';
            $format_long = '<h5>[DEBUG INFO] '.date('H:i:s').'</h5><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";><pre>%s</pre></div>';
            if (is_array($value))
                $value = print_r($value, true);

            echo sprintf(strlen($value) > 50?$format_long:$format_short, $value);
        }
    }

    function IFLAST ($array, $item, $onlast, $default = '') {
        if (end($array) == $item)
            return $onlast;
        return $default;
    }
    
    // Змінює GET параметри.
    // $key - новий або існуючий ключ.
    // $value - нове значення для ключа.
    // $deletedKeys - масив параметрів, які будуть видалені.
    // Повертає стрічку GET з зміненим або доданим параметром ?...&$key=$value&.
    function SetGET($key = '', $value = '', $deletedKeys = Array(), $prefix = '?', $fullurl = false)
    {
        $get = $prefix;
        $b = false;
        foreach($_GET as $id => $val) {
            if (in_array($id, $deletedKeys))
                continue;
            if ($id == $key) {
                $v = urlencode($value);
                $b = true;
            }
            else
                $v = $val;
            $get .= $id.'='.urlencode($v).'&amp;';
        }
        if ($b === false && $key !== '')
            $get .= $key.'='.urlencode($value).'&amp;';

        if ($fullurl)
            $get = $_SERVER['PHP_SELF'] . '?' . $get;
            
        return $get;
    }
    
    function SetGetAndSeo ($key = '', $value = '', $deletedKeys = Array()) {
        
        // explode into request file and search 
        $request = explode('?', $_SERVER['REQUEST_URI']);
        $_get = array();
        $_getNew = array();
        $updated = false;
        
        if (!empty($request[1]))
            $_get = explode('&', $request[1]);

        foreach ($_get as $idx => $git) {
            $_binGet = explode('=', $git);
            
            if ($_binGet[0] == $key) {
                $_binGet[1] = $value;
                $updated = true;
            }
            if (!in_array($_binGet[0], $deletedKeys))
                $_getNew[] = $_binGet[0] . '=' . $_binGet[1];
        }
        // force add requested argument
        if (!$updated)
            $_getNew[] = $key . '=' . $value;
        
        //$path = implode('/', $request[0]);
        $query = implode('&amp;', $_getNew);

        

        
        return $request[0] . '?' . $query;
    }
    
    function GetSeoValue ($section, $skip = 0) {

        $request = explode('?', $_SERVER['REQUEST_URI']);

        // search for section in request path
        $items = explode('/', $request[0]);
        $value = '';
        $reached = false;
        $skipped = -1; // -1 include section name
        foreach($items as $item) {
            if (empty($item))
                continue;
            if ($item == $section)
                $reached = true;
            if ($reached) {
                if($skipped >= $skip) {
                    $value = $item;
                    break;
                }
                $skipped++;
            }
        }
        
        // search for section in request query
        if(!empty($request[1])) {
            $_get = explode('&', $request[1]);
            foreach ($_get as $item) {
                $kv = explode('=', $item);
                if ($section == $kv[0]) {
                    $value = $kv[1];
                    break;
                }
            
            }
        
        }

        return $value;
    }
    
    function GetSeoPath ($section, $grabAfter = 1) {
    
        $items = explode('/', $_SERVER['REQUEST_URI']);
        
        //var_dump($items);
        
        $path = false;
        $reached = false;
        $grabbed = -1; // -1 include section name
        foreach($items as $item) {
            if (empty($item))
                continue;
            if ($item == $section)
                $reached = true;
            if ($reached) {
                $path .= DS . $item;
                $grabbed++;
                if ($grabbed >= $grabAfter)
                    break;
            }
        }
        
        return $path;
        
    }
    
    function GET($key, $seo = false) {
        if (!empty($seo))
            return GetSeoValue($seo);
        return isset($_GET[$key])?$_GET[$key]:null;
    }
    
    function SetSeoCondtitionalValue ($key, $value, $onMatch, $onUnmatch = false) {
        if (GetSeoValue($key) == $value)
            return $onMatch;
        return $onUnmatch;
    }
    
    function SetCondtitionalValue ($key, $value, $onMatch, $onUnmatch = false, $seo = false) {
        if ($seo)
            return SetSeoCondtitionalValue($key, $value, $onMatch, $onUnmatch);
        if (isset($_GET[$key]) && $_GET[$key] == $value)
            return $onMatch;
        return $onUnmatch;
    }

?>
