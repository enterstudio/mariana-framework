<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  */

  # Table Name
  $table = 'comments';

  # Table Fields
  $fields = array(
        'id'              =>  'INTEGER PRIMARY KEY',
        'date_created'    =>  'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'last_updated'     =>  'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
  );

  # Table Seeds
  $seeds = array();

  return array(
        'fields' => $fields,
        'seeds'  => $seeds
  );

?>