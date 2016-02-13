<?php namespace Mariana\Framework;

use Mariana\Framework\Design\Singleton;

class Config extends Singleton{
    /**
     * Class Config
     * @package Mariana\Framework
     * @desc gets or stores configuration stuff.
     */

    protected static $settings = array();

    public static function get($key){
        return isset(self::$settings[$key]) ?
            self::$settings[$key] :
            null;
    }

    public static function set($key,$value){
        self::$settings[$key] = $value;
    }

}