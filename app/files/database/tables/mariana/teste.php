<?php
/**
  * Created with love using Mariana Framework
  * Need help? Ask Pihh The Creator pihh.rocks@gmail.com
  */

  # Table Name
  $table = 'teste';

  # Table Fields
  $fields = array(
        'id'              =>  'INTEGER PRIMARY KEY AUTO_INCREMENT',
        'date_created'    =>  'INTEGER (11)',
        'last_updated'    =>  'INTEGER (11)',
  );

  # Table Seeds
  # Put here info on how you want to populate the database:
  /*
   * Example:
   *  $i = 0;
   *  while($i < 50){
   *    array_push($seeds,array('date_created'=> time(), 'last_updated' => time());
   *    $i++;
   *  }
   */
  $seeds = array();

  # Constraints
  # Put here the constraints
  # Example:
  /*
    $constaints = array(
        'primary_key' => 'id',
        'unique'      => array('date_created','last_updated'),  //stupid example but serves as proof of concept
        'foreign_key' => array(
            'key'=> $this_table_key,
            'table'=>$other_table_name,
            'reference'=> $other_table_key,
            'options'  => 'Example: on update cascade text'
            )
    );
  */
  $constaints = array();

  return array(
        'fields' => $fields,
        'seeds'  => $seeds
  );

?>