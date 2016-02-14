<?php namespace Mariana\Framework\ORM;

use Mariana\Framework\Cache\Cache;
use Mariana\Framework\Database;
use Mariana\Framework\Security;
/**
 * Class MarianaORM
 * @package Mariana\Framework\ORM
 *
 * TODO : hasOne, hasMany, observer, cache -> should be pretty staighforward, Testing
 *
 *
 */


class MarianaORM extends Database{


    public $allowed_select_values = ['=', '>', '>=', '<=', 'LIKE', 'NOT LIKE', '<>', 'IN', 'BEETWEEN', 'IS NOT NULL', 'NOT BEETWEEN', '!=', '!', 'SOUNDS LIKE'];
    public $allowed_join_values = ['inner', 'outer', 'left', 'right'];

    private $select = '*';
    private $count;
    private $orderBy;
    private $groupBy ;
    private $limit;
    private $offset;
    private $where;
    private $asc;
    private $desc;
    private $transaction;
    private $join;
    private $lastId;
    private $delete;
    private $methods = array();
    private $results_as_array;
    private $results_as_json;
    private $results_as_class;

    protected $data;
    protected $protected = array();               //  PROTECTED FIELDS (fields that should not be updated ex: id field or unique fields)
    protected $primary;                 //  PRIMARY KEY: needed for several stuff, id is primary by default
    protected $db;                      //  DATABASE CONNECTION
    protected $table;                   //  TABLE NAME -> DEFAULT = get_called_class();
    protected $columnList = array();    //  All Columns of the table that can be used in a query;
    protected $hasOne = array();
    protected $hasMany= array();
    protected $observe= array();


    function __construct($data = false){
        $this->db = self::getConnection();
        $this->table = self::table();
        $this->setDefault();
        if ($data !== false) {
            $this->{$this->primary} = $data;
        };
    }
    function __destruct(){
        $this->setDefault();
    }

    public function getColumns(){
        return $this->data;
    }
    public function setDefault()
    {
        $this->select = '*';
        $this->count = false;
        $this->orderBy = false;
        $this->groupBy = false;
        $this->limit = false;
        $this->offset = false;
        $this->data = array();
        $this->primary = 'id';
        $this->where = false;
        $this->transaction = false;
        $this->asc = false;
        $this->desc = false;
        $this->table = '';
        $this->join = false;
        $this->protected = array();
        $this->lastId = false;
        $this->delete = false;
        $this->hasOne = array();
        $this->hasMany= array();
        $this->observe= array();
    }

    /** SAFETY FUNCTIONS  */
    private function escape($arg)
    {
        return $this->db->quote($arg);
    }
    private function escapeColumn($arg, $table = false)
    {
        ($table) ?
            $table = '`' . str_replace('`', '', $table) . '`' :
            $table = "`$this->table`";

        $arg = '`' . str_replace('`', '', $arg) . '`';

        return $table . '.' . $arg;
    }

