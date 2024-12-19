<?php
$host = "localhost";
$user = "root";  // your database username
$password = "";   // your database password
$database = "newsportal";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 