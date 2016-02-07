<?php
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 2/7/2016
 * Time: 1:13 PM
 */

namespace Mariana\Framework\Cache;


interface iFile
{
    public function write($key, $value);
    public function read($key);
}