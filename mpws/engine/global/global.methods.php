<?php

    function arrExtend (&$arrSrc, $arrExt) {
        if (empty($arrSrc)) {
            $arrSrc = $arrExt;
            return;
        }
        $arrSrc = array_merge($arrSrc, $arrExt);
        return $arrSrc;
    }
    
    function extendParent ($arrSrc, $arrExt) {
        if (empty($arrSrc)) {
            $arrSrc = $arrExt;
            return;
        }
        return array_merge_recursive($arrSrc, $arrExt);;
    }
    
    function getValue ($value, $default = '') {
        if (isset($value))
            return $value;
        return $default;
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

?>
