<?php


class Db {
    public static $conn;
    public static function conn(){
        self::$conn = new PDO("mysql:host=localhost;dbname=citypost", "pihh", "");
    }
}

class Users extends Model{


}

class  Model extends Db{

    protected $query;
    protected $data;
    protected $primary = "id";

    public static function find(){
        // Gets by primary
    }

    public static function where($column,$value, $selector = false){
        $class = get_called_class();
        $class = new $class();

        $class->query = "SELECT * FROM users WHERE id = 1 ";
        $class->data["a"] = "b";

        return $class;
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


$u = Users::where()->also();
echo "a";
print_r($u);

echo "b";