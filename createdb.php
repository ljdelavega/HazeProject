<?php
/* Note: Always load the config file for each page */
require_once("resources/config.php");
require_once(TEMPLATES_PATH . "/header.php");
// database credentials
$servername = "localhost";
$username = "root";
$password = "mysql";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die('Could not connect: ' . $conn->connect_error);
}

$sql = 'CREATE DATABASE IF NOT EXISTS haze_db';
// create the haze_db only if it doesn't already exist.
if ($conn->query($sql) === TRUE) {
    echo "Database haze_db created successfully\n";
} else {
    echo 'Error creating database: ' . $conn->error . "\n";
}

$conn->close();
?>
