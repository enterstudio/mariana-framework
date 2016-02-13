<?php
/**
 * TODO:
 *  Database Controll
 *  Composer dump-autoload DunnoWhy but it's failing
 *  Run DB Manager when create table and seed
 *  On create Database, Create Seed for database version control
 */
# SETUP
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

# REMOVE ERROR DISPLAY
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

# DEFINE SOME VARIABLES
define('ROOT', realpath(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

# GET AUTOLOAD
require_once(ROOT.DS."vendor".DS."autoload.php");
require_once(ROOT.DS."app".DS."functions.php");

# DEFINE USAGES
use Mariana\Framework\Config;
use Mariana\Framework\Security\Environment;
use Mariana\Framework\Database;

# BOOT AND VALIDATE THE COMMAND LINE INTERFACE
Environment::setup();

require_once(ROOT . DS . "app" . DS . "config.php");

if(Config::get("mode")!== "dev"){
    echo "Console only available on dev mode, please configure your application";
    exit();
}

# SET OPTIONS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//(Config::get("mode")== "dev")?: exit() ;

$options = array();
$options[] = "help";
$options[] = "server";
$options[] = "create";
$options[] = "update";

# SET COMMANDS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
array_shift($argv);
$commands = $argv;


# CLI CLASS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////