<?php

use Mariana\Framework\Controller;
use Mariana\Framework\Security\Criptography;
use Mariana\Framework\View;


class TestController extends Controller{

    public function index(){

        echo '<pre>' , var_dump(Users::where('name','=','0')->get(1)), '</pre>';
        echo '<pre>' ;
            foreach((array)\Mariana\Framework\Session\Flash::showMessages() as $m){ echo $m; };
        echo '</pre>';
        echo '<pre>'; if(isset($_FILES)){print_r($_FILES); }; '</pre>';
        $v = new View('upload');


        /*
        $to = "filipemotasa@hotmail.com";
        $subject = "teste";

        $e = new Mariana\Framework\Email\Email();
        //$e->SendMail("teste@pihh.rocks",$to,$subject,"lorem ipsum");
        //include_once(ROOT."/mvc/views/layout.php");

        $v = new View('layout');
        //Log::store('security','teste se isto está a fazer o log como deve de ser',true);


        $c = new \Mariana\Framework\Cache\Cache();

        if($c->isCache('feedzz')){
           $c->write('feedzz' , 'xyzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz');
        }
        echo $c->read('feedzz');

        /*
        $scope = array($to,$subject);
        $v = new View("layout.html");
        $v->with($scope);
        /*
                $u = Users::find(1);
                print_r($u);

                $content = file_get_contents($_SERVER['DOCUMENT_ROOT']."/app/framework/email/templates/bluechimp/base_boxed_2column_query.html");
                echo $content;


                //echo "<pre>";
        /*
                $u = Users::find(33);
                $u->contactNumber();
                print_r($u->toArray());

                print_r($u);
        *//*
        $u = Users::find(33);
        $u->contactNumbers();
        print_r($u->toArray());

        print_r($u);

*
        $u = Users::where("id",">","33")->get();
        $u->usersContactNumber();

        print_r($u);


        $u = Users::where("id",">","33")->get();
        $u->usersContactNumbers();

        print_r($u);
*/

/*
        $u = Users::find(33);
        $u->contactNumbers();
        print_r($u->toArray());

       // print_r(->contactNumber());
        print_r(Users::find(33)->contactNumbers());

        //print_r(Users::find(33));

/*
        echo "<hr>";
        print_r(
            Users::where("id","1",">")
                ->get()
                ->toArray()
        );
        echo "<hr>";
*//*
        $u = new Users();
        $u->first_name = "josélio";
        $u->last_name = "maria";
        $u->email = "testing_save@hotmale.com";
        $u->save();
        print_r($u);

        $u = new Users(2);
        $u->first_name = "peter";
        $u->last_name = "north";
        $u->email = "xxx_horse_power@hotmale.com";
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
                ->also("email","testing_save@hotmale.com")
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
        $u = new Mariana\Framework\Upload\Upload();
        $u->multiple('files');
        $this->index();
    }
}