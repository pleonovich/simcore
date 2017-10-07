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
        "Date" => NULL,
        "LastModified" => NULL,
        "ContentType" => 'text/html',
        "Location" => NULL
    );

    function __construct() {}

    public function render() {
        if($this->Date!==NULL) header("Date: ".$this->Date);
        $code = $this->headers['Status'];
        header("HTTP/1.1 ".$code." ".$this->status[$code]);
        if($this->LastModified!==NULL) header("Last-Modified: ".$this->LastModified);
        header("Content-Type: ".$this->ContentType);
        if($this->Location!==NULL) header("Location: ".$this->Location);
    }

    public function __get($name){
        if(isset($this->headers[$name])) return $this->headers[$name];
        else return NULL;
    }

    public function __call($name, $params) {
        if(isset($this->status[$params[0]])) {
            if(isset($this->headers[$name])) $this->headers[$name] = $params[0];
            else die('Error! Header not found');
        } else die('Error! Status not found');
        return $this;
    }

}