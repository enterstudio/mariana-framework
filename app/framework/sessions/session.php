<?php namespace Mariana\Framework\Session;

use Mariana\Framework\Config;
use Mariana\Framework\Design\Singleton;

class Session extends Singleton{

    public static function start(){
        if(empty($_SESSION)){
            session_start();
        }
    }

    public static function destroy(){
        if(!empty($_SESSION)){
            session_destroy();
        }
    }

    public static function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key, $arrayKey = false){

        if($arrayKey == true){
            if(isset($_SESSION[$key][$arrayKey])){
                return $_SESSION[$key][$arrayKey];
            }
        }else{
            if(isset($_SESSION[$key])){
                return $_SESSION[$key];
            }
        }
        return false;
    }

    public static function delete($key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
        return false;
    }

    public static function display(){
        if(isset($_SESSION)) {
            return $_SESSION;
        }
        return false;
    }



}