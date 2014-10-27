<?php

class libraryRequest {

    static function getScriptName () {
        $name = $_SERVER['REDIRECT_URL'];
        return basename($name, '.js');
    }

    static function getRequestData () {
        global $PHP_INPUT;
        return (object) array(
            "get" => $_GET,
            "post" => $_POST,
            "data" => json_decode($PHP_INPUT, true)
        );
    }

    /* get values */
    static function fromGET($key, $defaultValue = null) {
        if (isset($_GET[$key]))
            return $_GET[$key];
        return $defaultValue;
    }

    static function hasInGet() {
        for ($i = 0, $num = func_num_args(); $i < $num; $i++)
            if (!isset($_GET[func_get_arg($i)]))
                return false;
        return true;
    }

    static function isPOST () {
        return $_SERVER['REQUEST_METHOD'] === "POST";
    }

    static function isGET () {
        return $_SERVER['REQUEST_METHOD'] === "GET";
    }

    static function isPATCH () {
        return $_SERVER['REQUEST_METHOD'] === "PATCH";
    }

    static function isPUT () {
        return $_SERVER['REQUEST_METHOD'] === "PUT";
    }

    static function isDELETE () {
        return $_SERVER['REQUEST_METHOD'] === "DELETE";
    }

    static function processRequest ($context) {
        $_REQ = self::getRequestData();
        $_source = self::fromGET('source');
        $_fn = self::fromGET('fn');
        $_method = strtolower($_SERVER['REQUEST_METHOD']);
        $requestFnElements = array($_method);

        if (self::hasInGet('source'))
            $requestFnElements[] = $_source;
        
        if (self::hasInGet('fn'))
            $requestFnElements[] = $_fn;

        $fn = join("_", $requestFnElements);
        // var_dump($context);
        // echo $fn;
        // var_dump($requestFnElements);
        // var_dump($_REQ);
        // var_dump(isset($context->api->$_fn));
        if (!empty($context)) {
            if (isset($context->api->$_fn) && method_exists($context->api->$_fn, $_method)) {
                $context->api->$_fn->$_method(libraryResponse::$_RESPONSE, $_REQ);
                // var_dump(libraryResponse::$_RESPONSE);
            } elseif (method_exists($context, $fn)) {
                $context->$fn(libraryResponse::$_RESPONSE, $_REQ);
            }
        }
    }

    /* state grabbers */
    static function isJsApiRequest () {
        //echo 'RequestAction is ' . self::getAction();
        // return self::getAction() === 'api';
        return MPWS_REQUEST === "API";
    }

    public static function getOrValidatePageSecurityToken($privateKey, $publicKey = '') {
        if (!empty($publicKey)) {
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
}


?>
