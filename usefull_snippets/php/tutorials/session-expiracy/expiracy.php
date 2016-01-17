<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 17/01/2016
 * Time: 10:50
 */

// Get the current Session Timeout Value
$currentTimeoutInSecs = ini_get("session.gc_maxlifetime");

// Change the session timeout value to 30 minutes  // 8*60*60 = 8 hours
ini_set("session.gc_maxlifetime", Config::get("session")["lifetime"]);
//————————————————————————————–

// php.ini setting required for session timeout.
ini_set("session.gc_maxlifetime",30);
ini_set("session.gc_probability",1);
ini_set("session.gc_divisor",1);

//if you want to change the  session.cookie_lifetime.
//This required in some common file because to get the session values in whole application we need to        write session_start();  to each file then only will get $_SESSION global variable values.
$sessionCookieExpireTime= 8*60*60;
session_set_cookie_params($sessionCookieExpireTime);
session_start();

// Reset the expiration time upon page load //session_name() is default name of session PHPSESSID
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
    //————————————————————————————–
    //To get the session cookie set param values.

    $CookieInfo = session_get_cookie_params();

    echo "<pre>";
    echo "Session information session_get_cookie_params function :: <br />";
    print_r($CookieInfo);
    echo "</pre>";
}