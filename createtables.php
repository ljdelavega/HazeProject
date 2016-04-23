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
username VARCHAR(30) NOT NULL UNIQUE,
list_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
password VARCHAR(20) NOT NULL,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table User created successfully";
    echo "\r\n";
} else {
    echo "Error creating table User: " . $conn->error;
    echo "\r\n";
}

// sql to create Game table if it doesn't already exist
$sql = "CREATE TABLE IF NOT EXISTS Game (
game_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
game_name VARCHAR(50) NOT NULL,
price DECIMAL(10,2) NOT NULL,
genre VARCHAR(30) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Game created successfully";
    echo "\r\n";
} else {
    echo "Error creating table Game: " . $conn->error;
    echo "\r\n";
}

// sql to create Contains table if it doesn't already exist
$sql = "CREATE TABLE IF NOT EXISTS Contains (
list_id INT(6) UNSIGNED,
game_id INT(6) UNSIGNED,
PRIMARY KEY (list_id, game_id),
FOREIGN KEY (list_id) REFERENCES User(list_id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (game_id) REFERENCES Game(game_id) ON DELETE CASCADE ON UPDATE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Contains created successfully";
    echo "\r\n";
} else {
    echo "Error creating table Contains: " . $conn->error;
    echo "\r\n";
}

// sql to create Reviews table if it doesn't already exist
$sql = "CREATE TABLE IF NOT EXISTS Reviews (
username VARCHAR(30) NOT NULL,
game_id INT(6) UNSIGNED NOT NULL,
rating INT(1) UNSIGNED,
text_review TEXT,
PRIMARY KEY (username, game_id),
FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (game_id) REFERENCES Game(game_id) ON DELETE CASCADE ON UPDATE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Reviews created successfully";
    echo "\r\n";
} else {
    echo "Error creating table Reviews: " . $conn->error;
    echo "\r\n";
}

// sql to create CompletionState table if it doesn't already exist
$sql = "CREATE TABLE IF NOT EXISTS CompletionState (
list_id INT(6) UNSIGNED,
game_id INT(6) UNSIGNED,
hours INT(8) UNSIGNED,
percent_complete DECIMAL(2,1),
date_completed DATETIME,
state VARCHAR(30),
PRIMARY KEY (list_id, game_id),
FOREIGN KEY (list_id) REFERENCES User(list_id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (game_id) REFERENCES Game(game_id) ON DELETE CASCADE ON UPDATE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table CompletionState created successfully";
    echo "\r\n";
} else {
    echo "Error creating table CompletionState: " . $conn->error;
    echo "\r\n";
}

//mysql_close($conn);
$conn->close();
?>
