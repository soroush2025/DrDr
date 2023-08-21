<?php
function check()
{
  return "drdr";
}

//set my sql Parameters
$servername = "localhost";
$database = "drdr";
$username = "drdr";
$password = "drdr";
$port = 3306;

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

$mysqli->get_server_info();
