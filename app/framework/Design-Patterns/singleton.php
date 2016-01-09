<?php namespace Mariana\Framework\Design;


abstract class Singleton
{
    private static $instances = array();

    protected function __construct() {}

    protected function __clone() {}

    public function __wakeup()
    {
        throw new Exception("Cannot Unserialize Singleton");
    }

    public static function getInstance()
    {
        $class = get_called_class(); // late-static-bound class name
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static;
        }
        return self::$instances[$class];
    }
}