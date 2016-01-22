<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 22/01/2016
 * Time: 12:30
 *
 * $expected = true;
 * $this->assertEquals($expected, $dbm);
 */

use \Mariana\Framework\DatabaseManager;

class DatabaseManagerTest extends PHPUnit_Framework_TestCase{

    private $dbm ;

    public function setup(){
        $this->dbm = $dbm = new DatabaseManager("users");
    }

    public function testConnection(){

    }

    public function testCreate(){
        //$dbm->create();
        //$dbm->seed();
    }

    public function testDrop(){

    }

    public function testSeed(){

    }

}