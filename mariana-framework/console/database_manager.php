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
        $handle = fopen($my_file, 'w'); // or die('Cannot open file:  '.$path);
        $data = $contents;
        fwrite($handle, $data);
        fclose($handle);

    }

    public static function createDatabase($name= false){

        self::setup();

        # Vars
        $config = Config::get('database');
        $user = $config['username'];
        $pass = $config['password'];
        $host = $config['host'];
        if($name == false){
            $name = $config['database'];
        }

        $template = "CREATE DATABASE IF NOT EXISTS `$name`
                CHARACTER SET ".Config::get('database')['charset']."
                COLLATE ".Config::get('database')['collation'].";
                CREATE USER '$user'@'$host' IDENTIFIED BY '$pass';
                GRANT ALL ON `$name`.* TO '$user'@'$host';
                FLUSH PRIVILEGES;";

        $dir_name = ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'databases'.DS.$name;
        $path = $dir_name.DS.$name.'.'.self::$extension;

        $contents = $template;

        if(!is_dir($dir_name)){
            mkdir($dir_name, 0700);
        }

        self::makeFile($path,$contents);

        try {

            $db = new PDO("mysql:host=$host", $user, $pass);
            $db->exec($template)
            or die(print_r($db->errorInfo(), true));
            echo "SUCCESS: Database $name created! ".PHP_EOL.PHP_EOL;

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
  \$table = '$name';

  # Table Fields
  \$fields = array(
        'id'              =>  'INTEGER PRIMARY KEY',
        'date_created'    =>  'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'last_updated'     =>  'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
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
            mkdir($dir_name2, 0700);
        }

        //STEP 2 - Create The Files
        //The php file
        $path = $dir_name.DS.$name.'.php';

        self::makeFile($path,$templatePHP);

        //The sql File
        $templateSQL = self::createSQL($name);
        $name_time = $name.'_'.time();
        $path = $dir_name2.DS.$name_time.self::$extension;
        self::makeFile($path,$templateSQL);
        //Second dir é a pasta de ficheiros para cada tabela

        // Run PDO to create the table
        $stmt = Database::getConnection()->prepare($templateSQL);
        if($stmt->execute()){
            echo "SUCCESS: Table $name successfully created!".PHP_EOL.PHP_EOL;
            echo "SUCCESS: Main for database management: $name.php successfully created and stored at $dir_name !".PHP_EOL.PHP_EOL;
            echo "SUCCESS: Database version control file: $name_time.sql successfully created and stored at $dir_name2 !".PHP_EOL.PHP_EOL;
            return true;
        }

        echo 'This process was not finnished. Please try again or send email to pihh.rocks@gmail.com';
        return false;

    }

    public static function updateTable($name){
        self::setup();
        # names:
        $ext = self::$extension;
        $sql_name = $name.'_'.time().$ext;
        $dir_name = ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'tables'.DS.Config::get('database')['database'].DS.$name;
        $dir_name_php = "$dir_name.php";
        $dir_name_sql = $dir_name.DS.$sql_name;

        # Drop the table to create a new one
        $sql = "DROP TABLE `$name`";
        echo $sql;
        $stmt = Database::getConnection()->prepare($sql);
        if($stmt->execute()){
            echo "SUCCESS: Deleted table $name !".PHP_EOL.PHP_EOL;
        }

        # Generate the sql file out of the php file
        $sql = self::createSQL($name);
        self::makeFile($dir_name_sql,$sql);

        $stmt = Database::getConnection()->prepare($sql);
        if($stmt->execute()){
            echo "SUCCESS: Database migration file: $dir_name_sql !".PHP_EOL.PHP_EOL;
        }else{
            self::deleteSQL($dir_name_sql);
            echo "FAIL: Database migration file failed to create".PHP_EOL.PHP_EOL;
        }

        self::seedTable($name);
    }

    public static function seedTable($name){
        $seeds = array();
        $path_php = ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'tables'.DS.Config::get('database')['database'].DS.$name.'.php';
        
        include($path_php);

        if(sizeof($seeds)> 0){
            $before = '';
            $after = '';
            $i = 0;
            foreach($seeds as $seed){
                $before = '';
                $after = '';
                foreach($seed as $key => $pair) {
                    $before .= "`$key` ,";
                    $after .= ":$key ,";
                }

                $before = '('.trim($before,',').')';
                $after = '('.trim($after,',').')';

                $sql = "INSERT INTO $name $before VALUES $after";

                $stmt = Database::getConnection()->prepare($sql);
                foreach ($seed as $key => $pair) {
                        $stmt->bindParam(":$key", $pair);
                }

                if($stmt->execute()){
                    $i++;
                }
            }

            if($i > 0){
                echo "SUCCESS: Database table $name successfully seeded! ";
            }else {
                echo 'FAIL: Unable to seed the table...';
            }
            return true;
        }
        echo "Array \$seeds not set or empty in $path_php";
        return false;
    }

    public static function createSQL($name){
        $fields = array();
        include(ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'tables'.DS.Config::get('database')['database'].DS.$name.'.php');
        $config = Config::get('database');
        $sql = "-- table created at ".date('Y-m-d')." \n CREATE TABLE IF NOT EXISTS `".$config['database']."`.`$name` ( ";
        foreach($fields as $key => $pair){
            $sql .= "\n`$key` $pair ,";
        }
        $sql = trim($sql,',');
        $sql .= " )\n ENGINE = ".$config['engine'];

        return $sql;
    }

    public static function deleteSQL($path){
        if(is_file($path)){
            unlink($path);
        }
    }

    public static function migrate(){
        self::setup();
        # Vars
        $database = Config::get('database')['database'];
        $dir_tables = ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'tables'.DS.$database.DS;

        # Create the database
        self::createDatabase($database);

        # Get every php file in the directory
        $php = glob($dir_tables . "*.php");
        foreach($php as $p)
        {
            $file = str_replace('.php','',str_replace($dir_tables,'',$p));
            if(!is_dir($dir_tables.$file)){
                mkdir($dir_tables.$file, 0700);
            }
            $sql = "CREATE TABLE IF NOT EXISTS $file ( `id` INT NOT NULL ) ENGINE = InnoDB; ";
            Database::getConnection()->prepare($sql)->execute();
            self::updateTable($file);
        }
    }
}