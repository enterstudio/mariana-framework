<?php
/**
 * Created by PhpStorm.
 * User: pihh
 * Date: 12/02/2016
 * Time: 23:37
 */

namespace Mariana\Framework\DatabaseManager;

use Mariana\Framework\Database;
use Mariana\Framework\Config;
use PDO;

class DatabaseManager{

    private static $extension = 'sql';

    private static function setup(){
        self::checkDev();
        self::getExtension();
    }

    private static function getExtension(){
        $driver = Config::get('database')['driver'];
        if($driver == 'mysql'){
            self::$extension = '.sql';
        }elseif($driver == 'SQLite3'){
            self::$extension = '.sq3';
        }
    }

    private static function checkDev(){
        if(Config::get('mode')!== 'dev'){
            echo 'Database management functionality only available in dev mode';
            die();
        }
    }

    private static function makeFile($path, $contents){
        /**
         * @param $path
         * @param $contents
         * @desc if file doesn't exist, creates a file and writes it's contents
         */

        $my_file = $path;
        $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$path);
        $data = $contents;
        fwrite($handle, $data);
        fclose($handle);

    }

    public static function createDatabase($name){

        self::setup();

        $template = 'CREATE DATABASE '.$name.'
CHARACTER SET '.Config::get('database')['charset'].'
COLLATE '.Config::get('database')['collation'].'
';

        $dir_name = ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'databases'.DS.$name;
        $path = $dir_name.DS.$name.'.'.self::$extension;

        $contents = $template;

        if(!is_dir($dir_name)){
            mkdir($dir_name, 0700);
        }

        self::makeFile($path,$contents);

        try {

            $config = Config::get('database');
            $user = $config['username'];
            $pass = $config['password'];
            $host = $config['host'];

            $db = new PDO("mysql:host=$host", $user, $pass);

            $db->exec("CREATE DATABASE `$name`;
                CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
                GRANT ALL ON `$name`.* TO '$user'@'localhost';
                FLUSH PRIVILEGES;")
            or die(print_r($db->errorInfo(), true));

        } catch (PDOException $e) {
            die("DB ERROR: ". $e->getMessage());
        }
    }

    public static function createTable($name){

        self::setup();
        /**
         * @ShouldDo:
         *  1- create folder at app/files/database/tables/$database_name/$name
         *  2- create file $name.php at app/files/database/tables/$database_name/
         *  3- create file $name_time().php at app/files/database/$database_name/$name
         */
        $templatePHP = "<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  */

  # Table Name
  \$table = ".$name.";

  # Table Fields
  \$fields = array(
        'id'              =>  'INTEGER PRIMARY KEY',
        'date_created'    =>  'TIMESTAMP',
        'last_updated'     =>  'TIMESTAMP',
  );

  # Table Seeds
  \$seeds = array();

  return array(
        'fields' => \$fields,
        'seeds'  => \$seeds
  );

?>";

        //STEP 1 - Create the folders
        $dir_name = ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'tables'.DS.Config::get('database')['database'];

        if(!is_dir($dir_name)){
            mkdir($dir_name, 0700);
        }

        $dir_name2 = ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'tables'.DS.Config::get('database')['database'].DS.$name;

        if(!is_dir($dir_name2)){
            mkdir($dir_name, 0700);
        }

        //STEP 2 - Create The Files
        //The php file
        $path = $dir_name.DS.$name.'.php';
        self::makeFile($path,$templatePHP);

        //The sql File
        $templateSQL = self::createSQL($name);
        $path = $dir_name2.DS.$name.'_'.time().'.'.self::$extension;
        self::makeFile($path,$templateSQL);
        //Second dir é a pasta de ficheiros para cada tabela


    }

    public static function updateTable($name){

    }

    public static function seedTable($name){

    }

    public static function createSQL($name){
        $fields = array();
        include_once(ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'tables'.DS.Config::get('database')['database'].DS.$name.'.php');
        $config = Config::get('database');
        $sql = "CREATE TABLE `".$config['database']."`.`$name` ( ";

        foreach($fields as $key => $pair){
            $sql .= "`$key` $pair ,";
        }
        $sql = trim($sql,',');
        $sql .= ' ) ENGINE = '.$config['engine'];

        return $sql;
    }
}