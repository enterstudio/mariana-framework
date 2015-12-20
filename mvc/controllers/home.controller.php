<?php

use Mariana\Framework\Controller;

class HomeController extends Controller{

    public function index(){
        echo "home controller running index";
        $result["tests"] = Tests::all();
        $result["sessions"] = Sessions::all();
        echo "<pre>".print_r($result);
    }

    public function second(){
        echo "second index";
    }
}