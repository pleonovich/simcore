<?php
/**
 * ABSTRACT CONTROLLER CLASS 1.0.0
 */

abstract class abstractController {
	
	protected $response; // response object
	protected $view; // view object
	protected $conn; // SafeMySQL object, db connection
	protected $user = null; // user object
	protected $url = null; // url object
	protected $sefurl = null; // sef
	
	public function __construct () {
		//if (!session_id()) session_start();
		if(!defined('DOMEN_NAME')) define('DOMEN_NAME',$_SERVER['HTTP_HOST']);
		if(!defined('HOST_NAME')) define('HOST_NAME',"//".$_SERVER['HTTP_HOST']);
		$opts = array(
			'host'    => Config::DB_HOST,
			'user'    => Config::DB_USER,
			'pass'    => Config::DB_PASS,
			'db'      => Config::DB_NAME,
			'charset' => Config::DB_CHARSET,
			'port' 	 => Config::DB_PORT,
			'socket' => Config::DB_SOCKET
		);
		$this->response = new Response();
		$this->view = new View();
		$this->conn = new SafeMySQL($opts);
		$this->user = new User($this->conn);
		$this->url = new URL();
		$this->sefurl = new SefURL();
	}

	public function isAccess () {
		if (!$this->user->access()) {
			$this->accessDenied();
			throw new Exception("ACCESS_DENIED");
		}
	}

	abstract protected function render($content);
	abstract protected function accessDenied();
	abstract protected function action404();	

	final protected function notFound() {
		$this->action404();
	}

	final protected function redirect($url=NULL, $replace=true, $code=NULL) {
		header("Location: $url", $replace, $code);
		exit;
	}

}