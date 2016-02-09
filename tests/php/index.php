<?php
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 2/8/2016
 * Time: 11:11 PM
 *
 * Truques:
 * @ covers functionName -> diz que o teste cobre a função tal
 * @ dataProvider functionName -> cria-se uma função qu retorna uma array de returs . Se algum dos returns falhar diz que falhou.
 * @ depends functionName ->faz o teste e falha caso alguma das dependencias falhe.
 *
 * Funções:
 * Template Methods: setUp() + tearDown()
 *      - setUp cria o objecto que vamos testar: ex: $this->instance = new Baseball();
 *      - tearDown remove a instance e limpa o objcto: ex: unset($this->instance);
 * Aceder a privates: invokeMethod(&$object, methodName, array $parameters = array())
 *  @ param object &$object     Objecto que queremos correr
 *  @ param string $method      Nome do metodo
 *  @ param array  $parameters  Parametros
 *  @ return mixed Method return
 *      function invokeMethod(&$object, methodName, array $parameters = array()){
 *          $reflection = new \ReflectionClass(get_class($object));
 *          $method = $reflection->getMethod($methodName);
 *          $method->setAccessability(true);
 *          return $method->invokeArgs($object, $parameters);
 *      }
 *
 * Stubs: Mocks definem expectativas. Stubs fazem o mm mas substitui comportamento
 *
 * Coverage: Gera reports para cada teste:
 *  phpunit --coverage-html coverage
 *
 * Comandos:
 * $this->assertTrue()  -> verifica se é true or false
 * $this->assertEqual() -> verifica se é igual
 *
 * MockObjects: Simulates the real data returning the predefined values ( útil quando trabalhamos com API's externas ou com código que ainda não está feito: ex database results )
 *  Nota: ignoram: final, static e privates;
 *  Nota: muita gente usa a Mockery/Mockery lib para fazer mock objects m vez dos do phpunit. eu vou fazer o mesmo porque estes são muito mais legiveis e não fazem conflito
 *      EX USING PHPUnit Mocks:
 *          function testMockObject(){
 *              $baseball = $this->getMock('Baseball',array('submitBat'));
 *              $baseball->expects($this->any())
 *                  ->method('submitAtBat')
 *                  ->will($this->returnValue(true));
 *              $result = $baseball->submitAtBat('1','bh'); // return true
 *              $expected = true;
 *              $this->assertEquals($expected,$result,'Message');
 *          }
 *      EX USING MOCKERY:
 *          function testMockery(){
 *              $obj = new Baseball();
 *              $val = true;
 *              $mock = new \Mockery::mock('Baseball');
 *              $mock->shouldRecieve('submitAtBat')->with('1','bh')->once()->andReturn($val);
 *          }
 */