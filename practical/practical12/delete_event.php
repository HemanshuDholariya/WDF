<?php
require_once 'db.php';
$id = (int)($_GET['id'] ?? 0);


if ($id > 0) {
// Remove poster file if exists
$stmt = $conn->prepare("SELECT poster FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$ev = $stmt->get_result()->fetch_assoc();
$stmt->close();


if ($ev && !empty($ev['poster'])) {
$path =  DIR	. '/uploads/' . $ev['poster']; if (file_exists($path)) unlink($path);

}


$del = $conn->prepare("DELETE FROM events WHERE id = ?");
$del->bind_param("i", $id);
$del->execute();
$del->close();


header("Location: events.php?msg=Event+deleted+successfully!");
} else {
header("Location: events.php");
}
exit;
?>


