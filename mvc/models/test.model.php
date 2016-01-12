<?php

use Mariana\Framework\Model;

class Tests extends Model{
    protected $fillable= ['name'];

    public function table_name(){
        return $this->table;
    }
}