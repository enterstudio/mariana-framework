<?php namespace Pihh\Classes;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 12/18/2015
 * Time: 9:56 PM
 */

class Security{


    protected function hash($var){
        return password_hash($var, PASSWORD_BCRYPT);
    }

    protected function check($var ,$hash){
        return password_verify ( $var ,$hash );
    }
}