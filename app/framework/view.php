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

    private $render = FALSE;

    public function __construct($template)
    {
        //try {
            $file = VIEW_PATH . strtolower($template) . '.php';

            if (file_exists($file)) {
                $this->render = $file;
            } else {
                //throw new customException('Template ' . $template . ' not found!');
            }
        //}
        //catch (customException $e) {
            //echo $e->errorMessage();
        //}
    }

    public function with($variable)
    {
        $variable = compact($variable);
        $this->data = $variable;
        $this->scope = json_encode($variable);
    }

    public function __destruct()
    {
        extract($this->data);
        include_once($this->render);

    }

}