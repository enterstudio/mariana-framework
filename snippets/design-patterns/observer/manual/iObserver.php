<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 14/01/2016
 * Time: 10:56
 *
 * @Note: this is not the best practice. PHP contains Standard PHP Librarys and the observer pattern exists on it.
 */

namespace Mariana\Framework\Design;

use Mariana\Framework\Design\iObserverSubject;

interface iObserver{
    public function update(iObserverSubject $s);
}