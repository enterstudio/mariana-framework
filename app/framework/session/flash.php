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

    public static function setMessages(Array $flashMessages){
        self::set("flash",$flashMessages);
    }

    public static function showMessages(){
        if(isset($_SESSION["flash"])){
            static::$messageArray = $_SESSION["flash"];
            self::delete("flash");
            return self::$messageArray;
        }
        return false;
    }

}