<?php namespace Mariana\Framework;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 12/18/2015
 * Time: 9:53 PM
 *
 * Asset Management: Include assets and stuff
 */

class Resource{

    private $middle_path;
    private $path;

    public function __construct($path){
        $this->path = trim($path,"/");
    }

    public  function asset(){
        $this->middle_path="";

    }

    public function js(){
        $this->middle_path="js/";
    }

    public function css(){
        $this->middle_path="css/";
    }

    public function img(){
        $this->middle_path="img";
    }

}