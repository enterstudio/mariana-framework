<?php namespace Mariana\Framework\ORM;

use Mariana\Framework\Database;

/**
 * Class MarianaORM
 * @package Mariana\Framework\ORM
 *
 * TODO : Remove the query from the object
 * TODO : Count
 * TODO : Joins
 * TODO : Transactions
 * TODO : Delete
 *
 */


class MarianaORM extends Database{

    private $query;           //  QUERY string
    public $data = array();  //  QUERY input data -> array["column"] = "value"
    protected $primary = "id";  //  PRIMARY KEY: needed for several stuff, id is primary by default
    protected $db;              //  DATABASE CONNECTION
    protected $table;           //  TABLE NAME -> DEFAULT = get_called_class();
    public $obj;                //  NEEDED
    protected $offsetValue = "";//  OFFSET -> offsets need to be injected on the end of the query, so just leave here a key to it.

    protected $columnList = array(); // All Columns of the table that can be used in a query;
    private static $allowed_select_values = ["=",">",">=","<=","LIKE","NOT LIKE", "<>", "IN" , "BEETWEEN" , "IS NOT NULL", "NOT BEETWEEN", "!=", "!", "SOUNDS LIKE"];
    private $field_table;
    private $field_value;

    private $dbTransaction = false;
    private $setCount = false;

    //  CONSTRUCTED AT MODEL

    //  STATIC METHODS
    public static function table(){
        return get_called_class();
    }

    public static function find($primary_key_value){
        // Gets Database item by primary
        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;

        // Check if table is allowed to be injected
        $object->query = "SELECT * FROM $table WHERE $object->primary = ? ";
        $object->data[$object->primary] = $primary_key_value;

        $stmt = self::getConnection()->prepare($object->query);
        $stmt->bindParam(1,$primary_key_value);
        $stmt->execute();

        $object->obj = (object) $stmt->fetch(\PDO::FETCH_OBJ);

        return $object;
    }   //  Get database item by primary key Done and tested

    public function delete(){}  //  UNSTARTED

