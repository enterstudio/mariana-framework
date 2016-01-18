<?php namespace Mariana\Framework\Security;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 1/15/2016
 * Time: 9:26 PM
 *
 * @Desc: Simple class that read the .env and creates the environment
 */

use Mariana\Framework\Design\Singleton;

class Environment extends Singleton{

    private static $env;
    private static $setup = 0;

    public static function setup(){
        self::$env = ROOT."/.env";
        if(self::$setup !== 1){
            self::$setup = 1;

            $env = file(self::$env);
            foreach($env as $line){
                //  Remover comentários:
                $line = strpos($line, "#") ? substr($line, 0, strpos($line, "#")) : $line;

                //  Caso não esteja vazio: POW
                if(strlen(trim($line))>0){

                    //  Meter em env
                    putenv (trim($line));
                    $newEnvVar = explode("=",$line);
                    //  Meter em $_ENV
                    if(isset($newEnvVar[1])) {
                        $key = trim($newEnvVar[0]);
                        $value = trim($newEnvVar[1]);
                        $_ENV[$key] = $value;
                    }
                }

            }
        }
    }
}
