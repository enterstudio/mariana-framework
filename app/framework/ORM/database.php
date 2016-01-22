<?php namespace Mariana\Framework;

use PDO;
use Mariana\Framework\Config as Config;
use Mariana\Framework\Design\Singleton as Singleton;

class Database extends Singleton {

    /*
    Get an instance of the Database
    @return Instance
    */
    public static $connection;

	// Constructor
	// Get mysqli connection
	public static function getConnection() {
            # Get connectiont type
            $driver = Config::get("database-driver");
            $config = Config::get("database");

            # Mysql Connection Parameters
            if($driver == "mysql") {
                try {
                    static::$connection = new \PDO("mysql:host=" . $config["host"] . ";dbname=" . $config["database"], $config["username"], $config["password"]);

                } catch (\PDOException $e) {
                    echo $e->getMessage();
                    exit;
                }
            }

            #SQLite3 Connection Parameters
            if($driver == "SQLite3"){
                try {
                    //static::$connection = new \SQLite3($_SERVER["DOCUMENT_ROOT"]."/app/files/".$config["database"].".sq3");
                    static::$connection = new \SQLite3($_SERVER["DOCUMENT_ROOT"]."/app/files/".$config["database"].".sq3");
                } catch (\PDOException $e) {
                    echo $e->getMessage();
                    exit;
                }
            }

        # Common Settings :
        //static::$connection->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return static::$connection;
    }
}