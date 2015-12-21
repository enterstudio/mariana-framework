<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Mariana\Framework\Config as Config;

//  Eloquent Connection
$capsule = new Capsule();
$config = Config::get("database");

$capsule->addConnection([
    'driver' => $config["driver"],
    'host' => $config["host"],
    'database' => $config["database"],
    'username' => $config["username"],
    'password' => $config["password"],
    'charset'   => $config["charset"],
    'collation' => $config["collation"],
    'prefix'    => $config["prefix"],
]);

$capsule->setAsGlobal();    //activate static methods
/*
 * $capsule->setCacheManager();
 * $capsule->setEventDispatcher();
 */
$capsule->bootEloquent();

//  Regular Connection defined at Mariana-framework\Framework\database.php