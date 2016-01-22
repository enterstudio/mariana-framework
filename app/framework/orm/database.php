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

        // Se a conexão não estiver feita,
        if(!static::$connection) {
            // Buscar parametros da driver e da base de dados
            $driver = Config::get("database-driver");
            $config = Config::get("database");

            if ($driver == "mysql") {
                try {
                    static::$connection = new \PDO("mysql:host=" . $config["host"] . ";dbname=" . $config["database"], $config["username"], $config["password"]);
                    static::$connection->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    exit;
                }
            }

            if ($driver == "SQLite3") {
                try {
                    static::$connection = new \PDO("sqlite:".ROOT.DS."app".DS."files".DS."database".DS."databases".DS.$config["database"].".sq3");
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    exit;
                }
            }
        }

        return static::$connection;
    }
}