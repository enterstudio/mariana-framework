<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  */

  # Table Name
  $table = 'users';

  # Table Fields
  $fields = array(
        'id'              =>  'INTEGER PRIMARY KEY AUTO_INCREMENT',
        'date_created'    =>  'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'last_updated'    =>  'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        'name'            =>  'VARCHAR(255)',
  );

  # Table Seeds
  $seeds = array(
      array('name' => 'pihh'),
      array('name' => 'mia'),
      array('name' => 'carv'),
      array('name' => 'minga'),
  );

  return array(
        'fields' => $fields,
        'seeds'  => $seeds
  );

?>