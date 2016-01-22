<?php namespace Mariana\Framework\DatabaseManager;
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 22/01/2016
 * Time: 09:58
 */

use Mariana\Framework\Database;

class DatabaseManager{

    public $db;
    private $path_databases = ROOT.DS."app".DS."files".DS."database".DS."databases".DS;
    private $path_seeds = ROOT.DS."app".DS."files".DS."database".DS."seeds".DS;
    private $table;
    private $fields;
    private $seeds;

    public function __construct($table){
        $this->table = $table;
        $this->db = Database::getConnection();
        $this->import();
    }

    public function import(){
        if(!$this->fields){
            # Initialize params
            $fields = false;
            $seeds = false;

            include_once ($this->path_seeds.$this->table.".php");
            $this->fields = $fields;
            $this->seeds = $seeds;
        }
    }

    # Create Table Fields String:
    function create()
    {
        # Field String
        $f = "";

        foreach ($this->fields as $key => $value) {
            $f .= " " . $key . " " . $value . ",";
        }

        # Clean Field String
        $f = trim($f, ",");

        # Query String
        $sql = "CREATE TABLE IF NOT EXISTS $this->table (" . $f . ")";

        # Generate
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $sql;
    }

    # Session
    function seed()
    {

        # columns generator
        $columns = "";
        foreach ($this->fields as $key => $pair){
            $columns .= " `$key` ,";
        }
        $columns = trim($columns,",");
        $columns = "(".$columns.")";

        #values generator
        $fields_size = sizeof($this->fields) ;

        foreach ($this->seeds as $key => $value) {

            $values = "";

            for($i = 0; $i < $fields_size; $i++){
                $values .= $value[$i] ." ,";
            }

            $values = "(".trim($values,",").")";

            $sql = "INSERT INTO $this->table $columns VALUES $values";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
    }


    function update()
    {

    }


    function loadBackup($table)
    {

    }

    function drop()
    {
        $sql = "DROP TABLE $this->table";
        $this->db->exec($sql);
    }

}