<?php

ini_set('display_errors', 1);
define('DSN', 'mysql:dbhost=localhost;dbname=chess');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

session_start();

try{
   $db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
} catch(\PDOException $e) {
  echo $e->getMessage();
}
