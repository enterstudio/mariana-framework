<?php namespace Mariana\Remembers;
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 1/3/2016
 * Time: 10:38 PM
 */

interface I_DataSource
{
    public function find($object, array $criteria = array(), $order = null, $limit = null, $offset = 0);
    public function count($object, array $criteria = array());
}