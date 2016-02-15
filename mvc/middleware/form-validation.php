<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  */

use Mariana\Framework\Middleware;

class FormValidation extends Middleware{

    public function before(){
        /**
          * @desc: Runs before the request
          * @example: Authorization Middleware should check if the email and password requests are set before running the script
          */

    }

    public function after(){
        /**
         *  @desc: Runs after the request
         *  @example: Authorization Middleware should check if after the user logs in, for example check if the session was set
         */
    }
}
?>