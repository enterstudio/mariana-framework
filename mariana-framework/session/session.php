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
        if(!is_array($value)){
            htmlspecialchars($value);
        }
        $_SESSION[htmlspecialchars($key)] = $value;
    }

    public static function get($key, $arrayKey = false){

        if($arrayKey == true){
            if(isset($_SESSION[$key][$arrayKey])){
                return htmlspecialchars($_SESSION[$key][$arrayKey]);
            }
        }else{
            if(isset($_SESSION[$key])){
                return htmlspecialchars($_SESSION[$key]);
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
            return htmlspecialchars(json_encode($_SESSION));
        }
        return false;
    }

    public static function check($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return false;
    }

    public static function set_csrf(){
        self::set('mariana-csrf',generate_random_string(rand(15,30)));
    }

    public static function csrf($token){
        if(self::check('mariana-csrf' && self::get('mariana-csrf') === $token)){
            self::delete('mariana-csrf');
            return true;
        }
        return false;
    }

}