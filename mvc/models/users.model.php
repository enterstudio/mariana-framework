<?php

use \Mariana\Framework\Model;

class Users extends Model{

    //protected static $table = "XXX";
    //protected static $primary;

    public function contactNumber(){
        return $this->hasOne("Contacts" ,"user_id");
    }

    public function contactNumbers(){
        return $this->hasMany("Contacts", "user_id");
    }

    public function usersContactNumber(){
        return $this->manyHaveOne("Contacts","user_id");
    }


    public function usersContactNumbers(){
        return $this->manyHaveMany("Contacts","user_id");
    }
}