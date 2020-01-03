<?php
namespace SimCore\core;

class Request {

    public $params = array();
    public $body = array();
    protected $url = null; // url object
	protected $sefurl = null; // sef

    function __construct($params, $body) {
        $this->params = $params;
        $this->body = $body;
        $this->url = new URL();
		$this->sefurl = new SefURL();
    }
    
}