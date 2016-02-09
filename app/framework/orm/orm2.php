<?php

namespace Mariana\Framework;

include_once('../../../vendor/autoload.php');

use \PDO;
use Mariana\Framework\Database;


class Orm {



    private $select;
    private $count;
    private $orderBy;
    private $groupBy;
    private $limit;
    private $offset;
    private $where;
    private $transaction;
    private static $allowed_select_values = ["=",">",">=","<=","LIKE","NOT LIKE", "<>", "IN" , "BEETWEEN" , "IS NOT NULL", "NOT BEETWEEN", "!=", "!", "SOUNDS LIKE"];

    protected $data;
    protected $primary;                 //  PRIMARY KEY: needed for several stuff, id is primary by default
    protected $db;                      //  DATABASE CONNECTION
    protected $table;                   //  TABLE NAME -> DEFAULT = get_called_class();
    protected $columnList = array();    // All Columns of the table that can be used in a query;

    public function __construct(){
        $this->db = Database::getConnection();
        $this->setDefault();
    }

    public function setDefault(){
        $this->select = '*';
        $this->count = '';
        $this->orderBy = '';
        $this->groupBy = '';
        $this->limit = '5';
        $this->offset = '';
        $this->data = array();
        $this->primary = 'id';
        $this->where = false;
        $this->transaction = false;
    }

    /** SAFETY FUNCTIONS  */
    private function escape($arg){
        return $this->db->quote($arg);
    }

    private function int($arg){
        return intval($arg);
    }

    /** MYSQL METHODS */
    public function select($args = '*'){
        $this->select = $this->escape($args);
    }
    public function count($arg = '*'){
        $this->select = ' count(' .$this->escape($arg). ') AS count ';
    }
    public function limit($number){
        $this->limit = intval($number);
    }
    public function offset($number){
        $this->offset = intval($number);
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

        // Check if table is allowed to be injected
        $object->query = "SELECT $object->select FROM $table WHERE $object->primary = ? ";
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
    public static function where($column,$valueOrSelector, $valueIfSelector = false){
        $object = self::table();
        $object = new $object;

        $object->where = array();
        $column = $object->db->quote($column);

        array_push($object->where,array($column,$valueIfSelector,$valueIfSelector));

        return $object;
        /*
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
        */
    }   // Done and tested


}

$o = new Orm();