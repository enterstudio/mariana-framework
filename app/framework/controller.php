<?php

namespace Mariana\Framework;


abstract class Controller{

    public $params = array();

    public function __construct(Array $array){
        $this->params = $array;

    }
}