<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 17/01/2016
 * Time: 10:56
 *
 * @Settings: past this snippet on app/config.php
 *
 */

Config::set("session",array(
    "https" => true,
    "user_agent" =>  true,
    "lifetime"  =>  7200, //seconds
    "cookie_lifetime" => 0, //[(0:Clear the session cookies on browser close)
    "refresh_session" =>  600, //regenerate Session Id
    "table"         =>"sessions",
    "salt"          => 'salt'
));