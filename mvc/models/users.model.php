<?php

use \Mariana\Framework\Model;

class Users extends Model{

    //protected static $table = "XXX";
    //protected static $primary;

    public function contacts(){
        return $this->has("contacts" ,"content", 'user_id', $limit = false);
        /*
            SELECT * FROM
                ( SELECT * FROM `users`) AS `users` ,
                ( SELECT `user_id`, GROUP_CONCAT(`content` SEPARATOR ',' ) FROM `comments`) AS `comments`
                WHERE `users`.`name` = 'pihh'
                AND `users`.`id` = `comments`.`user_id`

            Tradução:
            SELECT * FROM
                ( SELECT * FROM table1 ) as table1 ,
                ( SELECT pivot , GROUP CONCAT(what SEPARATOR ',') FROM table2 ) AS table2
                WHERE table1 clauses
                AND table1.primary = table2.key
         */
    }

}