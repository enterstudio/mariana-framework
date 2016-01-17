<?php namespace Mariana\Framework\Session;
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 17/01/2016
 * Time: 11:21
 */

use Mariana\Framework\Config;

class Flash extends Session{

    static $messageArray = array();

    public static function set(Array $flashMessages){
        $_SESSION["flash"] = $flashMessages;
    }

    public static function show(){
        if(isset($_SESSION["flash"])){
            static::$messageArray = $_SESSION["flash"];
            self::delete("flash");
            return self::$messageArray;
        }
        return false;
    }

}