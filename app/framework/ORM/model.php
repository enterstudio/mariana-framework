<?php namespace Mariana\Framework;

use Mariana\Framework\ORM\MarianaORM;

abstract class Model extends MarianaORM{

    private $data = array();
    protected static $table;

    function __construct($data = null){
        $this->data = $data;
    }

    function __get($name){
        return $this->data[$name];
    }

    function __set($name,$value){
        return $this->data[$name] = $value;
    }
/*
    public static function getTable(){
        if(!isset(static::$table)) {
            echo static::$table = get_called_class();
        }
    }
*/
}