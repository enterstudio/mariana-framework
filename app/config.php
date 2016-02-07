<?php
use Mariana\Framework\Config;

# Base Settings
# Base route -> cleans everything before this on our routing system
# Production or development ( you can allways set a $_SESSION["dev"] variable and modify this as you want )
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
ini_set('display_errors', 0);
error_reporting(0);

Config::set('website',get_url());
Config::set('base-route',FRAMEWORK_ROOT);
Config::set('mode',getenv('mode'));

# Language Settings
Config::set('language', array(
    'default-language'  => 'en',
    'allowed-languages' => array('en','pt')
));

# Developer Settings Override production Settings
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(Config::get('mode') == 'dev'){

    Config::set('website','http://pihh.rocks');

    # SET ERROR REPORTING
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    # set debug and log
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT.DS.'app'.DS.'files'.DS.'logs'.DS.'error.log');
}


# Database Connection Settings.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Config::set('database-driver', 'mysql'); // mysql or SQLite3
Config::set('database', array(
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
));

# Session configuration
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Config::set('session',array(
    'https' => true,
    'user_agent' =>  true,
    'lifetime'  =>  7200, //seconds
    'cookie_lifetime' => 0, //[(0:Clear the session cookies on browser close)
    'refresh_session' =>  600, //regenerate Session Id
    'table'         =>'sessions',
    'salt'          => 'salt'
));

# Email Configuration
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Config::set('email', array(
    'smtp-server'   =>  $_ENV['MAIL_HOST'],
    'port'          =>  $_ENV['MAIL_PORT'],
    'timeout'       =>  '30',
    'email-login'   =>  $_ENV['MAIL_USERNAME'],
    'email-password'=>  $_ENV['MAIL_PASSWORD'],
    'email-replyTo' =>  'pihh.rocks@gmail.com',
    'website'       =>  Config::get('website'),
    'charset'       =>  'windows-1251'
));

# File Upload Configuration
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Config::set('file-upload', array(
    'allowed-file-types'        =>  array(
        'text/plain',
    ),
    'allowed-file-extensions'   =>  array(
        'txt',
    ),
    'max-file-size'             =>  2097152 // 2mb
));

# Cache Settings
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Config::set('cache-timeout', 60);

# Security Settings
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Config::set('hash', getenv('key'));
Config::set('security-report-email-address', getenv('SECURITY_REPORT_MAIL'));


