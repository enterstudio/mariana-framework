<?php

use Mariana\Framework\Controller;

class TestController extends Controller{

    public function index(){

        echo "<pre>";

        $s = new Sessions();
        $u = new Users;
        $u->id = "15";
        $u->name = "PIHH xxx";
        $u->save();

        print_r($u);

        $u = Users::where("name","pihh");
        echo "<br>";
        print_r($u);

        echo $u[4]->id;
        echo "<br>";
        print_r(Users::find(4));
        echo "<br>";

        $u = new Users();
        $u->findAndUpdate("15", array("name"=>"20 - 14"));
        print_r(Users::find(15));
        //print_r(Users::find(15));

        echo "</pre>";
    }

    public function index_2(){
        echo "First Level (/home/)";
    }

    public function index_3(){
        echo "Second Level (/home/user/)";
    }

    public function index_4(){
        echo "Third Level (/home/{}/) with params: ";
        echo '<pre>';
        print_r($this->params);
    }

    public function index_5(){
        echo "Third Level (/home/user/{}) with params: ";
        echo '<pre>';
        print_r($this->params);
    }
}