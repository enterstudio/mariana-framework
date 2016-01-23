<?php
/**
 * Created with love using Mariana Framework
 * pihh.rocks@gmail.com
 */

# Table Name
$table = "users";

# Table Fields
$fields = array(
    "id"            =>  "INTEGER PRIMARY KEY",
    "name"          =>  "VARCHAR (255)",
    "email"         =>  "VARCHAR (255) UNIQUE",
    "password"      =>  "VARCHAR (255)",
    "salt"          =>  "VARCHAR (255)",
    "session_hash"  =>  "VARCHAR (255) NULL",
    "session_json"  =>  "VARCHAR (255) NULL",
    "signup_date"   =>  "TIMESTAMP"
);

# Specific for this example.
$column_password = "mariana";
$column_password = password_hash("mariana", PASSWORD_BCRYPT);

# Table Seeds
$seeds = array(
    array("1", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("2", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("3", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("4", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("5", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("6", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("7", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("8", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("9", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
    array("10", "'".generate_random_string()."'" , "'".generate_random_string(10)."@".generate_random_string(7).".com"."'"  , "'".$column_password."'"  , "'".generate_random_string()."'" , "NULL" , "NULL" , "CURRENT_TIMESTAMP"),
  );

return array(
    "fields" => $fields,
    "seeds"  => $seeds
);