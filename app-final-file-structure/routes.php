<?php
/**
 * @Desc: Mariana's Framework routing system, designed to be as simple as possible
 * @Requests: get and post requests only.
 * @By: Filipe Mota de Sá AKA Pihh - pihh.rocks@gmail.com
 * 
 * @example:
 Router::get('/home/user/{userid}/',array(
    'controller'    =>  'TestController',
    'method'        =>  'index_5',
    'middleware'    =>  array(
        'before'    =>  array('method 1'),
        'after'     =>  array('methods')
    )
 ));
 * 
 */

use Mariana\Framework\Router;

# VALID GET REQUESTS
Router::get('/',array(
    'controller'    =>  'TestController',
    'method'        =>  'index'
));

# VALID POST REQUESTS
Router::post('/',array(
    'controller'    =>  'TestController',
    'method'        =>  'post_test'
));

# DEFAULT REQUEST - 1 ALLOWED EACH
Router::$defaultGet = '/';
Router::$defaultPost = '/';