<?php
use Mariana\Framework\Config;

# Base Settings
Config::set("website","http://pihh.rocks");
Config::set("base-route","/framework/");            # Base route -> cleans everything before this on our routing system
Config::set("mode",getenv("mode"));                 # Production or development ( you can allways set a $_SESSION["dev"] variable and modify this as you want )

# Developer Settings vs Production Settings
if(Config::get("mode") == "dev"){
    // set error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    define("DEBUG", 1);
    define("LOG", 1);   //__FILE__ ; __LINE__ ; __FUNCTION__ ; __METHOD__ ; __CLASS__

}else{
    // unset error reporting | ini_set('error_reporting', E_STRICT); some errors only
    ini_set('display_errors', 0);
    error_reporting(0);
}

# Database connection
Config::set("database",array(
    "driver"    =>  "mysql",
    "host"      =>  $_ENV["DB_HOST"],
    "database"  =>  $_ENV["DB_DATABASE"],
    "username"  =>  $_ENV["DB_USERNAME"],
    "password"  =>  $_ENV["DB_PASSWORD"],
    "charset"   =>  "utf8",
    "collation" =>  "utf8_unicode_ci",
    "prefix"    =>  ""
));

# Email Configuration
Config::set("email", array(
    "smtp-server"   =>  $_ENV["MAIL_HOST"],
    "port"          =>  $_ENV["MAIL_PORT"],
    "timeout"       =>  "30",
    "email-login"   =>  $_ENV["MAIL_USERNAME"],
    "email-password"=>  $_ENV["MAIL_PASSWORD"],
    "email-replyTo" =>  "pihh.rocks@gmail.com",
    "website"       =>  Config::get("website"),
    "charset"       =>  "windows-1251"
));

# Security Settings
Config::set("hash", getenv("key"));

# Session configuration
Config::set("session",array(
    "https" => true,
    "user_agent" =>  true,
    "lifetime"  =>  7200, //seconds
    "cookie_lifetime" => 0, //[(0:Clear the session cookies on browser close)
    "refresh_session" =>  600, //regenerate Session Id
    "table"         =>"sessions",
    "salt"          => 'salt'
));