<?php namespace Mariana\Framework;

use Mariana\Framework\Router;
use Mariana\Framework\Security\Environment;
use Mariana\Framework\Session\Session;

class App{

    private $router;

    public static function run(){
        # Session start
        Session::start();

        # Setup
        Environment::setup();

        # Load dependencys
        require_once(ROOT . DS . "app" . DS . "routes.php");
        require_once(ROOT . DS . "app" . DS . "config.php");

        # Connect
        Database::$connection;

        # Route
        Router::route();
    }
}