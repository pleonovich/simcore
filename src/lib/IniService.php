<?php 
namespace SimCore\lib;

class IniService {

    public $data = array();

    function __construct ( $filePath ) {
        $this->data = parse_ini_file($filePath);
    }

    public function __get ( $name ) {
        if(isset($this->data[$name])) return $this->data[$name];
        else return NULL;
    }

    public static function factory ( $name ) {
        return new IniService($name);
    }

}