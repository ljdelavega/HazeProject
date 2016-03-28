<?php
/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");

// database credentials
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "haze_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create User table if it doesn't already exist
$sql = "CREATE TABLE IF NOT EXISTS User (
username VARCHAR(20) NOT NULL,
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
password VARCHAR(20) NOT NULL,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
reg_date TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table User created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


//mysql_close($conn);
$conn->close();
?>
