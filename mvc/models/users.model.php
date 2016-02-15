<?php

use \Mariana\Framework\Model;

class Users extends Model{

    //protected $table = "XXX";
    //protected $primary;

    public function contacts($many = false){
        return $this->has('comments','user_id','content', false, $many);
    }

    public function notify(){

    }

}