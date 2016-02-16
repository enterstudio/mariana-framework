<?php namespace Mariana\Framework;

abstract class Middleware{

    protected $before = array();
    protected $after = array();

    public function before(Array $array = array()){
        /**
         * @desc: Runs before the request
         * @example: Authorization Middleware should check if the email and password requests are set before running the script
         * @detail: $array = array((method,array(params)))
         */
        if(in_array($array[0],$this->before)){
            if(method_exists($this,$array[0])){
                $this->$array[0]($array[1]);
            }
        }
    }

    public function after(Array $array = array()){
        /**
         *  @desc: Runs after the request
         *  @example: Authorization Middleware should check if after the user logs in, for example check if the session was set
         */
        if(in_array($array[0],$this->after)){
            if(method_exists($this,$array[0])){
                $this->$array[0]($array[1]);
            }
        }
    }


}