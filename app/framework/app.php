<?php namespace Mariana\Framework;

use Mariana\Framework\Router;

class App{

    private $router;

    public static function run(){
        Database::$connection;
        Router::route();
    }
}