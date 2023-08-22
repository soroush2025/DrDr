<?php
function check()
{
  return "drdr";
}

//set my sql Parameters
$servername = "localhost";
$database = "drdr2";
$username = "drdr";
$password = "drdr";
$port = 3306;
$mysqli = null;
try {
  // Create a connection
  $mysqli = new mysqli($servername, $username, $password, $database, $port);
  $mysqli->set_charset("utf8mb4");

  // Check the connection
  if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $query = "CREATE TABLE IF NOT EXISTS `drdr`.`token3`(
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(256) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `token` VARCHAR(256) NOT NULL,
  `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`)
) ENGINE = INNODB;";

  $result = $mysqli->query($query);
  if (!$result) {
    echo "Error description: " . $mysqli->error;
  }
  $mysqli->get_connection_stats();
  $mysqli->get_server_info();
} catch (\Throwable $th) {
  //throw $th;
}

if (!is_null($mysqli)) {
  echo "isset already";
}
