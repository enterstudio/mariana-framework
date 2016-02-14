-- table created at date(Y-m-d) 
 CREATE TABLE IF NOT EXISTS `mariana`.`users` ( 
`id` INTEGER PRIMARY KEY AUTO_INCREMENT ,
`date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
`last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`name` VARCHAR(255)  )
 ENGINE = InnoDB