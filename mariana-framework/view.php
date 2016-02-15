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

    protected static $variable;
    protected static $block;
    protected static $settings;
    protected static $blockLast;
    protected static $blockOptions = array(
        'if=',
        'else=',
        'elseif=',
        'while',
        'for=',
        'foreach=',
    );

    public static function render($template, Array $scope = array()){

        $file = VIEW_PATH .DS. strtolower($template);

        self::$settings = Config::get('template-engine');

        if (file_exists($file)) {
            if(self::$settings['active']){

                self::parseTemplate($template, $scope);
            }else{
                include_once($file);
            }

        } else {
            echo 'Template: '.$file.' not found in mvc/views';
        }
    }

    private static function parseTemplate($template, $scope){
        $file = file_get_contents($file = VIEW_PATH .DS. strtolower($template));
        $count = 0;
        str_replace(self::$settings['variable'][0],'<?= $scope[\'', $file);
        str_replace(self::$settings['variable'][1],'\'] ?>',$file);
        echo $file;
    }

}