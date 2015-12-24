<?php namespace Mariana\Framework;

use Mariana\Framework\ORM\MarianaORM;

abstract class Model extends MarianaORM{

    protected $data = array();
    protected static $table;

    function __construct($data = null){
        $this->data = $data;
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