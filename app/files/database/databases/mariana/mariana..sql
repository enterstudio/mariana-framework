CREATE DATABASE IF NOT EXISTS `mariana`
                CHARACTER SET utf8
                COLLATE utf8_unicode_ci;
                CREATE USER 'root'@'localhost' IDENTIFIED BY '';
                GRANT ALL ON `mariana`.* TO 'root'@'localhost';
                FLUSH PRIVILEGES;