<?php

use \Mariana\Framework\Model;

class Users extends Model{

    //protected static $table = "XXX";
    //protected static $primary;

    public function contacts(){
        return $this->has("contacts" ,"user_id");
    }

}