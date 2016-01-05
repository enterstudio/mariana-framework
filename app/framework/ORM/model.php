<?php namespace Mariana\Framework;

use Mariana\Framework\ORM\MarianaORM;

abstract class Model extends MarianaORM{

    public $data = array();

    function __construct($data = false){
        $this->db = self::getConnection();
        $this->table = self::table();
    }

    function __get($name){
        if(array_key_exists($name,$this->data)){
            return $this->data[$name];
        }
    }

    function __set($name,$value){
        return $this->data[$name] = $value;
    }

    public function getColumns(){
        return $this->data;
    }

}