<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  */

use Mariana\Framework\Middleware;

class AuthMiddleware extends Middleware{

    // Register here all the methods this middleware makes before the request
    protected $before = array(
        'check_if_logged_in'
    );

    // Register here all the methods this middleware makes after the request
    protected $after = array();

    // public methods here
    public function check_if_logged_in($vars){
        var_dump($vars);
        echo " RUNNED THE MIDDLEWARE CRL ";
    }

    /*@example:
        protected $before = array(
            'check_if_logged_in'
        );


        public function check_if_logged_in($vars){
            // Run the confirmations
        }
    */
}
?>