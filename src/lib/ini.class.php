<?php 

class INI {

    public $data = array();

    function __construct ( $name ) {
        $this->data = parse_ini_file($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR.$name);
    }

    public function __get ( $name ) {
        if(isset($this->data[$name])) return $this->data[$name];
        else return NULL;
    }

    public static function factory ( $name ) {
        return new INI($name);
    }

}