<?php namespace Mariana\Framework\Session;

use Mariana\Framework\Config;


class Flash extends Session{
    /**
     * Class Flash
     * @desc Flash Messages are session messages that display only once (example, error messages)
     * @package Mariana\Framework\Session
     */

    static $messageArray = array();

    public static function setMessages(Array $flashMessages){
        /**
         * @param array $flashMessages
         * @desc sets $_SESSION['flash'] with an array of messages (
         */
        self::set('flash',$flashMessages);
    }

    public static function showMessages(){
        /**
         * @return array|bool
         */

        if(isset($_SESSION["flash"])){
            static::$messageArray = $_SESSION["flash"];
            self::delete("flash");
            return self::$messageArray;
        }
        return false;
    }

}