<?php

use Mariana\Framework\Config;

# Base Settings
Config::set("website","http://pihh.rocks");
Config::set("base-route","/framework/");    # Base route -> cleans everything before this on our routing system
Config::set("mode","dev");                  # Production or development ( you can allways set a $_SESSION["dev"] variable and modify this as you want )

# Developer Settings vs Production Settings
if(Config::get("mode") === "dev"){
    // set error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

}else{
    // set error reporting
    //ini_set('error_reporting', E_STRICT); some errors only
    ini_set('display_errors', 0);
    error_reporting(0);
}

# Database connection
Config::set("database",array(
    "driver"    =>  "mysql",
    "host"      =>  "localhost",
    "database"  =>  "citypost",
    "username"  =>  "root",
    "password"  =>  "",
    "charset"   =>  "utf8",
    "collation" =>  "utf8_unicode_ci",
    "prefix"    =>  ""
));

# Email Configuration
Config::set("email", array(
    "smtp-server"   =>  "smtp.gmail.com",
    "port"          =>  "587",
    "timeout"       =>  "30",
    "email-login"   =>  "pihh.rocks@gmail.com",
    "email-password"=>  "92834C8fe6dd125ed5c64d2b6c",
    "email-replyTo" =>  "pihh.rocks@gmail.com",
    "website"       =>  Config::get("website"),
    "charset"       =>  "windows-1251"
));

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