<?php
namespace SimCore\core;

/**
 * ABSTRACT CONTROLLER CLASS 1.0.0
 */

abstract class AbstractController {
	
	public function __construct () {
		//if (!session_id()) session_start();
		if(!defined('DOMEN_NAME')) define('DOMEN_NAME',$_SERVER['HTTP_HOST']);
		if(!defined('HOST_NAME')) define('HOST_NAME',"//".$_SERVER['HTTP_HOST']);
	}

	abstract protected function render($content);

	final protected function redirect($url=NULL, $replace=true, $code=NULL) {
		header("Location: $url", $replace, $code);
		exit;
	}

}