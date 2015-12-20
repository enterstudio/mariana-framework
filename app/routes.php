<?php
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 12/18/2015
 * Time: 9:41 PM
 */

use Mariana\Framework\Router;

# VALID GET REQUESTS
Router::get("/",array(
    "controller"    =>  "TestController",
    "method"        =>  "index"
));
Router::get("/home/",array(
    "controller"    =>  "TestController",
    "method"        =>  "index_2"
));
Router::get("/home/user/",array(
    "controller"    =>  "TestController",
    "method"        =>  "index_3"
));
Router::get("/home/{userid}/",array(
    "controller"    =>  "TestController",
    "method"        =>  "index_4"
));
Router::get("/home/user/{userid}/",array(
    "controller"    =>  "TestController",
    "method"        =>  "index_5",
    "middleware"    =>  array("method")
));

# VALID POST REQUESTS
Router::post("/",array(
    "controller"    =>  "controller"
));

# DEFAULT REQUEST - 1 ALLOWED EACH
Router::$defaultGet = "/";
Router::$defaultPost = "/";