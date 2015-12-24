<?php namespace Mariana\Framework;

use PDO;
use Mariana\Framework\Config as Config;
use Mariana\Framework\Design\Singleton as Singleton;

class Database extends Singleton {

    /*
    Get an instance of the Database
    @return Instance
    */
    public static $connection = false;

	// Constructor
	public function __construct() {
        "constructed";
    }
	// Get mysqli connection
	public static function getConnection() {

            $config = Config::get("database");
            try{
                self::$connection = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"],$config["username"],$config["password"]);
                self::$connection->setAttribute( \PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            }
            catch (PDOException $e){
                echo $e->getMessage();
                exit;
            }

        return self::$connection;
    }
}