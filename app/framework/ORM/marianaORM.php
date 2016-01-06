<?php namespace Mariana\Framework\ORM;

use Mariana\Framework\Database;

class MarianaORM extends Database{

    protected $query;
    protected $data = array();
    protected $primary = "id";
    protected $db;
    protected $table;
    protected $obj;
    protected $offsetValue = "";

    protected $columnList = array(); // All Columns of the table that can be used in a query;
    private static $allowed_select_values = ["=",">",">=","<=","LIKE","NOT LIKE", "<>", "IN" , "BEETWEEN" , "IS NOT NULL", "NOT BEETWEEN", "!=", "!", "SOUNDS LIKE"];

    private $field_table;
    private $field_value;
    //  CONSTRUCTOR NO MODEL

    //  STATIC METHODS
    public static function table(){
        return get_called_class();
    }

    public static function find(){
        // Gets by primary
    }

    public function update(){}

    public function delete(){}

    public function all(){}

    public static function where($column,$value, $selector = false){
        //PDO ESCAPE;

        $object = self::table();
        $object = new $object;

        // Check if table is allowed to be injected
        $checkValues = $object->checkColumnList($column);
        $object->field_table = $checkValues["field_table"];
        $object->field_value = $checkValues["field_value"];

        if($selector == false || !in_array($selector, self::$allowed_select_values)){
            $selector = "=";
        }

        $object->query = "SELECT * FROM users WHERE $object->field_table $selector $object->field_value ";
        $object->data[$object->field_value] = $value;

        return $object;
    }   // Done and tested

    //  PARAMETERIC METHODS
    public function also($column, $value, $selector = false){
        // AND FUNCTION

        // PDO ESCAPE
        // Check if table is allowed to be injected
        $checkValues = $this->checkColumnList($column);
        $this->field_table = $checkValues["field_table"];
        $this->field_value = $checkValues["field_value"];

        // SELECTOR
        if($selector == false || !in_array($selector, self::$allowed_select_values)){
            $selector = "=";
        }

        $this->query = $this->query." AND ".$this->field_table." ".$selector." ".$this->field_value." ";
        $this->data[$this->field_value] = $value;

        return $this;
    }   // Done and tested

    public function join(Array $condition = array(), $direction = false ){
        // Junta logo na foreign USING
        // Direction inner, outer, left , left outer, natural, right , right outer;
    }

    public function joinDescription ($column, $value, $selector = false){

    }

    public function asc($column=false){

        if($column !== false){
            $checkValues = $this->checkColumnList($column);
            $this->field_table = $checkValues["field_table"];
        }else{
            $column = $this->primary;
        }

        $this->query = $this->query." ORDER BY ".$column." ASC ";
        return $this;
    }   // Done and tested

    public function desc($column = false){

        if($column !== false){
            $checkValues = $this->checkColumnList($column);
            $this->field_table = $checkValues["field_table"];
        }else{
            $column = $this->primary;
        }

        $this->query = $this->query." ORDER BY ".$column." DESC ";
        return $this;
    }   // Done and tested

    public function offset($many = false){
        // Offsetvalue tem de ser no fim do query
        if(is_numeric($many)){
            $this->offsetValue = " OFFSET ".$many." ";
        }
        return $this;
    }   // Done and tested

    //  CALLER METHODS
    public function get($limit = false){
        //  LIMIT
        if($limit !== false && is_numeric($limit)){
            $limit = " LIMIT ".$limit." ";
        }else{
            $limit = "";
        }
        //  OFFSET
        $this->query = $this->query.$limit.$this->offsetValue;

        //  RUN THE QUERY

        $stmt = $this->db->prepare($this->query);

        //  For some reason, while looping this, the $pair is getting a copy of the last one.
        //  Pushing it into an array makes it update
        $i = 0;
        $array = array();
        foreach ($this->data as $key => $pair){
            $array[$i] = $pair;
            $stmt->bindParam($key, $array[$i]);
            $i++;
        }

        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS,self::table());

        return (object) $stmt->fetchAll(\PDO::FETCH_CLASS);

    }   // Done and tested

    public function save(){
        $values =  $this->data;
        $filtered = null;   // Armazena as colunas
        foreach($values as $key => $value){
            // Verifica se há id
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
            $query = "UPDATE ".$this->table." SET $params WHERE id = ".$this->id;

        }else{
            $params = join(", :",$columns);
            $params =":".$params;
            $columns= join(", ", $columns);
            $query = "INSERT INTO ".$this->table." ($columns) VALUES ($params)";
        }
        // Connect and do it

        $stmt = $this->db->prepare($query);

        $i = 0;
        $array = array();
        foreach($filtered as $key => $pair){
            $array[$i] = $pair;
            $stmt->bindParam(":".$key, $array[$i]);
            $i++;
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

    }    // Updates or inserts something // Done and tested

    public function saveAndGetId(){}    // Saves and gets the id // Done and tested

    public function first(){

        $this->get(1);
        return $this;
    }   // Done and tested

    public function last($column = false){

        $this->desc($column);
        $this->get(1);
        return $this;

    } // Done and tested

    //  PARSING METHODS
    public function toArray(){

    }

    //  SAFETY METHODS
    /*
     * If empty @columnList you can put anything on the query.
     * Something I don't advice.
     */
    public function checkColumnList($column){
        if(sizeof($this->columnList) > 0) {
            if (!in_array($column, $this->columnList)) {

                //echo "Passando no not in array";
                throw new \Exception ("Undeclared column field on get_called_class() Model");
                //die;
            }
        }

        return array(
            "field_table" => $column,
            "field_value" => ":".$column
        );
    }   // Done and tested

    /*
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
            // Verifica se � id
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
    */
}