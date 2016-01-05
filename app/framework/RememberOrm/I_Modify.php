<?php namespace Mariana\Remembers;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 1/3/2016
 * Time: 10:38 PM
 */

interface I_Modify
{
    public function add($object, array &$data);     //
    public function update($object, array &$data);  //
    public function remove($object, array $data);
}