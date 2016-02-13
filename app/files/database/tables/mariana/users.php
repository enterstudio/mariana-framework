<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  */

  # Table Name
  $table = users;

  # Table Fields
  $fields = array(
        'id'              =>  'INTEGER PRIMARY KEY',
        'date_created'    =>  'TIMESTAMP',
        'last_updated'     =>  'TIMESTAMP',
  );

  # Table Seeds
  $seeds = array();

  return array(
        'fields' => $fields,
        'seeds'  => $seeds
  );

?>