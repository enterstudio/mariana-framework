<?php namespace Mariana\Framework;

abstract class Middleware{

    public static $before = array();
    public static $after = array();

    public function __construct(Array $before = array(), Array $after = array()){
        self::$after = $after;
        self::$before= $before;
    }

    public function before(){

    }

    public function after(){

    }
}