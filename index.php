<?php

# Get base path
$framework_root = (trim(trim(str_replace($_SERVER["DOCUMENT_ROOT"], "", __DIR__),"\\"),"/"));
(strlen($framework_root) >0 ) ? $framework_root = "/".$framework_root."/" : $framework_root = "/";

# Defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)));
define('FRAMEWORK_ROOT', $framework_root);
define('VIEW_PATH', ROOT.DS."mvc".DS."views");


# including the required filesystem and booting the framework
require_once(ROOT.DS."vendor".DS."autoload.php");
require_once(ROOT.DS."app".DS."functions.php");

use Mariana\Framework\App;
App::run();

?>
