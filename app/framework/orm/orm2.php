<?php

namespace Mariana\Framework;


# REMOVE ERROR DISPLAY
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

# DEFINE SOME VARIABLES
define('ROOT', '../../../');
define('DS', DIRECTORY_SEPARATOR);

# GET AUTOLOAD
require_once(ROOT.DS."vendor".DS."autoload.php");
require_once(ROOT.DS."app".DS."functions.php");

# DEFINE USAGES

use Mariana\Framework\Config;
use \PDO;
use Mariana\Framework\Database;
use Mariana\Framework\Security\Environment;

# BOOT AND VALIDATE THE COMMAND LINE INTERFACE
Environment::setup();
require_once('../../../app/config.php');


class Orm extends Database
{

    public $allowed_select_values = ['=', '>', '>=', '<=', 'LIKE', 'NOT LIKE', '<>', 'IN', 'BEETWEEN', 'IS NOT NULL', 'NOT BEETWEEN', '!=', '!', 'SOUNDS LIKE'];
    public $allowed_join_values = ['inner', 'outer', 'left', 'right'];

    private $select;
    private $count;
    private $orderBy;
    private $groupBy;
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

    protected $data;
    protected $protected;               //  PROTECTED FIELDS (fields that should not be updated ex: id field or unique fields)
    protected $primary;                 //  PRIMARY KEY: needed for several stuff, id is primary by default
    protected $db;                      //  DATABASE CONNECTION
    protected $table;                   //  TABLE NAME -> DEFAULT = get_called_class();
    protected $columnList = array();    //  All Columns of the table that can be used in a query;
    protected $hasOne = array();
    protected $hasMany= array();
    protected $observe= array();
    /*
    public function __construct(){
        $this->db = Database::getConnection();
        $this->setDefault();
    }
    */

    function __construct($data = false)
    {
        $this->db = self::getConnection();
        $this->table = self::table();
        $this->setDefault();
        if ($data !== false) {
            $this->{$this->primary} = $data;
        };
/*
        $this->hasOne = array(
            array('comment','comments','user_id'),
            array('comments','comments','user_id'),
        );
        foreach ($this->hasOne as $h ) {
            $name = $h[0];
            $h1 = $h[1];
            $func = function(&$h1){
                print_r($h1);
            };
            $this->{$name} = $func($h);
        }
*/
    }

    function __destruct()
    {
        $this->setDefault();
    }

    function __call($method, $args)
    {
        //return call_user_func_array($method, $args);
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }

    function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }

    function __set($name, $value)
    {
        echo '<pre>',  var_dump($value) , '</pre>';
        if(is_object($value)){
            return $this->{$name} = $value;
        }
        return $this->data[$name] = $value;
    }

    public function getColumns()
    {
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
        $this->table = 'users';
        $this->join = false;
        $this->protected = array();
        $this->lastId = false;
        $this->delete = false;
        $this->hasOne = array();
        $this->hasMany= array();
        $this->observe= array();
    }

    /** METHOD BUILDING PARAMETERS */
    private function attachMethod($name, $var ,$function){

    }

    private function attachObserver(){

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
    public function join($otherTable, $otherTableKey, $selector = false, $currentTableKey = false, $innerOuterRightLeftFull = false)
    {

        # Escape the other table
        $otherTableKey = $this->escapeColumn($otherTableKey, $otherTable);
        $otherTable = "`$otherTable`";

        # Escape the current table
        ($currentTableKey) ?
            $currentTableKey = $this->escapeColumn($currentTableKey) :
            $currentTableKey = $this->escapeColumn($this->primary);

        # Define the type of join
        ($innerOuterRightLeftFull) ?
            (in_array(strtolower($innerOuterRightLeftFull), $this->allowed_join_values)) ?
                $innerOuterRightLeftFull = strtoupper($innerOuterRightLeftFull) . ' JOIN ' :
                $innerOuterRightLeftFull = ' JOIN '
            :
            $innerOuterRightLeftFull = " JOIN ";

        # Define the type of selector
        if ($selector !== false) {
            if (!in_array(trim($selector), $this->allowed_select_values)) {
                $selector = '=';
            }
        } else {
            $selector = '=';
        }


        $this->join = array(
            $innerOuterRightLeftFull,
            $otherTable,
            $currentTableKey,
            $selector,
            $otherTableKey);

        return $this;
    }
    public function saveGetId()
    {
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
                if(!is_object($pair)) {
                    $stmt->bindParam(":$key", $pair);
                }
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
    public static function find($primary_key_value){
        // Gets Database item by primary
        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;

        return $object::where($object->primary, $primary_key_value)
            ->get();

    }   //  Get database item by primary key Done and tested
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
    public static function first(){
        // Gets Database item by primary
        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;

        return $object->get(1);
    }   // Done and tested
    public static function last($column = false){

        // Gets Database item by primary
        $table = self::table();
        $object = new $table();
        // Override table name if set on model
        $table = $object->table;

        $object->desc($column);
        return $object->get(1);

    } // Done and tested
    public static function where($column,$valueOrSelector, $valueIfSelector = false){
        $object = self::table();
        $object = new $object;
        $object->pushWhere($column,$valueOrSelector, $valueIfSelector);
        return $object;
    }   // Done and tested

    /** BBUILDER METHODS **/
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
                $query .= " DESC " :
                $query .= "";
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

        return $query;
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

        # Return

        return $stmt->fetch(PDO::FETCH_OBJ);

    }   // Done and tested

}

$o = Orm::where('id','>','0')
    ->also('name','pihh')
    ->also('hash','laden')
    ->join('otherTable','otherField',false,false,'left')
    ->offset(5)
    ->get();
echo $o , '<hr>';

$o = Orm::where('id','0')
    ->order('name')
    ->get();
echo $o , '<hr>';

$o = Orm::where('id','0')
    ->desc('name')
    ->get();
echo $o , '<hr>';

$o = Orm::where('id','0')
    ->desc()
    ->get();
echo $o , '<hr>';

$o = Orm::last();
echo $o , '<hr>';

$o = Orm::first(1);
echo $o , '<hr>';

$o = Orm::find(1);
echo $o , '<hr>';

$o = new Orm();
$o->name = 'pihh';
$o->password = '30';
$o->hash = '0';
$o->save();
echo '<hr>';

$o = new Orm(2);
$o->name = 'pihh';
$o->password = 'password';
$o->hash = 'hash';
$o->save();
echo '<hr>';

$o = new Orm();
$o->comments();
