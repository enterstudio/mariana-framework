<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 09/01/2016
 * Time: 13:20
 */

function memory_usage(){
    $memory = memory_get_usage();

    $peak = memory_get_peak_usage();

    return compact('memory', 'peak');
}