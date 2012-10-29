<?php

class RestResponse {

    private $responseCode = "";
    private $responseBody = "";

    public function RestResponse() {
        
    }

    public function setResponseBody($msg) {
        $this->responseBody = $msg;
    }

    public function getResponseBody() {
        return $this->responseBody;
    }

    public function setResponseCode($code) {
        $this->responseCode = $code;
    }

    public function getResponseCode() {
        return $this->responseCode;
    }

    public function getDataAsString() {
        return $this->responseBody;
    }

    public function getDataAsXml() {
        $xml = simplexml_load_string($this->responseBody);
        if ($xml == false) {
            throw new Exception('Could not parse XML.', $this->responseBody);
        }
        return $xml;
    }

}

?>
