<?php namespace Mariana\Framework\Design;

use Mariana\Framework\Design\iObserver;
use Mariana\Framework\Design\iObserverSubject;


/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 14/01/2016
 * Time: 09:16
 *
 * @Usage: quando temos vários objectos que precisam de um update quando outro objecto muda.
 * @Example: notificações no mercado de acções para quem se mostrou interessado na acção que actualizou.
 * @Vantagens: Loose coupling
 * @Desvantagem: Pode mandar updates que na interessem.
 *
 * @Notes: Não preciso do código para um observer nem uma classe para o observer visto que o php tem as spl
 *
 * NO ENTANTO O MEU PROPÓSITO É APRENDER O MAIS POSSÍVEL, NÃO QUERO REINVENTAR A RODA, QUERO SABER MEXER NELA CASO ELA ESTOIRE POR ALGUM MOTIVO
 *
 * @SPLFunctions:
 *      SplSubject
 *          attach(Observer $o)
 *          detach(Observer $o)
 *          notify()
 *      SplObserver
 *          update(SplSubject $s)
 *
 * Example:
 */


class User implements iObserverSubject {

    const UNKNOWN_USER = 1;
    const INCORRECT_PWD = 2;
    const ALREADY_LOGGED_IN = 3;
    const ALLOW = 4;

    private $storage = array();

    public function __set($key, $value){
        $this->{$key} = $value;
    }

    public function __get($key){
        return $this->{$key};
    }

    public function attach(iObserver $o){
        // TODO: Implement attach() method.
        array_push($this->storage, $o);
    }

    public function detach(iObserver $o){
        // TODO: Implement detach() method.
        foreach($this->storage as $storage => $eval){
            if ($eval == $o){
                unset($eval);
            }
        }
    }

    public function notify(){
        // TODO: Implement notify() method.
        foreach ( $this->storage as $observer ) {
            $observer->update( $this );
        }
    }
}

class Situation implements iObserver{

    public function __set($key, $value){
        $this->{$key} = $value;
    }

    public function __get($key){
        return $this->{$key};
    }

    public function update(iObserverSubject $s){
        echo $s->name." : ".$this->problem."<br>";
    }
}

echo "<h1>Observe this: </h1>";

$pihh = new User();
$mia = new User();

$pihh->name = "pihh";
$mia->name = "mia";

$fart = new Situation();
$fart->problem = "just won the lottery";

$nauseousSmell = new Situation();
$nauseousSmell->problem = "has infinite possibilitys of spending Pihh's money but doesn't know from where to start";

$fart->update($pihh);
$nauseousSmell->update($mia);

