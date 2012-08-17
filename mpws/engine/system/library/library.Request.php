<?php


class libraryRequest {

    /* get values */
    static function getDisplay($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('display', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPage ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('page', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getAction ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('action', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getApiFn ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('fn', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getApiParam ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        $param = urldecode(self::getValue('p', $defaultValue, $switch, $valueOnSwitch));
        //var_dump($param);
        parse_str($param, $param);
        if (empty($param))
            return $defaultValue;

        $param = libraryUtils::cleanQueryArray($param);
        
        // if single parameter
        // p=sometext
        // return 'sometext'
        $c = current($param);
        if (count($param) == 1 && empty($c))
            return $c;
        return $param;
    }
    static function getOID ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('oid', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getValue($key, $defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::value($_GET, $key, $defaultValue, $switch, $valueOnSwitch);
    }

    /* get post values */
    static function getPostDisplay($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getPostValue('display', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPostPage ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getPostValue('page', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPostAction ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getPostValue('action', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPostValue($key, $defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::value($_POST, $key, $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPostFormAction () {
        return self::value($_POST, 'do');
    }
    static function isPostFormAction ($equalsToThisValue) { 
        $do = self::value($_POST, 'do');
        //echo '<br>isPostFormAction === ' . $equalsToThisValue . ' == ' . $do;
        //echo ' equals: ' . ($do === strtolower($equalsToThisValue)?1:0);
        return strtolower($do) === strtolower($equalsToThisValue);
    }
    static function isPostFormActionMatchAny (/* looking for any action */) {
        // we'll return tru if there are some action that you're looking for'
        $num = func_num_args();
        $do = self::value($_POST, 'do');
        for ($i = 0; $i < $num; $i++)
            if (strcasecmp($do, func_get_arg($i)) === 0)
                return true;
        return false;
    }
    static function isPostFormActionMatchAll (/* looking for any action */) {
        // we'll return tru if there are some action that you're looking for'
        $num = func_num_args();
        $do = self::value($_POST, 'do');
        for ($i = 0; $i < $num; $i++)
            if (strcasecmp($do, func_get_arg($i)) !== 0)
                return false;
        return true;
    }

    static function getPostContainer (/* required fields */) {
        $container = array();
        if (!empty($args)) {
            $num = func_num_args();
            for ($i = 0; $i < $num; $i++)
                $container[func_get_arg($i)] = self::getPostValue(func_get_arg($i));
        }
        return $container;
    }
    static function getPostMapContainer ($map) {
        $container = array();
        if (!empty($map)) {
            foreach ($map as $containerKey => $postKey)
                if (empty($postKey))
                    $container[$containerKey] = '';
                else
                    $container[$containerKey] = self::getPostValue($postKey);
        }
        return $container;
    }

    static function storeOrGetRefererUrl ($store = true, $scope = '') {

        if(empty($scope))
            $scope = self::getDisplay();

        //var_dump(parse_url($_SERVER['HTTP_REFERER']));
        if ($store) {
            $_SESSION['MPWS_STORED_URL'.$scope] = $_SERVER['REQUEST_URI'];
        } else {
            $_data = parse_url($_SESSION['MPWS_STORED_URL'.$scope]);
            return $_data['path'] . '?' . $_data['query'];
        }
    }

    static function getNewUrl($key = '', $value = '', $remove = array('page', 'action')) {
        $_data = array();
        parse_str($_SERVER['QUERY_STRING'], $_data);
        
        if (!empty($key))
            $_data[$key] = $value;

        // remove hidden keys

        if(is_array($remove))
            foreach ($remove as $keyToRemove)
                unset($_data[$keyToRemove]);
        elseif (is_string($remove))
            unset($_data[$remove]);
        //unset($_data['action']);
        //$str = http_build_query($_data);
        //var_dump($str);

        return http_build_query($_data);
    }

    /* common */
    static function value($method, $key, $defaultValue = null, $switch = null, $valueOnSwitch = null) {
        if ($switch)
            return $valueOnSwitch;
        else {
            if (isset($method[$key])) {
                return $method[$key];
            } else
                return $defaultValue;
        }
    }
    
    public static function getOrValidatePageSecurityToken($keyToValidate = '') {
        if (!empty($keyToValidate))
            return $keyToValidate === self::getOrValidatePageSecurityToken();
        
        // make token
        $p = libraryRequest::getPage('undefined');
        $phash = md5($p);
        return $phash;
    }

    
    public static function postRedirect ($values, $host, $action) {
        $post_data = '';
        foreach($values as $key => $value)
            $post_data .= ($key . '=' . $value . '&');
        $content_length = strlen($post_data);
        header('POST ' . $action . ' HTTP/1.1');
        header('Host: ' . $host);
        header('Connection: close');
        header('Content-type: application/x-www-form-urlencoded');
        header('Content-length: ' . $content_length);
        header('');
        header($post_data);
    }
    
    public static function locationRedirect($values, $location) {
        $post_data = '';
        foreach($values as $key => $value)
            $post_data .= ($key . '=' . $value . '&');
        header('Location: ' . $location . '?' . $post_data);
    }
 
    /*
     * Original post: http://www.php.net/manual/en/function.fsockopen.php#101872
     * By: Jeremy Saintot
     * MPWS:
     *  1. renamed to doHttpRequest from http_request
     *  2. commented user agent adding line
     */
    public static function doHttpRequest ( 
        $verb = 'GET',             /* HTTP Request Method (GET and POST supported) */ 
        $ip,                       /* Target IP/Hostname */ 
        $port = 80,                /* Target TCP port */ 
        $uri = '/',                /* Target URI */ 
        $getdata = array(),        /* HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
        $postdata = array(),       /* HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
        $cookie = array(),         /* HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
        $custom_headers = array(), /* Custom HTTP headers ie. array('Referer: http://localhost/ */ 
        $timeout = 1,           /* Socket timeout in seconds */ 
        $req_hdr = false,          /* Include HTTP request headers */ 
        $res_hdr = false           /* Include HTTP response headers */ 
        ) 
    { 
        $ret = ''; 
        $verb = strtoupper($verb); 
        $cookie_str = ''; 
        $getdata_str = count($getdata) ? '?' : ''; 
        $postdata_str = ''; 

        foreach ($getdata as $k => $v) 
                    $getdata_str .= urlencode($k) .'='. urlencode($v) . '&'; 

        foreach ($postdata as $k => $v) 
            $postdata_str .= urlencode($k) .'='. urlencode($v) .'&'; 

        foreach ($cookie as $k => $v) 
            $cookie_str .= urlencode($k) .'='. urlencode($v) .'; '; 

        $crlf = "\r\n"; 
        $req = $verb .' '. $uri . $getdata_str .' HTTP/1.1' . $crlf; 
        $req .= 'Host: '. $ip . $crlf; 
        // mpws removed: $req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf; 
        $req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf; 
        $req .= 'Accept-Language: en-us,en;q=0.5' . $crlf; 
        $req .= 'Accept-Encoding: deflate' . $crlf; 
        $req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf; 

        foreach ($custom_headers as $k => $v) 
            $req .= $k .': '. $v . $crlf; 

        if (!empty($cookie_str)) 
            $req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf; 

        if ($verb == 'POST' && !empty($postdata_str)) 
        { 
            $postdata_str = substr($postdata_str, 0, -1); 
            $req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf; 
            $req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf; 
            $req .= $postdata_str; 
        } 
        else $req .= $crlf; 

        if ($req_hdr) 
            $ret .= $req; 

        if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false) 
            return "Error $errno: $errstr\n"; 

        stream_set_timeout($fp, 0, $timeout * 1000); 

        fputs($fp, $req); 
        while ($line = fgets($fp)) $ret .= $line; 
        fclose($fp); 

        if (!$res_hdr) 
            $ret = substr($ret, strpos($ret, "\r\n\r\n") + 4); 

        return $ret; 

        /*
        Example usages : 


        echo http_request('GET', 'www.php.net'); 
        echo http_request('GET', 'www.php.net', 80, '/manual/en/function.phpinfo.php'); 
        echo http_request('GET', 'www.php.net', 80, '/manual/en/function.phpinfo.php', array('get1' => 'v_get1'), array(), array('cookie1' => 'v_cookie1'), array('X-My-Header' => 'My Value')); 


        [EDIT BY danbrown AT php DOT net: Contains a bugfix provided by "Wrinkled Cheese" on 24-JUN-2011 to fix the $getdata foreach() loop; another bugfix provided by Suat Secmen on 12-JAN-2012 to fix a $timeout = 1000, then stream_set_timeout($fp, 0, $timeout * 1000), equaling 1,000 seconds.]
    */

    }
    
    
}


?>
