<?php
namespace SimCore\lib;

use SimCore\core\Config as Config;

class HipertextLinks {

	private $DATA = array();
	private $types = array("ico","css","js");
	const ID_FICON = "ico";	
	const ID_CSS = "css";
	const ID_JS = "js";
	const ID_EXFOLDER = "folder";
	const ID_EXURL = "url";
	
	function __construct () {
	}
	
	public function css ( $file, $external=false ) {
		if($external==self::ID_EXFOLDER) $file = Config::$http_host."/".$file;
		elseif($external==self::ID_EXURL) $file = $file;
		else $file = Config::$http_host."/css/".$file;
		$this->set($file, self::ID_CSS, $external);
	}
	
	public function js ( $file, $external=false ) {	
		if($external==self::ID_EXFOLDER) $file = Config::$http_host."/".$file;
		elseif($external==self::ID_EXURL) $file = $file;
		else $file = Config::$http_host."/js/".$file;
		$this->set($file, self::ID_JS, $external);
	}
	
	public function ico ( $file ) {		
		$this->set($file, self::ID_FICON);
	}
	
	private function set ( $file, $type ) {
		if(!in_array($type,$this->types)) return null;
		$next = count($this->DATA);
		$this->DATA[$next]['file'] = $file;
		$this->DATA[$next]['type'] = $type;
	}
	
	public function render () {		
		if(count($this->DATA)==0) return "";
		$links = NULL;
		foreach ($this->DATA as $link) {
			if ($link['type'] == self::ID_FICON) $links .= $this->print_ficon($link['file']);
			elseif ($link['type'] == self::ID_CSS) $links .= $this->print_css($link['file']);
			elseif ($link['type'] == self::ID_JS) $links .= $this->print_js($link['file']);				 
		}
		return $links;
	}
	
	final public function __toString() {
		return $this->render();
	}
	
	private function print_css ( $file ) {
		return "<link href=\"".$file."\" rel=\"stylesheet\" type=\"text/css\">\n";
	}
	
	private function print_js ( $file ) {
		return "<script src=\"".$file."\" type=\"text/javascript\" ></script>\n";
	}
	
	private function print_ficon ( $file ) {
		$icon_link = "<link href=\"".Config::$http_host."/".$file."\" rel=\"icon\" type=\"image/x-icon\" />\n";
		$icon_link .= "<link href=\"".Config::$http_host."/".$file."\" rel=\"shortcut icon\" type=\"image/x-icon\" />\n";
		return $icon_link;
	}

}
?>