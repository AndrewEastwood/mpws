<?php

require_once 'PEAR.php';
// Get this by installing HTTP_Request using
//      pear install HTTP_Request
require_once "HTTP/Request.php";
include_once "RestResponse.php";

/*
 * This Class depends on pear package HTTP/Request. Please see 
 * http://php.netbeans.org/ on how to setup PHP environment and install 
 * required pear package 'HTTP_Request' from http://pear.php.net/.");
 * If you have pear installed already. Then run 'pear install HTTP_Request'.
 * Also install 'Net_Socket' and 'Net_URL' required by HTTP_Request package.
 */

class RestConnection {

    private $url = "";
    private $response = "";
    private $responseBody = "";
    private $req = null;
    private $headers = array();
    private $date = "";
    private $username = null;
    private $password = null;

    public function RestConnection($url, $params1, $params2) {
        $pathParams = null;
        $params = null;
        if (isset($params2)) {
            $pathParams = $params1;
            $params = $params2;
        } else if ($params1) {
            $params = $params1;
        }
        if ($url == null)
            throw new Exception('$url parameter cannot be empty in RestConnection()');
        $this->url = $url;
        if ($pathParams != null) {
            foreach ($pathParams as $key => $value) {
                $this->url = str_replace($key, $value, $this->url);
            }
        }
        $this->url = $this->encodeUrl($this->url, $params);
        $this->date = gmdate("D, d M Y H:i:s T");
        $this->req = & new HTTP_Request($this->url);
    }

    public function setAuthentication($username, $password) {
        $this->username = $username;
        $this->password = $password;
        if ($this->username != null && $this->password != null)
            $this->req->setBasicAuth($this->username, $this->password);
    }

    public function getDate() {
        return $this->date;
    }

    public function setHeaders($headers = array("")) {
        //remove all previous headers
        foreach ($this->headers as $key => $value) {
            $this->req->removeHeader($key);
        }
        $this->headers = $headers;
        foreach ($this->headers as $key => $value) {
            $this->req->addHeader($key, $value);
        }
    }

    public function get() {
        $this->req->setMethod("GET");
        return $this->connect();
    }

    public function put($data) {
        if ($data != null) {
            if (is_array($data)) {
                $this->req->setMethod("PUT");
                $this->req->addHeader("Content-Type", "application/x-www-form-urlencoded");
                $this->req->setBody($this->encodeParams($data));
            } else {
                $this->req->setBody($data);
            }
        }
        return $this->connect();
    }

    public function post($data) {
        if ($data != null) {
            if (is_array($data)) {
                $this->req->setMethod("POST");
                $this->req->addHeader("Content-Type", "application/x-www-form-urlencoded");
                $this->req->setBody($this->encodeParams($data));
            } else {
                $this->req->setBody($data);
            }
        }
        return $this->connect();
    }

    public function delete() {
        $this->req->setMethod("DELETE");
        return $this->connect();
    }

    public function connect() {
        $this->response = $this->req->sendRequest();

        if (PEAR::isError($this->response)) {
            echo $this->response->getMessage();
            die();
        } else {
            $this->responseBody = $this->req->getResponseBody();
        }
        $response = new RestResponse();

        $response->setResponseCode($this->req->getResponseCode());
        $response->setResponseBody($this->req->getResponseBody());

        return $response;
    }

    private function encodeUrl($url, $params) {
        $encodedParams = $this->encodeParams($params);
        if (strlen($encodedParams) > 0) {
            $encodedParams = "?" . $encodedParams;
        }

        return $url . $encodedParams;
    }

    private function encodeParams($params) {
        $p = "";
        if ($params != null) {
            foreach ($params as $key => $value) {
                if ($value != null)
                    $p = $p . $key . "=" . urlencode($value) . "&";
            }
            $p = substr($p, 0, strlen($p) - 1);
        }

        return $p;
    }

    public static function currentTimeMillis() {
        list($usec, $sec) = explode(" ", microtime());
        return round(((float) $usec + (float) $sec) * 1000);
    }

}

?>
