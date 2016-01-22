<?php


# Defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)));
define('VIEW_PATH', ROOT.DS."mvc".DS."views");

# including the required filesystem and booting the framework
require_once(ROOT.DS."vendor".DS."autoload.php");
require_once(ROOT.DS."app".DS."functions.php");

use Mariana\Framework\App;
App::run();

?>
