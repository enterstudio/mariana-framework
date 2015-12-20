<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();
$capsule->addConnection([
    'driver' => Config::get("driver"),
    'host' => Config::get("host"),
    'database' => Config::get("database"),
    'username' => Config::get("username"),
    'password' => Config::get("password"),
    'charset'   => Config::get("charset"),
    'collation' => Config::get("collation"),
    'prefix'    => Config::get("prefix"),
]);

$capsule->setAsGlobal();    //activate static methods
/*
 * $capsule->setCacheManager();
 * $capsule->setEventDispatcher();
 */
$capsule->bootEloquent();