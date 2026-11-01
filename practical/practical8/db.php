<?php


$host = '127.0.0.1';

$user = 'root';
$pass = '';
$dbname = 'studenthub';
$port = 3306;


// Create MySQLi connection
$conn = new mysqli($host, $user, $pass, $dbname, $port);


if ($conn->connect_error) {
die("Database Connection Failed: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");
?>


