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
    protected $primary = 'id';          //  PRIMARY KEY: needed for several stuff, id is primary by default
    protected $db;                      //  DATABASE CONNECTION
    protected $table;                   //  TABLE NAME -> DEFAULT = get_called_class();
    protected $columnList = array();    //  All Columns of the table that can be used in a query;
    protected $has = array();
    protected $observes = array();


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
        $this->has = array();
        $this->observes = array();
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
            $where = '';
            $i = 0;
            foreach($this->where as $w){
                ($i === 0) ?
                    $where .= " WHERE $w[0] $w[1] $w[2] " :
                    $where .= " AND $w[0] $w[1] $w[2]";
                $i++;
            }
            $query = $query.$where;
        }

        # MANY Builder
        if(sizeof($this->has)> 0){
            $query_start = "SELECT * FROM (SELECT * FROM `$this->table`) AS `$this->table` , ";
            $query_end = '';

            foreach($this->has as $h){

                $other_table = $h['other_table'];
                $other_table_key = $h['other_table_key'];
                $key_that_matches_other_table_key = $h['this_table_key'];
                $name_of_the_column_we_want_to_fetch = $h['column_fetched'];
                $how_many_results = $h['how_many'] ;

                ($how_many_results == false || $how_many_results == 0) ?
                    $query_start .= "( SELECT `$other_table_key`, GROUP_CONCAT(`$name_of_the_column_we_want_to_fetch` SEPARATOR ',' ) AS `$other_table-$name_of_the_column_we_want_to_fetch` FROM `$other_table` ) AS `$other_table` ," :
                    $query_start .= "( SELECT `$other_table_key`, `$name_of_the_column_we_want_to_fetch` AS `$other_table-$name_of_the_column_we_want_to_fetch` FROM `$other_table` LIMIT ".$how_many_results.") AS `$other_table` ,";

                ($key_that_matches_other_table_key == false)?
                    $key_that_matches_other_table_key = $this->primary:
                    $key_that_matches_other_table_key = $key_that_matches_other_table_key;

                $query_end .= " AND `$this->table`.`$key_that_matches_other_table_key` =  `$other_table`.`$other_table_key` ";
                /* WORKING QUERY
                    SELECT * FROM
                    ( SELECT * FROM `users` ) as `users` ,
                    ( SELECT `comments`.`user_id` , GROUP_CONCAT(`comments`.`content` SEPARATOR ',') FROM `comments` ) AS `comments`
                    WHERE `users`.`id` = 1
                    AND `users`.`id` = `comments`.`user_id`
                */

            }

            $query_start = trim($query_start,',');
            $query = $query_start.$where.$query_end;
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

    protected function has($other_table, $other_table_key, $name_of_the_column_we_want_to_fetch, $key_that_matches_other_table_key = false, $how_many_results = false){
        array_push($this->has, array(
            'other_table' => $other_table,
            'other_table_key' => $other_table_key,
            'this_table_key'  => $key_that_matches_other_table_key,
            'column_fetched' => $name_of_the_column_we_want_to_fetch,
            'how_many' => $how_many_results)
        );
        return $this;
    }

}