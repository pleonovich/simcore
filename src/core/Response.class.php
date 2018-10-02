<?php
/**
 * Response 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with headers
 *
 * $Response = new Response();
 * $Response->Status(301);
 * $Response->LastModified(gmdate("D, d M Y H:i:s", time()));
 * $Response->ContentType('text/html');
 *
 */ 

class Response {

    private $status = array(
        200 => "OK",
        201 => "Created",
        204 => "No Content",
        301 => "Moved Permanently",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        498 => "Invalid Token",
        499 => "Token Required",
    );
    private $headers = array(
        "Status" => 200,
        "Date" => false,
        "LastModified" => false,
        "ContentType" => 'text/html',
        "Location" => false
    );

    function __construct() {
    }

    public function send($body = null) {
        if($this->Date!==false) header("Date: ".gmdate("D, d M Y H:i:s \G\M\T", $this->Date));
        $code = $this->headers['Status'];
        header("HTTP/1.1 ".$code." ".$this->status[$code]);
        if($this->LastModified!==false) header("Last-Modified: ".gmdate("D, d M Y H:i:s \G\M\T",$this->LastModified));
        header("Content-Type: ".$this->ContentType);
        if($this->Location!==false) header("Location: ".$this->Location);
        if ($this->headers['ContentType'] == "application/json") {
            $body = json_encode($body);
            exit($body);
        }
        exit($body);
    }

    public function __get($name){
        if(isset($this->headers[$name])) return $this->headers[$name];
        else return NULL;
    }

    public function status($code) {
        if(isset($this->status[$code])) $this->headers['Status'] = $code;
        else throw new Exception('Error! Status not found');
        return $this;
    }

    public function __call($name, $params) {
        if(isset($this->headers[$name])) {
            $this->headers[$name] =  $params[0];
        } else throw new Exception('Error! Header not found');
        return $this;
    }
}