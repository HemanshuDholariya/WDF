<?php
require_once 'db.php';
$id = (int)($_GET['id'] ?? 0);
$error = '';


$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
$stmt->close();


if (!$event) die("Event not found. <a href='events.php'>Back</a>");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
$title	= trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$event_date = trim($_POST['event_date'] ?? '');
$category	= trim($_POST['category'] ?? '');
$status	= trim($_POST['status'] ?? 'Open');
$posterName = $event['poster']; // keep existing


// Handle new poster upload
if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
$allowed = ['image/jpeg', 'image/png', 'image/gif'];
$fileType = mime_content_type($_FILES['poster']['tmp_name']); if (!in_array($fileType, $allowed)) {
$error = "Invalid file type. Only JPG, PNG, GIF allowed.";
} elseif ($_FILES['poster']['size'] > 2 * 1024 * 1024) {
$error = "File too large. Max 2 MB.";
} else {
$ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
$posterName = 'event_' . time() . '_' . rand(100, 999) . '.' . $ext; move_uploaded_file($_FILES['poster']['tmp_name'],  DIR	. '/uploads/' .
$posterName);
}
}


if (empty($error)) {
$upd = $conn->prepare("UPDATE events SET title=?, description=?, event_date=?, category=?, status=?, poster=? WHERE id=?");
$upd->bind_param("ssssssi", $title, $description, $event_date, $category, $status,
$posterName, $id);
if ($upd->execute()) {
header("Location: events.php?msg=Event+updated+successfully!"); exit;
} else {
$error = "Update failed: " . $conn->error;
}
$upd->close();
}
}
?>
<!DOCTYPE html>

<html>
<head>
<title>Edit Event - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body { background: #f4f6f8; padding: 25px; } .box { max-width: 550px; margin: auto; background: white; padding: 30px; border-radius: 8px; }</style>
</head>
<body>
<div class="box shadow-sm">
<h3 style="color: #1e3a8a;" class="mb-3">Edit Event</h3>
<?php if ($error): ?><div class="alert alert-danger small py-2"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="POST" enctype="multipart/form-data">
<div class="mb-3"><label class="form-label">Event Title:</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required></div>
<div class="mb-3"><label class="form-label">Description:</label><textarea name="description" class="form-control" rows="2"><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea></div>
<div class="mb-3"><label class="form-label">Event Date:</label><input type="date" name="event_date" class="form-control" value="<?php echo htmlspecialchars($event['event_date']); ?>" required></div>
<div class="mb-3"><label class="form-label">Category:</label>
<select name="category" class="form-select" required>
<?php foreach (['Workshop','Seminar','Cultural','Sports','Technical'] as $c): ?>
<option value="<?php echo $c; ?>" <?php if ($event['category'] === $c) echo 'selected'; ?>><?php echo $c; ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="mb-3"><label class="form-label">Status:</label>
<select name="status" class="form-select">
<option value="Open" <?php if ($event['status'] === 'Open') echo 'selected';
?>>Open</option>

<option value="Closed" <?php if ($event['status'] === 'Closed') echo 'selected';
?>>Closed</option>
</select>
</div>
<div class="mb-3"><label class="form-label">Replace Poster (optional):</label><input type="file" name="poster" class="form-control" accept="image/*">
<?php if (!empty($event['poster'])): ?><small class="text-muted">Current: <?php echo htmlspecialchars($event['poster']); ?></small><?php endif; ?>
</div>
<button type="submit" class="btn btn-warning">Update Event</button>
<a href="events.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>


