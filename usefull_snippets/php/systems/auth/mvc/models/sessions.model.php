<?php
/**
 * Created by PhpStorm.
 * User: filipe_2
 * Date: 1/18/2016
 * Time: 9:02 PM
 *
 * @Desc: Database Table Handeling Class For SESSIONS;
 */
namespace Mariana\Framework\MVC\Model;

use Mariana\Framework\Model;

class Sessions extends Model{

    public function __construct(){
        // static echo get_called_class()
        //echo get_class($this);
        $this->columnList= array(
            "id",
            "hash",
            "session"
        );
    }

}
