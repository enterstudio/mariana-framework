<?php

# Defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)));
define('VIEW_PATH', ROOT.DS."mvc".DS."views");

# Getting the urls for the MVC
$url = $_SERVER["REQUEST_URI"];

# including the required filesystem
function require_all ($path) {
    require_once(ROOT.DS."vendor".DS."autoload.php");
    foreach (glob($path.'*.php') as $filename) require_once $filename;
}

require_all("app/");

# BOOT THE APP
use Mariana\Framework\App;
use Mariana\Framework\View;

App::run();
View::render('pages'.DS.'home');

?>



