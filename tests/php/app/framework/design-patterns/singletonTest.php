<?php
namespace Mariana\Framework\UnitTests;

use \PHPUnit_Framework_TestCase;
use Mariana\Framework\Design\Singleton;

class Unique extends Singleton{

    public $id;
    public $test;

    public function get($key){
        return $this->{$key};
    }

    public function set($key , $value){
        $this->{$key} = $value;
    }

}

class singletonTest extends PHPUnit_Framework_TestCase
{
    public function test_singleton_uniqueness(){
        $s = Unique::getInstance();
        $s->set('id', '1');
        $s2 = Unique::getInstance();

        //$this->assertTrue($s2->id === '1');
        $this->assertEquals($s->get('id'),$s2->get('id'),"SingletonTest->test_singleton_uniqueness: S1 e S2 não são iguals");
    }
}