<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 17/01/2016
 * Time: 12:47
 */
namespace Mariana\Framework\Auth;

use Mariana\Framework\Config;
use Mariana\Framework\Database;
use Mariana\Framework\MVC\Model\Sessions;
use Mariana\Framework\Session\Session;
use Mariana\Framework\Session\Cookie;
use Mariana\Framework\Session\Flash;
use Mariana\Framework\Security\Criptography;

Class Auth extends Controller{

    private $errors = array();

    public function signin(Array $params = array()){
        /**
         * @Desc: needs two params : email + password
         * @Desc2: Optional params : keepAlive
         */

        /*
         * TODO: Confirm the system.
         * TODO: Error checking and Flash Session
         */
        $email = $params["email"];
        $password = $params["'password'"];

        $u = \Users::where("email", $email)->first();

        // STEP 2: Compare
        $compare = Criptography::compare($password,$u["password"]);

        if($compare){
            Session::set("user_id", $u["id"]);
            Session::set("email", $u["email"]);

            // STEP 3: SET INFINITE SESSION
            if(isset($params["keepAlive"])){
                $this->setRememberMe();
            }
        }
    }

    public function signout(Array $params = array()){

    }

    public function signup(Array $params = array()){

    }

    public function setRememberMe(){
        //  STEP 1: Generate The Hash.
        $key = rand(1,100000).time();
        $hash = Criptography::encript($key);

        //  STEP 2: Store the key/hash combination + the session as JSON Array on the database
        $session = new Sessions();
        $session->hash = $key;
        $session->json_session = json_encode($_SESSION);
        $session->saveAndGetId();

        //Set the cookie with the ID and the cookie with the hash
        Cookie::set(Config::get("cookieID"),$session);
        Cookie::set(Config::get("cookieHash"),$hash);
    }

    public function checkRememberMe(){

        //  STEP 1: Check if there is a active session..
        if(!isset($_SESSION["id"]) || empty($_SESSION["id"])){

            //STEP 2: Check for cookie that identifies this session (set at config)...
            if(isset($_COOKIE(Config::get("cookieID"))) && isset($_COOKIE[Config::get("cookieHash")])){

                // STEP 3: Decrypt the hash
                $decriptedHash = Criptography::decript($_COOKIE[Config::get("cookieHash")]);
                $confirmation = Sessions::where("id", $_COOKIE[Config::get("cookieID")])
                    ->also("hash", $decriptedHash)
                    ->first();

                // STEP 4: if exists in the database:
                $setSession = json_decode($confirmation["json_session"]);
                foreach($setSession as $key => $pair){
                    Session::set($key,$pair);
                }

            }

        }

    }

}
