<?php namespace Mariana\Framework\ORM;

use Mariana\Framework\Database;

class MarianaORM extends Database{

    public static $connection;
    protected static $table;
    public $db;

    public function __construct(){
        echo "construct";
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
            // unset db from object
            unset($this->db);

            // Set the id to the user
            if(!$this->id){
                $this->id = self::$connection->lastInsertId();
            }

            return $this;// return the object
        }else{
            return false;
        }

    }

    public static function where($column,$value){
        // Declarar a array de objectos vazia
        $obj = [];

        $query = "SELECT * FROM ".static::getTable()." WHERE ".$column." = :".$column;
        $db = self::getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam($column,$value);
        $stmt->execute();

        $class = get_called_class();

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){;
            // Criar novo objecto

            $object = new $class($row);
            // Tirar a base de dados
            unset($object->db);

            foreach ($row as $key => $value){
                $object->{$key} = $value;
            }
            $obj[] = $object;

        }

        return $obj;
    }

    public static function find($id){
        return self::where("id" , $id)[0];
    }

    public function findAndUpdate($id, Array $update){

        $class = get_called_class();

        $obj = $class::find($id);

        $data = $obj->data;
        $obj = new $class($data);
        $obj->id = $id; // Sign the id to update

        foreach($update as $key => $value){
            $obj->$key = $value;
        }
        $obj = $obj->save();

        return $obj;
    }

    public static function all(Array $params = array()){


        $query_attach ="";
        if(sizeof($params) > 0){
            $query_attach = " WHERE ".$params[0] ." ".$params[1]." :".$params[0];
        }

        $query = " SELECT * FROM ".self::getTable()." ".$query_attach;

        echo $query;

        $db = self::getConnection();
        $stmt = $db->prepare($query);

        //foreach($params as $param) {
          //  print_r($param);
            $stmt->bindParam($params[0], $params[2]);
        //}

        $stmt->execute();

        $class = get_called_class();
        $obj = [];

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){;
            // Criar novo objecto
            $object = new $class($row);
            // Tirar a base de dados
            unset($object->db);

            foreach ($row as $key => $value){
                $object->{$key} = $value;
            }
            $obj[] = $object;
        }

        return $obj;
    }
}