<?php namespace Mariana\Framework;

use Mariana\Framework\ORM\MarianaORM;

abstract class Model extends MarianaORM{

    public $data = array();

    function __construct($data = false){
        $this->db = self::getConnection();
        $this->table = self::table();
        if($data !== false){
            $this->{$this->primary} = $data;
        }
    }

    function __call($method, $args)
    {
        //return call_user_func_array($method, $args);
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }

    function __get($name){
        if(array_key_exists($name,$this->data)){
            return $this->data[$name];
        }
    }

    function __set($name,$value){
        if(trim($name) !== '' && trim($value) !== '') {
            return $this->data[$name] = $value;
        }
    }

    public function getColumns(){
        return $this->data;
    }



}