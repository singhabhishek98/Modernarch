<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "modernarch";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding for improved security and data integrity
$conn->set_charset("utf8mb4");
?>