    public static function all(){

        $db = self::getConnection();
        $query = " SELECT * FROM ".self::getTable();
        $stmt = $db->prepare($query);
        $stmt->execute();

        $class = get_called_class();
        $obj = [];

        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){;
            // Criar novo objecto
            $object = new $class($row);
            $object->unsetFromObject();
            foreach ($row as $key => $value){
                $object->{$key} = $value;
            }
            $obj[] = $object;
        }
        return $obj;
    }


    //  PARAMETERIC METHODS

    public static function where($column,$valueOrSelector, $valueIfSelector = false){
        //PDO ESCAPE;

        $object = self::table();
        $object = new $object;

        // SELECTOR CHECK
        if($valueIfSelector !== false){
            $selector = $valueOrSelector;
            $value = $valueIfSelector;
        }else{
            $selector = "=";
            $value = $valueOrSelector;
        }

        // Check if table is allowed to be injected
        $checkValues = $object->checkColumnList($column);
        $object->field_table = $checkValues["field_table"];
        $object->field_value = $checkValues["field_value"];

        if($selector == "=" || !in_array($selector, self::$allowed_select_values)){
            $selector = "=";
        }

        $object->query = "SELECT * FROM users WHERE $object->field_table $selector $object->field_value ";
        $object->data[$object->field_value] = $value;

        return $object;
    }   // Done and tested

    public function also($column, $valueOrSelector, $valueIfSelector = false){
        // AND FUNCTION

        // SELECTOR CHECK
        if($valueIfSelector !== false){
            $selector = $valueOrSelector;
            $value = $valueIfSelector;
        }else{
            $selector = "=";
            $value = $valueOrSelector;
        }

        // SELECTOR
        if($selector == "=" || !in_array($selector, self::$allowed_select_values)){
            $selector = "=";
        }

        // PDO ESCAPE
        // Check if table is allowed to be injected
        $checkValues = $this->checkColumnList($column);
        $this->field_table = $checkValues["field_table"];
        $this->field_value = $checkValues["field_value"];

        $this->query = $this->query." AND ".$this->field_table." ".$selector." ".$this->field_value." ";
        $this->data[$this->field_value] = $value;

        return $this;
    }   // Done and tested

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

    //  RELATIONSHIPS
    public function hasOne($class, $reference){

        // One user has one item

        $obj = new $class();

        $query = "SELECT * FROM $obj->table WHERE $reference = ? ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1 , $this->obj->{$this->primary});
        $stmt->execute();

        $newProperty = $stmt->fetch(\PDO::FETCH_OBJ);

        // dar o nome contactos á propriedade contactos do objecto.
        $e = new \Exception();
        $trace = $e->getTrace();
        $last_call = strtolower($trace[1]["function"]);
        $newPropertyName = $last_call;

        // Adicionar propriedade ao objecto
        $this->obj->{$newPropertyName} = $newProperty;

        return $newProperty;
    }   // Done and tested

    public function hasMany($class, $reference){

        // One user has one item
        $obj = new $class();

        //, NULL as $reference  -> sensitive fields
        $query = "SELECT * FROM $obj->table WHERE $reference = ? ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1 , $this->obj->{$this->primary});
        $stmt->execute();

        $newProperty = (object) $stmt->fetchAll(\PDO::FETCH_OBJ);

        // dar o nome contactos á propriedade contactos do objecto.
        $e = new \Exception();
        $trace = $e->getTrace();
        $last_call = strtolower($trace[1]["function"]);
        $newPropertyName = $last_call;

        // Adicionar propriedade ao objecto

        $this->obj->{$newPropertyName} = $newProperty;

        return $newProperty;
        // Done and tested
    }   // tested and done

    public function manyHaveOne($class, $reference){
        $i = 1;
        foreach($this->obj as $single){

            $obj = new $class();

            $query = "SELECT * FROM $obj->table WHERE $reference = ? ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1 , $this->obj->{$this->primary});
            $stmt->execute();

            $newProperty = $stmt->fetch(\PDO::FETCH_OBJ);

            // dar o nome contactos á propriedade contactos do objecto.
            $e = new \Exception();
            $trace = $e->getTrace();
            $last_call = strtolower($trace[1]["function"]);
            $newPropertyName = $last_call;

            // Adicionar propriedade ao objecto
            $single->{$newPropertyName} = $newProperty;
        }

        return $this;
    }   //   Not working

    public function manyHaveMany($class, $reference){}

    public function join(Array $condition = array(), $direction = false ){
        // Junta logo na foreign USING
        // Direction inner, outer, left , left outer, natural, right , right outer;
    }

    public function joinDescription ($column, $value, $selector = false){

    }

    //  CALLER METHODS
    public function get($limit = false){

        try {

            //  LIMIT
            if ($limit !== false && is_numeric($limit)) {
                $limit = " LIMIT " . $limit . " ";
            } else {
                $limit = "";
            }
            //  OFFSET
            $this->query = $this->query . $limit . $this->offsetValue;


            //  RUN THE QUERY
            $stmt = $this->db->prepare($this->query);

            //  IF QUERY HAS DYNAMIC PARAMS
            //  For some reason, while looping this, the $pair is getting a copy of the last one.
            //  Pushing it into an array makes it update
            //if (sizeof($this->params) > 0) {
                $i = 0;
                $array = array();
                foreach ($this->data as $key => $pair) {
                    $array[$i] = $pair;
                    $stmt->bindParam($key, $array[$i]);
                    $i++;
                }
            //}
            $stmt->execute();

            /*
            // SET TRANSACTION IF NEEDED
            if($this->dbTransaction){
                $this->db->beginTransaction();
            }

            if($this->dbTransaction){
                $this->db->commit();
            }

            if($this->setTransaction){
                $this->db->rollback();
            }

            */
            $this->obj = (object)$stmt->fetchAll(\PDO::FETCH_OBJ);
            return $this;

        } catch (\Exception $e){


            echo $e;
            return false;
        }

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

    public function saveAndGetId(){
        $this->save();
        return $this->id;
    }    // Saves and gets the id // Done and tested

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
        return json_decode(json_encode($this->obj),TRUE);
    }

    public function toJSON(){
        return json_encode($this->obj);
    }

    //  OTHER METHODS
    public function count(){
        $this->setCount = true;
    }

    public function setTransaction(){
        $this->dbTransaction = true;
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
    }   // Done and tested - Prevents sql injection

    public function transaction(){
        $this->setTransaction = true;
    }
}