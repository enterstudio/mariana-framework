<?php


class Db {
    public static $conn;
    public static function conn(){
        self::$conn = new PDO("mysql:host=localhost;dbname=citypost", "pihh", "");
        return self::$conn;
    }
}

class Users extends Model{


}

class  Model extends Db{

    protected $query;
    protected $data;
    protected $primary = "id";
    protected $db;
    protected $table;

    public function __construct(){
        $this->db = DB::conn();
        $this->table = self::table();
    }

    public static function table(){
        return get_called_class();
    }

    public static function find(){
        // Gets by primary
    }

    public static function where($column,$value, $selector = false){
        $object = self::table();
        $object = new $object;

        if($selector == false){
            $selector = "=";
        }

        $object->query = "SELECT * FROM users WHERE id = 1 ";
        $object->data[$column] = "b";

        return $object;
    }

    public function also($colum, $value, $selector = false){
        // AND
        $this->query = $this->query." AND a = b";
        $this->data["b"] = "c";
        return $this;
    }

    public function join($direction = false, Array $condition = array()){
        // Junta logo na foreign USING
        // Direction inner, outer, left , left outer, natural, right , right outer;
    }

    public function joinDescription ($column, $value, $selector = false){

    }

    public function get($limit = false, $offset = false){

        //LIMIT y OFFSET x

    }
}


$u = Users::where("a","b")->also("c","d");

print_r($u);
