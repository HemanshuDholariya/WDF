<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'studenthub', 3306); if ($conn->connect_error) {
die("Database Connection Error: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");


function log_action($conn, $user_id, $action, $details = '') {
$stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, details) VALUES (?,
?, ?)");
$stmt->bind_param("iss", $user_id, $action, $details);
$stmt->execute();
$stmt->close();
}
?>



