<?php namespace Mariana\Framework;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 12/18/2015
 * Time: 10:00 PM
 */



class View {

    private $data = array();
    private $scope = array();

    protected $variable = array('{{','}}');
    protected $block = array('<' , '/>');
    protected $blockLast;
    protected $blockOptions = array(
        '<if=',
        '<else=',
        '<elseif=',
        '<while',
        '<for=',
        '<foreach=',
    );

    public function __construct($template)
    {
            $file = VIEW_PATH .DS. strtolower($template) . '.php';
            if (file_exists($file)) {
                $this->render = $file;
            } else {
                echo 'Template: '.$file.' not found in mvc/views';
            }

    }

    public function with($variable)
    {
        $variable = compact($variable);
        $this->data = $variable;
        $this->scope = json_encode($variable);

    }
    public function template(){

    }

    public function __destruct()
    {

        include_once($this->render);

    }

}