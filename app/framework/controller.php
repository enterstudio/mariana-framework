<?php namespace Mariana\Framework;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 12/18/2015
 * Time: 9:59 PM
 */

abstract class Controller{

    public $params = array();

    public function __construct(Array $array){
        $this->params = $array;

    }
}