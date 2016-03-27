<?php
/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");
// database credentials
$servername = "localhost";
$username = "root";
$password = "mysql";

/*
// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE myDB";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
*/

// Connect to MySQL
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die('Could not connect: ' . $conn->connect_error);
}

/*
// Make haze_db the current database
$db_selected = $conn->query('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'haze_db'');

if (!$db_selected) {
*/
  // If we couldn't, then it either doesn't exist, or we can't see it.
  $sql = 'CREATE DATABASE IF NOT EXISTS haze_db';
  // create the haze_db only if it doesn't already exist.
  if ($conn->query($sql) === TRUE) {
      echo "Database haze_db created successfully\n";
  } else {
      echo 'Error creating database: ' . $conn->error . "\n";
  }
//}

//mysql_close($conn);
$conn->close();
?>
