<?php


echo "Creating database files: ";

$db = new PDO('mysql:host=localhost;dbname=citypost', 'pihh', '');

$stmt_create = "CREATE TABLE IF NOT EXISTS sessions (
                        `session_id` varchar(255) NOT NULL,
                        `data` text,
                        `user_agent` varchar(255) NOT NULL,
                        `ip_address` varbinary(16) NOT NULL,
                        `last_updated` int(11) NOT NULL,
                        `fingerprint` varchar(255) NOT NULL,
                        PRIMARY KEY (`session_id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

if($db->exec($stmt_create)){
    echo "/r/n Database created";
}