    /** MYSQL METHODS */
    public function delete($primary_key_value){
        $this->delete = true;
        $this->find($primary_key_value);
        return $this;
    }
    public function select($args = '*')
    {
        if (strpos($args, ',')) {
            #estamos perante uma array
            $args = explode(',', $args);
            foreach ($args as $a) {
                $this->select .= $this->escapeColumn($a) . ',';
            }
            $this->select = trim($this->select, ',');
        } else {
            #estamos perante single arg
            $this->select = $this->escapeColumn($args);
        }
        return $this;
    }
    public function count($arg = '*')
    {
        $this->select = ' count(' . $this->escape($arg) . ') AS `count` ';
        return $this;
    }
    public function limit($number)
    {
        $this->limit = intval($number);
        return $this;
    }
    public function offset($number)
    {
        $this->offset = intval($number);
        return $this;
    }
    public function desc($column = false)
    {
        $this->order($column);
        $this->desc = $this->orderBy;
        return $this;
    }   // Done and tested
    public function transaction()
    {
        $this->transaction = true;
        return $this;
    }   // Done and tested
    public function order($column = false)
    {
        if ($column == false) {
            $column = $this->primary;
        }
        $this->orderBy = $this->escapeColumn($column);
        return $this;
    }   // Done and tested
    public function join($otherTable, $otherTableKey, $selector = false, $currentTableKey = false, $inner_outer_left_right = false){

        # Escape the other table
        $otherTableKey = $this->escapeColumn($otherTableKey, $otherTable);
        $otherTable = "`$otherTable`";

        # Escape the current table
        ($currentTableKey) ?
            $currentTableKey = $this->escapeColumn($currentTableKey) :
            $currentTableKey = $this->escapeColumn($this->primary);

        # Define the type of join
        ($inner_outer_left_right) ?
            (in_array(strtolower($inner_outer_left_right), $this->allowed_join_values)) ?
                $inner_outer_left_right = strtoupper($inner_outer_left_right) . ' JOIN ' :
                $inner_outer_left_right = ' JOIN '
            :
            $inner_outer_left_right = " JOIN ";

        # Define the type of selector
        if ($selector !== false) {
            if (!in_array(trim($selector), $this->allowed_select_values)) {
                $selector = '=';
            }
        } else {
            $selector = '=';
        }


        $this->join = array(
            $inner_outer_left_right,
            $otherTable,
            $currentTableKey,
            $selector,
            $otherTableKey);

        return $this;
    }
    public function saveGetId(){
        $this->lastId = true;
        $this->save();
    }
    private function pushWhere($column, $valueOrSelector, $valueIfSelector = false)
    {
        # SELECTOR CHECK
        if ($valueIfSelector !== false) {
            (in_array(trim($valueOrSelector), $this->allowed_select_values)) ?
                $selector = $valueOrSelector :
                $selector = "=";
            $value = $valueIfSelector;
        } else {
            $selector = "=";
            $value = $valueOrSelector;
        }
        # START THE ARRAY
        if (!is_array($this->where)) {
            $this->where = array();
        }

        $doubleDotColumn = ":" . $column;
        $column = $this->escapeColumn($column);
        array_push($this->where, array($column, $selector, $doubleDotColumn, $value));
    }
    public function save()
    {
        /**
         * @desc as in this function we are using __get and __set, and for th
         * observers and hasOne, hasMany we set params as functions,
         * we exclude objects from the query so they don't
         * interact with the results
         */

        # Params
        $cols = '';
        $vals = '';
        $sets = '';

        try {
            # Start transaction
            if ($this->transaction !== false) {
                $this->db->beginTransaction();
            }
            # Application Logic
            if (!isset($this->data[$this->primary])) {
                # INSERT
                foreach ($this->data as $key => $value) {
                    if(!is_object($value)){
                        $cols .= "`$key`,";
                        $vals .= ":$key ,";
                    }
                }

                # Threatment
                $cols = trim($cols, ',');
                $vals = trim($vals, ',');

                # Query
                $query = "INSERT INTO $this->table ( $cols ) VALUES ( $vals ) ;";

            } else {
                # UPDATE
                foreach ($this->data as $key => $value) {
                    if(!is_object($value)) {
                        if (!in_array($key, $this->protected)) {
                            $sets .= " `$key` = :$key ,";
                        } else {
                            # Caso seja um field protegido, tira antes de fazer o query
                            unset($this->data[$key]);
                        }
                    }
                }
                # Threatment
                $sets = trim($sets, ',');

                # Query
                $query = "UPDATE $this->table SET $sets WHERE $this->primary = " . $this->data[$this->primary] . ";";
            }

            $stmt = $this->db->prepare($query);


            foreach ($this->data as $key => $pair) {
                echo ":$key ,$pair </br>";
                $stmt->bindparam(":$key",$pair);
                /*
                if(!is_object($pair)) {
                    $stmt->bindParam(":$key", $pair);
                }
                */
            }
            $stmt->execute();

            #Start commitment
            if ($this->transaction !== false) {
                $this->db->commit();
            }

            # Return the result
            # Get the id
            (!isset($this->data[$this->primary])) ?
                $lastid = $this->db->lastInsertId() :
                $lastid = $this->data[$this->primary];

            if ($this->lastId !== false) {
                return $lastid;
            } else {
                return $this->find($lastid);
            }


        } catch (Exception $e) {
            # Start Rollback
            if ($this->transaction !== false) {
                $this->db->rollback();
            }
            # Return the result;
            return false;
        }
    }

    /**  STATIC METHODS  **/
    public static function table(){
        return get_called_class();
    }
    public static function find($primary_key_value, $json_array_class_OR_object = false){
        // Gets Database item by primary
        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;

        if($json_array_class_OR_object){
            $object->json_array_class_OR_object($json_array_class_OR_object);
        }

        return $object::where($object->primary, $primary_key_value)
            ->get();

    }   //  Get database item by primary key Done and tested
    public static function all($json_array_class_OR_object = false){

        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;
        if($json_array_class_OR_object){
            $object->json_array_class_OR_object($json_array_class_OR_object);
        }
        return $object->get();

    }
    public static function first($json_array_class_OR_object = false){
        // Gets Database item by primary
        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;

        # Set how to display results
        if($json_array_class_OR_object){
            $object->json_array_class_OR_object($json_array_class_OR_object);
        }

        return $object->get(1);
    }   // Done and tested
    public static function last($column = false, $json_array_class_OR_object = false){

        // Gets Database item by primary
        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;

        if($json_array_class_OR_object){
            $object->json_array_class_OR_object($json_array_class_OR_object);
        }

        $object->desc($column);
        return $object->get(1);

    } // Done and tested
    public static function where($column,$valueOrSelector, $valueIfSelector = false){
        $object = self::table();
        $object = new $object;
        $object->pushWhere($column,$valueOrSelector, $valueIfSelector);
        return $object;
    }   // Done and tested

