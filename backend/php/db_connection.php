<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "phishing_detection_db";
$port = 3306;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>