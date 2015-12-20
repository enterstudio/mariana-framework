<?php

# Defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)));
define('VIEW_PATH', ROOT.DS."mvc".DS."views");

# Getting the urls for the MVC
//$url = $_SERVER["REQUEST_URI"];

# including the required filesystem

require_once(ROOT.DS."vendor".DS."autoload.php");
require_once(ROOT.DS."app/app.php");

# BOOT THE APP
use Mariana\Framework\App;
use Mariana\Framework\View;

App::run();
View::render('pages'.DS.'home');

?>



