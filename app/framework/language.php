<?php
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 2/7/2016
 * Time: 7:37 PM
 */

namespace Mariana\Framework\Language;


class Lang
{
    public static $array = array();

    public static function set($lang){
        self::$array = $lang;
    }

    public static function get($id){
        return self::$array[$id];
    }
}