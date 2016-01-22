<?php
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 1/21/2016
 * Time: 11:59 PM
 */
$create = "Create Table users (
                  id INTEGER PRIMARY KEY,
                  username VARCHAR (255),
                  email VARCHAR (255) UNIQUE ,
                  password VARCHAR (255),
                  salt VARCHAR (255),
                  session_hash VARCHAR (255),
                  session_json BLOB
                  )";

function seed($many){
    $seed = array();
    for($i = 0; $i < $many; $i++ ){
        $s = "INSERT INTO users (username, email) VALUES ("","adminpass")";
    }

}