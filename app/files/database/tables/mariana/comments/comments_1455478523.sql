-- table created at 2016-02-14 
 CREATE TABLE IF NOT EXISTS `mariana`.`comments` ( 
`id` INTEGER PRIMARY KEY AUTO_INCREMENT ,
`date_created` INTEGER (11) ,
`last_updated` INTEGER (11) ,
`user_id` INTEGER ,
`content` TEXT  )
 ENGINE = InnoDB