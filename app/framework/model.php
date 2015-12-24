<?php namespace Mariana\Framework;

use Mariana\Framework\ORM\ORM;

abstract class Model extends ORM{

    private $data = array();
    protected static $table;

    public function __construct($data = null){
        $this->data = $data;
    }

    public function __get($name){
        return $this->data($name);
    }

    public function __set($key,$pair){
        $this->data[$key] = $pair;
    }

    public function getColumns(){
        return $this->data;
    }


}