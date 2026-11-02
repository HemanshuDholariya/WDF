<?php
require_once 'db.php';
$error = '';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$course = trim($_POST['course'] ?? '');
$year  = (int)($_POST['year'] ?? 1);
$phone = trim($_POST['phone'] ?? '');


if (empty($name) || empty($email) || empty($course)) {
$error = "Name, email, and course are required fields.";
} else {
$stmt = $conn->prepare("INSERT INTO students (name, email, course, year, phone) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssds", $name, $email, $course, $year, $phone);


if ($stmt->execute()) {
header("Location: students.php?msg=Student+added+successfully!"); exit;
} else {
$error = "Failed to add student: " . $conn->error;

}
$stmt->close();
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Student - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body { background-color: #f4f6f8; padding: 25px; } .box { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 8px; }</style>
</head>
<body>
<div class="box shadow-sm">
<h3 style="color: #1e3a8a;" class="mb-3">Add New Student</h3>
<?php if ($error): ?><div class="alert alert-danger small py-2"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="POST">
<div class="mb-3"><label class="form-label">Full Name:</label><input type="text" name="name" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Email:</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Course:</label>
<select name="course" class="form-select" required>
<option value="Computer Engineering">Computer Engineering</option>
<option value="Information Technology">Information Technology</option>
<option value="Electronics">Electronics</option>
<option value="Mechanical">Mechanical</option>
</select>
</div>

<div class="mb-3"><label class="form-label">Year:</label><input type="number" name="year" min="1" max="4" value="1" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Phone:</label><input type="text" name="phone" class="form-control"></div>
<button type="submit" class="btn btn-primary" style="background-color: #1e3a8a;">Save Student</button>
<a href="students.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>


