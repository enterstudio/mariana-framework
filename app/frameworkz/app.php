<?php namespace Mariana\Framework;

use Mariana\Framework\Language\Lang;
use Mariana\Framework\Router;
use Mariana\Framework\Security\Environment;
use Mariana\Framework\Session\Session;

class App{
    /**
     * Class App
     * @package Mariana\Framework
     * @desc Bootstrap for the frameworkz
     */
    
    public static function run(){
        # Session start
        Session::start();

        # Setup
        Environment::setup();

        # Load dependencys
        require_once(ROOT . DS . 'app' . DS . 'routes.php');
        require_once(ROOT . DS . 'app' . DS . 'config.php');

        # Set Language and import translation files (LANG)
        self::setLanguage();

        # Connect
        Database::$connection;

        # Route
        Router::route();
    }

    private static function setLanguage(){
        if(!Session::get('application-language')){
            $lang = get_browser_language();

            # Set the language
            (in_array($lang,Config::get('language')['allowed-languages']))?
                Session::set('application-language', $lang) :
                Session::set('application-language', Config::get('language')['default-language']);

        }
        include_once(ROOT.DS.'app'.DS.'languages'.DS.Session::get('application-language').'.php');

        Lang::set($marianaFrameworkLanguageArray);
    }
}