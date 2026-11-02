<?php
require_once 'db.php';


$id = (int)($_GET['id'] ?? 0);


if ($id > 0) {
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id); if ($stmt->execute()) {
header("Location: students.php?msg=Student+deleted+successfully!");
} else {
header("Location: students.php?msg=Failed+to+delete+student");
}
$stmt->close();
} else {
header("Location: students.php");
}
exit;
?>


