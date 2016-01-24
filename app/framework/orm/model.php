<?php namespace Mariana\Framework;

use Mariana\Framework\ORM\MarianaORM;

abstract class Model extends MarianaORM{

    public $data = array();

    //protected $teste = "teste";

    function __construct($data = false){
        $this->db = self::getConnection();
        $this->table = self::table();
        if($data !== false){
            $this->{$this->primary} = $data;
        }
    }

    function __call($method, $args){
        return call_user_func_array($method, $args);
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