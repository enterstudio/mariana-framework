<?php

use Mariana\Framework\Controller;

class TestController extends Controller{

    public function index(){

        echo "<pre>";

        $u = new Users();
        $u->first_name = "josélio";
        $u->last_name = "maria";
        $u->email = "testing_save@hotmale.com";
        $u->save();
        print_r($u);


        print_r(
            Users::where("id","1",">")
                ->offset(5)
                ->first()
        );

        print_r(
            Users::where("id","1",">")
                ->offset(3)
                ->last()
        );

        print_r(
            Users::where("id","1")
                ->also("email","josemiguel@jose.com")
                ->get()
        );


/*
        print_r(
        Users::where("id","1")
            ->first()
        );



        /*
        echo "User Model - select without specific query<br>";
        print_r(
            Users::where("id","0",">")
                ->also("email","josemiguel@jose.com")
                ->get()
        );
        echo "<hr>";

        echo "User Model - select without specific query<br>";
        print_r(
            Users::where("id","0",">")
                ->offset(2)
                ->desc()
                ->get(3)
        );
        echo "<hr>";
        echo "</pre>";
        */
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

    public function post_test(){
        print_r(Users::all());
    }
}