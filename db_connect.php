<?php
// Database connection settings
$host = 'localhost';       // Database host (e.g., localhost)
$username = 'root';        // Database username
$password = '';            // Database password
$database = 'benflismotors'; // Database name

// Create a connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}
?>
