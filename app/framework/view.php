<?php namespace Mariana\Framework;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 12/18/2015
 * Time: 10:00 PM
 */

class View {

    private static function withJson($file){
        $file = str_replace("{{","<?=",$file);
        $file = str_replace("}}","?>",$file);

       $file = str_replace("@include(", "ob_get_contents(", $file);
        return $file;
    }

    public static function render($path,$array = array()){

        ob_start();
            include(VIEW_PATH.DS.$path.DS."index.html");
            $content = ob_get_contents();
            $content = self::withJson($content);
        ob_end_clean();

        echo $content;
    }
}