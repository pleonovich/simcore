<?php 
/**
 * CONTROLLER CLASS
 */

class Controller extends abstractController {
	
	protected $content = null;
	
	public function __construct () {
		parent::__construct();
	}
	
	protected function accessDenied () {
		View::factory()->render('accessdenied');
	}
	
	protected function action404 () {
		View::factory()->render('action404');
	}
	
	protected function render ( $content ) {
		View::factory()
		->bind("content",$content)
		->render('layout');
	}

}