<?php namespace Mariana\Remembers;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 1/3/2016
 * Time: 10:38 PM
 */

interface I_Transaction
{
    public function beginTransaction();
    public function commit();
    public function rollBack();
}