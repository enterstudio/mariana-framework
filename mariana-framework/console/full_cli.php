<?php
// CLI Script
define('ROOT', realpath(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

include_once (ROOT.DS.'mariana-frameworkz'.DS.'console'.DS.'marianaCLISetup.php');
include_once (ROOT.DS.'mariana-frameworkz'.DS.'console'.DS.'marianaCLI.php');
/**
 * TODO:
 *  Database Controll
 *  Composer dump-autoload DunnoWhy but it's failing
 *  Run DB Manager when create table and seed
 *  On create Database, Create Seed for database version control
 *
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
use Mariana\Framework\DatabaseManager\DatabaseManager;

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
class CLI{

private $cli_args = array();
private $cli_options = array(
"help",
"server",
"server:",
"create:",
"update:",
"migrate",
"seed"
);
public function __construct(Array $params= array()){

$this->cli_args = $params;
$this->checkForValidCommand();
}

# Templates
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
private $tempalte_help = <<<USAGE
MARIANA COMMAND LINE HELPER
Have suggestions? Talk with us on pihh.rocks@gmail.com

OPTIONS:
help: Print this help

server: Start php server on this directory. You can customize the server settings by running: cli server XX - This will run http://localhost:XX

create: Creates a file with proper setup (replace NAME with it's name).
- create:database NAME
- create:table NAME - (keep it's name as plural EX: users - also creates a model)
- create:controller NAME - (will be named as nameController)
- create:model NAME - (keep it's name as plural EX: users)
- create:middleware NAME

update: Updates database tables
- seed
- drop
\n
USAGE;

private function template_table($name){
/**
 * @param string $name
 * @desc creates a database seed
 *
return
    "<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  *

  # Table Name
  \$table = ".$name.";

  # Table Fields
  \$fields = array(
        'id'            =>  'INTEGER PRIMARY KEY',
        'timestamp'     =>  'TIMESTAMP'
  );


  # Table Seeds
  \$seeds = array(
        array('1', 'CURRENT_TIMESTAMP'),
  );

  return array(
        'fields' => \$fields,
        'seeds'  => \$seeds
  );

?>";
}

private function template_controller($name){
    /**
     * @param string $name
     * @desc creates a database seed
     *
    return
        "<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  *

use Mariana\\Framework;
use Mariana\\Framework\\Controller;
use Mariana\\Framework\\Database;

class $name extends Controller{

    /**
     * Default method;
     *
     public function index(){

     }

}
?>";
}

private function template_model($name, $table){
    /**
     * @param string $name
     * @desc creates a database seed
     *
    return
        "<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  *

use \\Mariana\\Framework\\Model;

class $name extends Model{

//protected static \$table = '$table';
//protected static \$primary;

}
?>";
}

private function template_middleware($name){
    /**
     * @param string $name
     * @desc creates a database seed
     *
    return
        "<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  *

use Mariana\\Framework\\Middleware;

class $name extends Middleware{


}
?>";
}


# AUXILIAR FUNCTIONS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

private function checkIfFileExists($path){

    /**
     * @param $path
     * @return bool
     *

    if(file_exists($path)) {
        echo "\nFile allready exists in $path. Please delete it or rename your new file.\n";
        exit();
    }
    return false;
}

private function makeFile($path, $contents){
    /**
     * @param $path
     * @param $contents
     * @desc if file doesn't exist, creates a file and writes it's contents


    $my_file = $path;
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$path);
    $data = $contents;
    fwrite($handle, $data);
    fclose($handle);

}

private function parseName($name){
    /**
     * @param $name
     * @param bool|false $parsingType
     * @desc: parses the name as the convention says
     * @return string $name
     *

    $name = str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    $name = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));

    return $name;
}

private function checkForValidCommand(){

    /**
     * @param $commands
     * @param $options
     * @desc checks if command is valid, if not, returns help.
     * @return command() | help()
     *

    # Command String threatment
    $command = strtolower(trim($this->cli_args[0]));

    foreach ($this->cli_options as $o) {
        # Option String treatment
        $o = strtolower(trim($o));

        # Checks for commands that don't have :
        if($o == $command && strpos($o, ':') !== 0) {
            return $this->{$o}();
        }

        # Checks for composite commands ( the ones who have : )
        if(strpos($command, $o) === 0){

            # String threatment
            # Remove current command from the cli_args
            array_shift($this->cli_args);

            $decouple = explode(":",$command);
            $command = $decouple[0];
            $command_type = $decouple[1];
            $command_args = $this->cli_args;


            return $this->{$command}($command_type , $command_args);
        }
    }

    return $this->help();
}

# THE FUNCTIONS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
private function help(){

    echo $this->tempalte_help;
    exit;
}

private function server($port = false){
    /**
     * @param bool $port
     * @return string
     * @default port 314 (port called because of my name - pi 3.14)
     * @desc starts php server on this folder and opens browser
     *
    ($port == false)?
        $port = 314:
        $port = $port;

    echo ("\nServer running at http://localhost:$port.\n To quit press CTRL + C");

    $command_1 = "start /max http://localhost:$port";
    $command_2 = "php -S localhost:$port";

    return exec($command_1."&& ".$command_2);
}

public function create($what, Array $args = array()){
    /**
     * @param $what
     * @param array $args
     * @return bool|void
     * @desc creates files and runs composer dump-autoload function
     *

    $what = strtolower(trim($what));

    $possibleCreations = array(
        "database",
        "table",
        "model",
        "controller",
        "middleware"
    );

    if(!in_array($what,$possibleCreations)){
        echo "\nWARNING: It's not possible to create $what . More info: php mariana help\n";
        return true;
    }

    if(empty($args)){
        echo "\nWARNING: Cannot create $what whitout giving it a name. More info: php mariana help\n";
        return true;
    }else{
        $name = $args[0];
    }

    # THE FUNCTIONS

    # CREATE CONTROLLER
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($what == "controller"){

        $file_name = strtolower($name);
        $name = $this->parseName($name);
        $path = ROOT.DS."mvc".DS."controllers".DS.$file_name.".controller.php";

        $this->checkIfFileExists($path);

        $contents = $this->template_controller($name);

        $this->makeFile($path, $contents);

        echo "\nCreated controller: $name in $path.\n";
        return $this->composer();

    }

    # CREATE MODEL
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($what == "model") {
        $file_name = strtolower($name);
        $name = $this->parseName($name);
        $path = ROOT.DS."mvc".DS."models".DS.$file_name.".model.php";

        $this->checkIfFileExists($path);

        $contents = $this->template_model($name,$file_name);

        $this->makeFile($path, $contents);

        echo "\nCreated model: $name in $path.\n";
        return $this->composer();

    }

    # THE MIDDLEWARE
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($what == "middleware") {
        $file_name = strtolower($name);
        $name = $this->parseName($name);
        $path = ROOT.DS."mvc".DS."middlewate".DS.$file_name.".php";

        $this->checkIfFileExists($path);

        $contents = $this->template_middleware($name);

        $this->makeFile($path, $contents);

        echo "\nCreated middleware: $name in $path. \n";
        return $this->composer();
    }

    # CREATE DATABASE
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($what == "database"){
        /**
         * @Should Do:
         *  1- Create database
         *  2- Create file at app/files/database/databases/$name/$name
         *
        $name = strtolower($this->parseName($name));

        DatabaseManager::createDatabase($name);
        echo "Created database: $name. The export database file is at: app/files/database/databases/$name";
        // Confirmations:

        return true;
    }   # Done and tested



    # CREATE TABLE
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if($what == "table"){

        DatabaseManager::createTable($name);
        return true;
    }


    return help();
}


public function update($what, Array $args = array()){

    if(empty($args)){
        echo "\nWARNING: Cannot update table whitout proper naming it. More info: php mariana help\n";
        return true;
    }else{
        $name = $args[0];
    }
    DatabaseManager::updateTable($name);
    return true;
}

public function migrate($database= false){
    if($database == false){
        $database = Config::get('database');
    }
    DatabaseManager::migrate($database);
}

public function seed($table){
    DatabaseManager::seedTable($table);
}

private function composer(){
    /**
     * @desc runs composer dump-autoload
     *
    return exec("composer.phar dump-autoload");
}

}




# STARTUP
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cli = new CLI($commands);


 exit();
*/