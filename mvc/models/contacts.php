<?php

use \Mariana\Framework\Model;

class Contacts extends Model{

    //protected $table = "contacts";
    //protected static $primary = "id";

    public function __construct()
    {
        //parent::__construct();
        $this->table = "users_contacts";
    }
}