    /** BUILDER METHODS **/
    public function also($column,$valueOrSelector, $valueIfSelector = false){
        $this->pushWhere($column,$valueOrSelector, $valueIfSelector);
        return $this;
    }
    public function get($limit = false){

        # Function Parameters
        if($limit){
            $this->limit = $limit;
        }

        # Query Builder
        ($this->count) ?
            $query = "SELECT $this->count FROM `$this->table` ":
            $query = "SELECT $this->select FROM `$this->table` ";

        # Delete Builder
        if($this->delete){
            $query = "DELETE FROM `$this->table` ";
        }

        # JOIN Builder
        if($this->join){
            $query .= $this->join[0].' '.$this->join[1].' ON '.$this->join[2].' '.$this->join[3].' '.$this->join[4];
        }

        # WHERE Builder
        if($this->where){
            $i = 0;
            foreach($this->where as $w){
                ($i === 0) ?
                    $query .= " WHERE $w[0] $w[1] $w[2] " :
                    $query .= " AND $w[0] $w[1] $w[2]";
                $i++;
            }
        }

        # Set Group By
        if($this->orderBy){
            $query .= " ORDER BY $this->orderBy ";
        }

        # Set Direction
        if($this->orderBy) {
            ($this->desc) ?
                $query .= ' DESC ' :
                $query .= '';
        }

        # Set Limit
        if($this->limit){
            $query .= " LIMIT $this->limit ";
        }

        # Set Offset
        if($this->offset){
            $query .= " OFFSET $this->offset ";
        }

        # Set Group By
        if($this->groupBy){
            $query = " GROUP BY $this->groupBy ";
        }

        # Run the query
        $stmt = $this->db->prepare($query);
        if(is_array($this->where)){
            foreach($this->where as $w){
                $stmt->bindParam($w[2],$w[3]);
            }
        }
        $stmt->execute();

        # Reset defaults
        $this->setDefault();

        # Cache:

        # Set how to display data
        if ($this->results_as_array) {
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } elseif ($this->results_as_class) {
            $results = $stmt->fetchAll(\PDO::FETCH_CLASS);
        } else {
            $results = $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
        if ($this->results_as_json) {
            $results = json_encode($results);
        }
        return $results;

    }   // Done and tested
    public function as_array(){
        $this->results_as_array = true;
        return $this;
    }
    public function as_class(){
        $this->results_as_class = true;
        return $this;
    }
    public function as_json(){
        $this->results_as_json = true;
        return $this;
    }
    private function json_array_class_OR_object($type){
        $type = trim($type);

        switch ($type) {
            case 'json':
            $this->results_as_json = true;
            return $this;
        break;
            case 'array':
            $this->results_as_array = true;
            return $this;
        break;
            case 'class':
            $this->results_as_class = true;
            return $this;
        break;
            default:
            return $this;
        }

    }

    protected function has($table, $key, $extras = false){
        array_push($this->has, array($table, $key, $extras));
    }

    /** VARS  **

    public $data = array();  //  QUERY input data -> array["column"] = "value"
    public $obj;                //  NEEDED

    protected $primary = "id";  //  PRIMARY KEY: needed for several stuff, id is primary by default
    protected $db;              //  DATABASE CONNECTION
    protected $table;           //  TABLE NAME -> DEFAULT = get_called_class();
    protected $offsetValue = "";//  OFFSET -> offsets need to be injected on the end of the query, so just leave here a key to it.
    protected $columnList = array(); // All Columns of the table that can be used in a query;

    private $field_table;
    private $field_value;
    private $dbTransaction = false;
    private $setCount = false;
    private $query;           //  QUERY string
    private static $allowed_select_values = ["=",">",">=","<=","LIKE","NOT LIKE", "<>", "IN" , "BEETWEEN" , "IS NOT NULL", "NOT BEETWEEN", "!=", "!", "SOUNDS LIKE"];

    /**  STATIC METHODS  **
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
    public static function delete($primary_key_value){
        // Gets Database item by primary
        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;

        // Check if table is allowed to be injected
        $object->query = "DELETE FROM $table WHERE $object->primary = ? ";
        $object->data[$object->primary] = $primary_key_value;

        $stmt = self::getConnection()->prepare($object->query);
        $stmt->bindParam(1,$primary_key_value);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
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

    /**  PARAMETERIC METHODS  **
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

    /**  RELATIONSHIPS  **
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
    public function join(Array $condition = array(), $direction = false ){
        // Junta logo na foreign USING
        // Direction inner, outer, left , left outer, natural, right , right outer;
    }
    public function joinDescription ($column, $value, $selector = false){

    }

    /**  CALLER METHODS  **
    public function get($limit = false){

        try {

            //  COUNT
            if($this->setCount){
                $this->query = str_replace('*', 'count(*)', $this->query);
            }

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

    /**  PARSING METHODS  **
    public function toArray(){
        return json_decode(json_encode($this->obj),TRUE);
    }
    public function toJSON(){
        return json_encode($this->obj);
    }

    /**  OTHER METHODS  **
    public function count(){
        $this->setCount = true;
    }
    public function setTransaction(){
        $this->dbTransaction = true;
    }

    /**  SAFETY METHODS  **
    public function checkColumnList($column){
        /**
         * If empty @columnList you can put anything on the query.
         * Something I don't advice.
         *
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
     **/
}