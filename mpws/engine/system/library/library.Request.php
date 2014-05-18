<?php


class libraryRequest {

    /* get values */
    static function getPlugin ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('plugin', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getLocale ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('l', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getDisplay($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('display', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPage ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('page', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getAction ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('action', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getOID ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('oid', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getValue($key, $defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::value($_GET, $key, $defaultValue, $switch, $valueOnSwitch);
    }

    /* get post values */
    static function getPostPlugin ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getPostValue('plugin', $defaultValue, $switch, $valueOnSwitch);
    }
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
    static function getPostFormAction ($lower = true) {
        if ($lower)
            return strtolower(self::value($_POST, 'do'));
        return self::value($_POST, 'do');
    }
    static function getPostFormField ($key, $doEscape = true, $wrap = '') {
        $_value = self::value($_POST, renderFLD_NAME . strtolower($key));
        
        if ($doEscape) {
            if (is_array($_value))
                foreach ($_value as $k => $v)
                    $_value[$k] = mysql_escape_string($v);
            if (is_string($_value))
                $_value = mysql_escape_string($_value);
        }
        if (!empty($wrap)) {
            if (is_array($_value))
                foreach ($_value as $k => $v)
                    $_value[$k] = $wrap . $v . $wrap;
            if (is_string($_value))
                $_value = $wrap . $_value . $wrap;
        }
        return $_value;
    }

    /* api */
    static function getApiCaller ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getPostValue('caller', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getApiFn ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getPostValue('fn', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getApiParam ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        $param = self::getPostValue('p', $defaultValue, $switch, $valueOnSwitch);
        //var_dump($param);
        // parse_str($param, $param);
        if (empty($param))
            return $defaultValue;
        // $param = libraryUtils::cleanQueryArray($param);
        // if single parameter
        // p=sometext
        // return 'sometext'
        $c = current($param);
        if (count($param) == 1 && empty($c))
            return $c;
        // explode custom params
        if (isset($param['custom'])) {
            //echo 'LOL';
            //echo $param['custom'];
            $output = false;
            parse_str(urldecode($param['custom']), $output);
            $param['custom'] = $output;
        }
        return $param;
    }

    /* state grabbers */
    static function isJsApiRequest () {
        //echo 'RequestAction is ' . self::getAction();
        // return self::getAction() === 'api';
        return MPWS_REQUEST === "API";
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
        for ($i = 0, $num = func_num_args(); $i < $num; $i++)
            $container[func_get_arg($i)] = self::getPostValue(func_get_arg($i));
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
            if (isset($_SESSION['MPWS_STORED_URL'.$scope])) {
                $_data = parse_url($_SESSION['MPWS_STORED_URL'.$scope]);
                return $_data['path'] . (!empty($_data['query'])? ('?' . $_data['query']) : '');
            }
            return false;
        }
    }

    static function getNewUrl($key = '', $value = '', $remove = array('page', 'action'), $keep = array()) {
        //echo 'GETTING NEW URL WITH KEY: ' . $key;
        $_data = array();
        parse_str($_SERVER['QUERY_STRING'], $_data);
        if ($remove == 1)
            $remove = array('page', 'action');
        // array arguments
        if (!empty($key)) {
            if (is_array($key)) {
                foreach ($key as $idx => $k) {
                    if (empty($value) || $value[$idx] == null)
                        $remove[] = $k;
                    else
                        $_data[$k] = $value[$idx];
                }
            } else {
                if ($value == null)
                    $remove[] = $key;
                else
                    $_data[$key] = $value;
            }
        }
        // remove hidden keys
        if(is_array($remove))
            foreach ($remove as $keyToRemove)
                unset($_data[$keyToRemove]);
        elseif (is_string($remove))
            unset($_data[$remove]);
        //unset($_data['action']);
        //$str = http_build_query($_data);
        //var_dump($str);
        //filter by keep value
        if (!empty($keep)){
            $_newData = array();
            foreach ($_data as $_key => $_val) {
                if (in_array($_key, $keep))
                    $_newData[$_key] = $_val;
            }
            $_data = $_newData;
        }
        //$str = http_build_query($_data);
        //var_dump($str);
        //var_dump($keep);
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
    
    public static function getOrValidatePageSecurityToken($privateKey, $publicKey = '') {
        if (!empty($publicKey)) {
            // echo $_SESSION['MPWS_SESSION_TOKEN'];
            // echo 'publicKey=' . $publicKey;
            // echo 'MPWS_SESSION_TOKEN=' . $_SESSION['MPWS_SESSION_TOKEN'];
            if (!empty($_SESSION['MPWS_SESSION_TOKEN']))
                return $_SESSION['MPWS_SESSION_TOKEN'] === $publicKey;
            return $publicKey === self::getOrValidatePageSecurityToken($privateKey);

        }
        if (!self::isJsApiRequest()) {
            // make token
            $_SESSION['MPWS_SESSION_TOKEN'] = md5($privateKey . date('Y-m-d'));
            // echo 'MPWS_SESSION_TOKEN=' . $_SESSION['MPWS_SESSION_TOKEN'];
        }
        return isset($_SESSION['MPWS_SESSION_TOKEN']) ? $_SESSION['MPWS_SESSION_TOKEN'] : null;
    }

    
    public static function postRedirect ($values, $host, $action) {
        // MPWS PERMISSION CHECK
        if (!MPWS_ENABLE_REDIRECTS)
            return;
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
        // MPWS PERMISSION CHECK
        if (!MPWS_ENABLE_REDIRECTS)
            return;
        header('Location: ' . self::getLocationString($values, $location));
    }
    
    public static function getLocationString ($values, $location) {
        $post_data = '';
        foreach($values as $key => $value)
            $post_data .= ($key . '=' . $value . '&');
        return $location . '?' . $post_data;
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
        
        
        // MPWS PERMISSION CHECK
        if(!MPWS_ENABLE_REDIRECTS)
            return;
        
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
          

    
    public static function getURL ($urlString) {

        define("PSNG_CRAWLER_MAX_FILESIZE", 150000); // only 100k data will be scanned
        define("PSNG_CRAWLER_MAX_GETFILE_TIME", 5);  
        define("PSNG_VERSION", '1.5.3');

        $url = parse_url($urlString);
        $url_scheme = isset($url['scheme']) ? $url['scheme'] : '';
        $url_host = isset($url['host']) ? $url['host'] : '';
        $url_port = isset($url['port']) ? $url['port'] : '';
        $url_path = isset($url['path']) ? $url['path'] : '';
        $url_path = str_replace(' ', '%20', $url_path); // replace spaces in url
        $url_query = isset($url['query']) ? $url['query'] : '';
        //$cookie_string = '';
        /*if (count($this->cookies) > 0) {
                foreach ($this->cookies as $cookie_name => $cookie) {
                        // check path - dumb approach (only check if url contains cookie path)
                        if (strpos($urlString, $cookie['path'])) {
                                $cookie_string .= $cookie_name . '=' . $cookie[$cookie_name] . '; ';
                        }
                }
                if (strlen($cookie_string) > 0) {
                        $cookie_string = 'Cookie: ' . $cookie_string ."\r\n";
                }
        }
//		echo "Sending cookie_string: $cookie_string<br>\n";
        */
        /*if ($url_port == '') {
                if ($url_scheme == 'https') {
                        $url_port = "443";
                } else {
                        $url_port = "80";
                }
        }*/
        $url_port = "80";
        //		debug($url, 'Parsed URL');
        
        //echo 'OPENING: ' . $url_host;
        $fp = fsockopen($url_host, $url_port, $errno, $errstr, /*$this->timeout*/30);
        if ($fp === FALSE) {
                //echo 'FAILED';
                debug($errstr, 'Could not open connection for '.$urlString.' (host: '.$url_host.', port:'.$url_port.'), Errornumber: '.$errno);
                return array('header' => array(), 'content' => '');
        }
        
        //var_dump($fp);
        
        $query_encoded = '';
        if ($url_query != '') {
                $query_encoded = '?';
                foreach (split('&', $url_query) as $id => $quer) {
                        $v = split('=', $quer);
                        if ($v[1] != '') {
                                $query_encoded .= $v[0].'='.rawurlencode($v[1]).'&';
                        } else {
                                $query_encoded .= $v[0].'&';
                        }
                }
                $query_encoded = substr($query_encoded, 0, strlen($query_encoded) - 1);
                $query_encoded = str_replace('%2B','+', $query_encoded);
        }

        $get = "GET /".$url_path.$query_encoded." HTTP/1.1\r\n";
        $get .= "Host: ".$url_host."\r\n";
        //$get .= "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; phpSitemapNG ".PSNG_VERSION.")\r\n";
        //$get .= "Referer: ".$url_scheme.'://'.$url_host.$url_path."\r\n";
        //$get .= $cookie_string;
        $get .= "Connection: close\r\n\r\n";
        debug(str_replace("\n", "<br>\n", $get), 'GET-Query');
        socket_set_blocking($fp, TRUE);
        
        //echo $get;
        
        fwrite($fp, $get);

        $res = '';
        $head_done = FALSE;
        $ts_begin = self::microtime_float();
        // source for chunk-decoding: http://www.phpforum.de/archiv_13065_fsockopen@end@chunked@geht@nicht_anzeigen.html

        // get headers
        $currentHeader = '';
        while ( '' != ($line=trim(fgets($fp, 1024))) ) {
                if ( FALSE !== ($pos=strpos($line, ':')) ) {
                        $currentHeader = substr($line, 0, $pos);
                        $header[$currentHeader] = trim(substr($line, $pos+1));
                } else {
                        @$header[$currentHeader] .= $line;
                }
        }

        // check for chunk encoding
        if (isset($header['Transfer-Encoding']) && $header['Transfer-Encoding'] == 'chunked') {
                $chunk = hexdec(fgets($fp, 1024));
        } else {
                $chunk = -1;
        }

        // check file size
        /*if (isset($header['Content-Length']) && $header['Content-Length'] > PSNG_CRAWLER_MAX_FILESIZE) {
                info($size, "File size ". $header['Content-Length'] . " of ".$urlString." exceeds file size limit of ".PSNG_CRAWLER_MAX_FILESIZE." byte!");
                fclose($fp);
                return array('header' => $header, 'content' => '');
        }*/

        // get content
        $res = '';
        $deadline = 1345664732.5111;
        while ($chunk != 0 && !feof($fp)) {
                // echo "chunking...<br>\n";
            if ($chunk > 0){
                    $part = fread($fp, $chunk);
                    $chunk -= strlen($part);
                    $res .= $part;

                    if ($chunk == 0){
                        if (fgets($fp, 1024) != "\r\n") debug('Error in chunk-decoding');
                        $chunk = hexdec(fgets($fp, 1024));
                    }
            } else {
                    $res .= fread($fp, 1024);
            }
                // handle local timeout for fetching file
                if (($ts_middle = self::microtime_float() - $ts_begin) > PSNG_CRAWLER_MAX_GETFILE_TIME) break;
                // handle global timeout:
                if (($deadline != 0) && (($deadline - self::microtime_float()) < 0)) break;
        }
        fclose($fp);

        return array('header' => $header, 'content' => $res);
    }
    
    public static function microtime_float() {
        list ($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }
    
    public static function explodeUrl ($url) {
        $_url = parse_url($url);
        //var_dump($_url);
        //var_dump($_url['query']);
        //var_dump(parse_str($_url['query']));
        if (!empty($_url['query'])) {
            parse_str($_url['query'], $_url['query']);
        }
        return $_url;
    }
    
}


?>
