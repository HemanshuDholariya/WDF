<?php
require_once 'db.php';
$error = '';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
$title	= trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$event_date = trim($_POST['event_date'] ?? '');
$category	= trim($_POST['category'] ?? '');
$status	= trim($_POST['status'] ?? 'Open');
$posterName  = null;


if (empty($title) || empty($event_date) || empty($category)) {
$error = "Title, date, and category are required.";
} else {
// Handle poster upload with validation
if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
$allowed = ['image/jpeg', 'image/png', 'image/gif'];
$maxSize = 2 * 1024 * 1024; // 2 MB


$fileType = mime_content_type($_FILES['poster']['tmp_name']);
$fileSize = $_FILES['poster']['size'];


if (!in_array($fileType, $allowed)) {
$error = "Invalid file type. Only JPG, PNG, GIF allowed.";
} elseif ($fileSize > $maxSize) {
$error = "File too large. Maximum size is 2 MB.";
} else {
// Rename file to prevent overwrite
$ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
$posterName = 'event_' . time() . '_' . rand(100, 999) . '.' . $ext;
$uploadDir =   DIR	. '/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true); move_uploaded_file($_FILES['poster']['tmp_name'], $uploadDir . $posterName);
}
}


if (empty($error)) {
$stmt = $conn->prepare("INSERT INTO events (title, description, event_date, category, status, poster) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $title, $description, $event_date, $category, $status,
$posterName);
if ($stmt->execute()) {
header("Location: events.php?msg=Event+added+successfully!"); exit;
} else {
$error = "Failed to add event: " . $conn->error;
}
$stmt->close();
}
}
}
?>
<!DOCTYPE html>

<html>
<head>
<title>Add Event - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body { background: #f4f6f8; padding: 25px; } .box { max-width: 550px; margin: auto; background: white; padding: 30px; border-radius: 8px; }</style>
</head>
<body>
<div class="box shadow-sm">
<h3 style="color: #1e3a8a;" class="mb-3">Add New Event</h3>
<?php if ($error): ?><div class="alert alert-danger small py-2"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="POST" enctype="multipart/form-data">
<div class="mb-3"><label class="form-label">Event Title:</label><input type="text" name="title" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Description:</label><textarea name="description" class="form-control" rows="2"></textarea></div>
<div class="mb-3"><label class="form-label">Event Date:</label><input type="date" name="event_date" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Category:</label>
<select name="category" class="form-select" required>
<option value="Workshop">Workshop</option>
<option value="Seminar">Seminar</option>
<option value="Cultural">Cultural</option>
<option value="Sports">Sports</option>
<option value="Technical">Technical</option>
</select>
</div>
<div class="mb-3"><label class="form-label">Status:</label>
<select name="status" class="form-select"><option value="Open">Open</option><option value="Closed">Closed</option></select>
</div>

<div class="mb-3"><label class="form-label">Upload Poster (JPG/PNG/GIF, max 2MB):</label><input type="file" name="poster" class="form-control" accept="image/*"></div>
<button type="submit" class="btn btn-primary" style="background-color: #1e3a8a;">Save Event</button>
<a href="events.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>


