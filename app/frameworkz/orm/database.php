<?php namespace Mariana\Framework;

use PDO;
use Mariana\Framework\Design\Singleton as Singleton;

class Database extends Singleton {

    /*
    Get an instance of the Database
    @return Instance
    */
    public static $connection;
    public static $connection_status;

	// Constructor
	// Get mysqli connection
	public static function getConnection() {

        // Se a conexão não estiver feita,
        if(!static::$connection) {

            $config = Config::get('database');
            $driver = $config['driver'];

            // Buscar parametros da driver e da base de dados

            if ($driver == 'mysql') {

                try {
                    static::$connection = new \PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['database'], $config['username'], $config['password']);
                    static::$connection->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    static::$connection_status = self::$connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    exit;
                }
            }

            if ($driver === 'SQLite3') {
                try {
                    static::$connection = new \PDO('sqlite:'.ROOT.DS.'app'.DS.'files'.DS.'database'.DS.'databases'.DS.$config['database'].'.sq3');
                    static::$connection_status = self::$connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    exit;
                }
            }

        }

        return static::$connection;
    }
}