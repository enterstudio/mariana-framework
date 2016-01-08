<?php

use \Mariana\Framework\Model;

class Users extends Model{

    //protected static $table = "XXX";
    //protected static $primary;

    public function contactNumbers(){
        return $this->hasOne("Contacts" ,"user_id");
    }
}