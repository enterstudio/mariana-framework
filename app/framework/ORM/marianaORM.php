<?php namespace Mariana\Framework\ORM;

use Mariana\Framework\Database;

class MarianaORM extends Database{

    public static $connection;
    protected static $table;

    public function __construct(){
        self::getTable();
    }

    public static function getTable(){
        if(!isset(static::$table)) {
            static::$table = strtolower(get_called_class());
        }
        echo static::$table;
    }

}