<?php namespace Mariana\Framework;

use Mariana\Framework\ORM\MarianaORM;

abstract class Model extends MarianaORM{

    public $data = array();
    public $has = array();
    public $observer = array();

    function __construct($data = false){
        $this->db = self::getConnection();
        $this->table = self::table();
        if($data !== false){
            $this->{$this->primary} = $data;
        }
    }

/*
    protected function has($table, $identifier, $one_or_many = false){
        // The query :
        // SELECT * FROM (SELECT * FROM `users` WHERE `id` = 1) AS `u`, (SELECT GROUP_CONCAT(`mobile_numbers`.`phone_number`, '') AS 'contacts' FROM `mobile_numbers` WHERE `user_id` = 1 ) AS `m` 
        $this->{$table} = $this->join($table,$identifier,false,false,false);
        return $this;
    }

    /*
    function __call($method, $args){
        return call_user_func_array($method, $args);
    }
    */

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
        if(is_object($value)){
            return $this->$name = $value;
        }elseif(trim($name) !== '' && trim($value) !== '') {
            return $this->data[$name] = $value;
        }

    }

    public function getColumns(){
        return $this->data;
    }



}