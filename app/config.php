<?php

use Mariana\Framework\Config;

require_once(ROOT . DS . "app" . DS . "routes.php");
require_once(ROOT . DS . "app" . DS . "database.php");

# Base route -> cleans everything before this on our routing system
Config::set("base-route","/framework/");

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

# Database connection

Config::set("database",array(
    "driver"    =>  "mysql",
    "host"      =>  "localhost",
    "database"  =>  "citypost",
    "username"  =>  "pihh",
    "password"  =>  "",
    "charset"   =>  "utf8",
    "collation" =>  "utf8_unicode_ci",
    "prefix"    =>  ""
));