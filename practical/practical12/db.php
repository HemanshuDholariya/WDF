<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'studenthub', 3306); if ($conn->connect_error) {
die("Database Connection Error: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>



