<?php 

class Request {

    public $params = array();
    public $body = array();

    function __construct($params, $body) {
        $this->params = $params;
        $this->body = $body;
    }
    
}