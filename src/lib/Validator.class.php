<?php

class Validator {

    const ID_TEXT = 'text';
    const ID_EMAIL = 'email';
    const ID_SIMILAR = 'similar';

    private $DATA = array();
    private $ARGS = array();
    private $inval = array();
    private $errors = array();
    private $ini = array();

    function __construct ( $DATA, $ini = null ) {
        $this->DATA = $DATA;
        if($ini!==null) $this->ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR.$ini);
    }

    private function label ($name) {
        if(isset($this->ini[$name])) return $this->ini[$name];
        else return $name;
    }

    private function add ( $name, $type, $length = 255, $toname = null ) {
        $key = count($this->ARGS);
        $this->ARGS[$key]['name'] = $name;
        $this->ARGS[$key]['type'] = $type;
        $this->ARGS[$key]['length'] = $length;
        $this->ARGS[$key]['to'] = $toname;
    }

    public function text ( $name, $length = 255 ) {        
        $this->add($name, self::ID_TEXT, $length);
        return $this;
    }

    public function email ( $name, $length = 255 ) {        
        $this->add($name, self::ID_EMAIL, $length);
        return $this;
    }

    public function is_similar ( $name, $toname, $length = 255 ) {        
        $this->add($name, self::ID_SIMILAR, $length, $toname);
        return $this;
    }

    private static function clean ( $text ) {        
        $text = trim($text);
        $text = strip_tags($text);
        $text = htmlentities($text, ENT_QUOTES, "UTF-8");
        $text = htmlspecialchars($text, ENT_QUOTES);
        return $text;
    }

    private function checkText ( $key ) {
        $name = $this->ARGS[$key]['name'];
        $label = $this->label($name);
        $text = self::clean($this->DATA[$name]);
        $length = $this->ARGS[$key]['length'];
        if(empty($text)) {
            $this->errors[] = " Поле '{$label}' не заполнено ";
        } else {
            if(strlen($text)>$length) {
                $this->errors[] = " Поле '{$label}' слишком длинное";
            } elseif(strlen($text)<4) {
                $this->errors[] = " Поле '{$label}' слишком короткое ";
            } else {
                if(!preg_match('/^[A-Za-zА-Яа-я0-9_!?.,;:\(\)\"\`\'\s]{4,'.$length.'}/iu', $text, $match)) {
                    $this->errors[] = " Поле '{$label}' содержит запрещенные символы  ";
                }
            }
        }
    }

    private function checkIsSimilar ( $key ) {
        $name = $this->ARGS[$key]['name'];
        $toname = $this->ARGS[$key]['to'];
        $label1 = $this->label($name);
        $label2 = $this->label($toname);
        $res = ($this->DATA[$name]===$this->DATA[$toname]) ? true : false;
        if(!$res) $this->errors[] = " Поля '{$label1}' и '{$label2}' не совпадают ";
        return $res;
    }

    private function checkEmail ( $key ) {
        $name = $this->ARGS[$key]['name'];
        $label = $this->label($name);
        $email = self::clean($this->DATA[$name]);
        if(empty($email)) {
            $this->errors[] = " Поле '{$label}' не заполнено ";
        } else {
            if(!preg_match("/[a-zA-Z0-9-_.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/i",$email)) {
                $this->errors[] = " Поле '{$label}' содержит запрещенные символы ";
            }
        }
    }

    public function validate () {        
        foreach($this->ARGS as $key=>$args) {
            $name = $args['name'];
            if(isset($this->DATA[$name])) {
                if($args['type']===self::ID_TEXT) $this->checkText($key);
                elseif($args['type']===self::ID_EMAIL) $this->checkEmail($key);
                elseif($args['type']===self::ID_SIMILAR) $this->checkIsSimilar($key);                
            }
        }
        return (count($this->errors)>0) ? false : true ;
    }

    public function errors () {
        return implode("<br>",$this->errors);
    }

    public static function factory ( $DATA, $ini=null ) {
        return new Validator($DATA, $ini);
    }

}