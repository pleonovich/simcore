<?php
/**
 * ABSTRACT CONTROLLER CLASS 1.0.0
 */

abstract class abstractController {
	
	protected $view; // view object
	protected $conn; // SafeMySQL object, db connection
	protected $user = null; // user object
	protected $url = null; // url object
	protected $sefurl = null; // sef
	
	public function __construct () {
		//if (!session_id()) session_start();
		define('DOMEN_NAME',$_SERVER['HTTP_HOST']);
		$opts = array(
			'user'    => Config::DB_USER,
			'pass'    => Config::DB_PASS,
			'db'      => Config::DB_NAME,
			'charset' => Config::DB_CHARSET
		);
		$this->view = new View();
		$this->conn = new SafeMySQL($opts);
		$this->user = new User($this->conn);
		$this->url = new URL();
		$this->sefurl = new SefURL();
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

	final protected function redirect($url) {
		header("Location: $url");
		exit;
	}

}