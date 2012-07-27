<?php


    /**    Returns the offset from the origin timezone to the remote timezone, in seconds.
    *    @param $remote_tz;
    *    @param $origin_tz; If null the servers current timezone is used as the origin.
    *    @return int;
    */
    function get_timezone_offset($remote_tz, $origin_tz = null) {
        if($origin_tz === null) {
            if(!is_string($origin_tz = date_default_timezone_get())) {
                return false; // A UTC timestamp was returned -- bail out!
            }
        }
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        return $offset;
    }


    function toUserTime ($timezone, $dateTime = false, $format = 'Y-m-d H:i:s') {
        if (empty($dateTime))
            return date($format, time() + ($timezone * 3600 ));
        return date($format, strtotime($dateTime) + ($timezone * 3600 ));
    }
    
    function toServerTime ($dateTime, $format = 'Y-m-d H:i:s') {
        return date($format, strtotime($dateTime) + date('Z'));
    }
    
    function timeInfo ($userTimeZone, $userTime = false, $serverTime = false, $format = 'Y-m-d H:i:s') {
        $serverTimeZone = date('Z') / 3600;
        
        /* SERVER INFO */
        $server = array();
        $server['NOW'] = date($format);
        $server['OFFSET'] = $serverTimeZone;
        if ($serverTimeZone == 0)
            $server['GMT'] = 'Etc/GMT';
        elseif ($serverTimeZone > 0)
            $server['GMT'] = 'Etc/GMT+' . $serverTimeZone;
        else
            $server['GMT'] = 'Etc/GMT' . $serverTimeZone;
        //$server['IN_USER'] = toUserTime($userTimeZone, $server['NOW']);
        
        /* USER */
        $user = array();
        $user['NOW'] = toUserTime($userTimeZone, date($format));
        $user['OFFSET'] = $userTimeZone;
        if ($userTimeZone == 0)
            $user['GMT'] = 'Etc/GMT';
        elseif ($userTimeZone > 0)
            $user['GMT'] = 'Etc/GMT+' . $userTimeZone;
        else
            $user['GMT'] = 'Etc/GMT' . $userTimeZone;
        //$user['IN_SERVER'] = toServerTime($user['NOW']);
        
        
        
        
        /* REQUESTED TIME */
        if (!empty($userTime)) {
            $req['USER'] = $userTime;
            $req['USER_TO_SERVER'] = toServerTime($userTime);
        }
        if (!empty($serverTime)) {
            $req['SERVER'] = $serverTime;
            $req['SERVER_TO_USER'] = toUserTime($userTimeZone, $serverTime);
        }
        
        $req['TZ'] = get_timezone_offset($user['GMT'], $server['GMT']);

        return array(
            'SERVER' => $server,
            'USER' => $user,
            'INFO' => $req
        );
    }

/*
    // get server time by user
    // austin -6   <--- user is here
    // kiev +2     <--- server is here
    // diff 8 hours
    function stime ($userDateTimeString, $userTimezone) {
        $stime = array();
        
        $userTimezone *= 3600;
        
        /* CURRENT TIME* /
        $utime['C_SERVER'] = date('Y-m-d H:i:s');
        $utime['C_USER'] = date('Y-m-d H:i:s', time() + $userTimezone);
        
        /* OFFSET * /
        $utime['SEVER_OFFSET'] = date('Z') / 3600;
        $utime['USER_OFFSET'] = $userTimezone / 3600;
        
        /* PROVIDED TIME  * /
        $utime['USER'] = date('Y-m-d H:i:s', strtotime($serverDateTimeString) + $userTimezone);
        $utime['SERVER'] = $serverDateTimeString;
        
        return $stime;
    }

    // get user time by server
    function utime ($userTimezone) {
        $utime = array();
        
        $userTimezone *= 3600;
        
        
        
        
        /* DIFF * /
        $utime['DIFF'] = (date('Z') / 3600) - $userTimezone / 3600;
        
        /* GMT * /
        $utime['U_GMT'] = 'GMT ' . $userTimezone / 3600;
        $utime['S_GMT'] = 'GMT ' . $utime['SEVER_OFFSET'];
        
        
        return $utime;
    }*/



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
