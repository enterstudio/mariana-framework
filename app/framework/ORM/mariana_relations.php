<?php namespace Mariana\Framework\ORM;
use Mariana\Framework\Database;

class ORM extends Database{

    public static $connection;
    protected static $table;

    public function __construct(){
        self::getConnection();
        self::getTable();
        echo "XXXXXXXXXXXX";
    }

    public static function getTable(){
        if(!isset(static::$table)) {
            static::$table = strtolower(get_called_class());
        }
        echo static::$table; // Variaveis estáticas faz-se echo com static
    }

    public function save(){
        $values = $this->getColumns();

        $filtered = null;
        foreach ($values as $key=>$value) {
            if($value !== null && is_integer($key) && $value !== '' && str_pos($key, 'obj_') === false && $key !== ""){
                if($value === false){
                    $value = 0;
                }
                $filtered[$key] = $value;
            }
        }

    }
}