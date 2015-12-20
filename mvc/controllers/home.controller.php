<?php

use Mariana\Framework\Controller;

class HomeController extends Controller{

    public function index(){
        echo "home controller running index";
    }

    public function second(){
        echo "second index";
    }
}