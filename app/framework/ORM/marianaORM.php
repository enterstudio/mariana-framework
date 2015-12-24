<?php namespace Mariana\Framework\ORM;

use Mariana\Framework\Database;

class MarianaORM extends Database{

    public static $connection;
    protected static $table;
    protected $data;
    protected $db;

    public function __construct(){

    }

    public static function getTable(){
        if(!isset(static::$table)) {
            static::$table = strtolower(get_called_class());
        }
        return static::$table;
    }

    public function save(){

        $values =  $this->data;
        $filtered = null;   // Armazena as colunas

        foreach($values as $key => $value){
            // Verifica se é id
            if($value !== null && !is_integer($key) && $value !== '' && strpos($key, 'obj_') === false && $key !== "id"){
                if($value === false){
                    $value = 0;
                }
                $filtered[$key] = $value;
            }
        }

        $columns = array_keys($filtered);

        if($this->id){
            $params = "";
            foreach($columns as $column){
                $params .= $column." = :".$column.",";
            }
            $params = trim($params, ",");
            $query = "UPDATE ".$this->getTable()." SET $params WHERE id = ".$this->id;
        }else{
            $params = join(", :",$columns);
            $params =":".$params;
            $columns= join(", ", $columns);
            $query = "INSERT INTO ".$this->getTable()." ($columns) VALUES ($params)";
        }

        // Connect and do it
        $this->db = self::getConnection();

        $stmt = $this->db->prepare($query);
        foreach($filtered as $key => $value){
            $stmt->bindParam(":".$key,$value);
        }
        if($stmt->execute()){
            $this->id = self::$connection->lastInsertId();
            self::$connection = null;
            return true;
        }else{
            return false;
        }

    }
}