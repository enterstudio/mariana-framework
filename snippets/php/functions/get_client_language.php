<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 09/01/2016
 * Time: 13:14
 */

function get_client_language($availableLanguages, $default='en'){
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $langs=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);


        foreach ($langs as $value){
            $choice=substr($value,0,2);
            if(in_array($choice, $availableLanguages)){
                return $choice;
            }
        }

    }
    return $default;
}