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
        // NOTE: preg_match is temporary disabled
        //echo 'convDT:' . $fromTZ . ' => ' . $toTZ;
        
        if (empty($dt))
            $dt = null;//date($format);
        
        $date = false;
        $matches = false;
        
        /* set time with server TZ or specific TZ */
        if (empty($fromTZ))
            $date = new DateTime($dt);
        else {
            /* check for GMTXXX time zome */
            /*try {
                preg_match($fromTZ, '/^GMT(.*)$/', $matches);
            } catch (Exception $ex) { debug($ex); };
            */
            if (count($matches) == 2) {
                echo 'ololololol';
                $isNegative = $matches[1] < 0;
                $interval = new DateInterval('PT'.(+$isNegative).'H');
                if($isNegative)
                    $date->sub($interval);
                else
                    $date->add($interval);
            } else
                $date = new DateTime($dt, new DateTimeZone($fromTZ));
        }

        /* set target TZ */
        /* check for GMTXXX time zome */
        /*$matches = false;
        try {
            preg_match($toTZ, '/^GMT(.*)$/', $matches);
        } catch (Exception $ex) { debug($ex); };*/
        if (count($matches) == 2) {
            echo 'ololololol';
            $isNegative = $matches[1] < 0;
            $interval = new DateInterval('PT'.(+$isNegative).'H');
            if($isNegative)
                $date->sub($interval);
            else
                $date->add($interval);
        } else
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
    function debug ($value, $title = '', $argsDebug = false) {
        if (MPWS_LOG_LEVEL == 0) return;
        if (!isset($GLOBALS['MPWS_DEBUG']))
            $GLOBALS['MPWS_DEBUG'] = '';
        //return false;
        if (MPWS_ENV == 'DEV') {
            
            $_debugLine = '';
            
            if ($argsDebug) {
                $bt = debug_backtrace();
                $_value = $bt[1]['args'];
                
                $format_args = '<div><b>[DEBUG INFO] '.date('H:i:s').'</b>%s with arguments:<pre>%s</pre></div>';
                
                $debug_args = array();
                foreach ($_value as $idx => $arg) {
                    if (is_string($arg))
                        $debug_args['string'][$idx] = $arg;
                    if (is_numeric($arg))
                        $debug_args['numeric'][$idx] = $arg;
                    if (is_array($arg))
                        $debug_args['array'][$idx] = $arg;
                }
                $_debugLine = sprintf($format_args, $value . ' ' . $title, print_r($debug_args, true));
            } else {
                $format_short = '<div><b>[DEBUG INFO] '.date('H:i:s').'</b> %1$s</div>';
                $format_long = '<b>[DEBUG INFO] '.date('H:i:s').'</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> %2$s<pre>%1$s</pre></div>';
                //if (is_array($value))
                //    $value = print_r($value, true);

                if (is_array($value))
                    $_debugLine = sprintf($format_long, print_r($value, true), $title);
                else
                    $_debugLine = sprintf($format_short, $value, $title);
            }
            
            $GLOBALS['MPWS_DEBUG'] .= $_debugLine;
            
            if (MPWS_LOG_LEVEL == 2)
                echo $_debugLine;
        }
    }
    
    function valueOnEnv($variants) {
        if (isset($variants[MPWS_ENV]))
            return $variants[MPWS_ENV];
        return null;
    }
    
    function makeKey ($str, $ret = true) {
        if($ret)
            return strtoupper(trim($str));
        $str = strtoupper(trim($str));
    }
    
    //$d = 'ololololo';
    //echo makeKey('abc1----');
    //makeKey(&$d, false);
    //echo $d;
    
    function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
    
    function getArguments($func_args) {
        $fn_args = false;
        // optimize arguments
        if (count($func_args) > 1) {
            foreach ($func_args as $value) {
                if (is_string($value))
                    $fn_args[] = $value;
                if (is_object($value))
                    $fn_args[] = $value;
                if (is_array($value))
                    foreach ($value as $array_value)
                        $fn_args[] = $array_value;
            }
        } elseif (count($func_args) == 1)
            $fn_args = $func_args[0];
        else
            $fn_args = null;
        
        return $fn_args;
    }

    function glGetFirstNonEmptyValue (/* objects */) {
        $args = func_get_args();
        if (is_array($args[0]))
            $args = $args[0];
        foreach ($args as $value) {
            if (isset($value) && !empty($value))
                return $value;
        }
    }
    
    function glGetOnlyNums ($str, $includeChars = array()) {
        $_num = '';
        //echo 'OLOLO = ' . $str;
        for ($i = 0; $i < strlen($str); $i++)
            if (is_numeric ($str[$i]) || in_array($str[$i], $includeChars))
                $_num .= $str[$i];
        return $_num;
    }
    
?>
