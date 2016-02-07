<?php

namespace Mariana\Framework;


abstract class Controller{

    public $params = array();
    public $middleware = array();
    public $middleware_before = array();
    public $middleware_after = array();

    public function __construct(Array $array){
        $this->params = $array;

    }
}