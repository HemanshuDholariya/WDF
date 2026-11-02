<?php
require_once 'db.php';
$id = (int)($_GET['id'] ?? 0);
$error = '';


$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();


if (!$student) {
die("Student record not found. <a href='students.php'>Back</a>");
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$course = trim($_POST['course'] ?? '');

$year  = (int)($_POST['year'] ?? 1);
$phone = trim($_POST['phone'] ?? '');


$upd = $conn->prepare("UPDATE students SET name = ?, email = ?, course = ?, year = ?, phone = ? WHERE id = ?");
$upd->bind_param("sssdsi", $name, $email, $course, $year, $phone, $id);


if ($upd->execute()) {
header("Location: students.php?msg=Student+updated+successfully!"); exit;
} else {
$error = "Update failed: " . $conn->error;
}
$upd->close();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Student - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body { background-color: #f4f6f8; padding: 25px; } .box { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 8px; }</style>
</head>
<body>
<div class="box shadow-sm">
<h3 style="color: #1e3a8a;" class="mb-3">Edit Student Details</h3>
<?php if ($error): ?><div class="alert alert-danger small py-2"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="POST">

<div class="mb-3"><label class="form-label">Full Name:</label><input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']);
?>" required></div>
<div class="mb-3"><label class="form-label">Email:</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']);
?>" required></div>
<div class="mb-3"><label class="form-label">Course:</label>
<select name="course" class="form-select" required>
<option value="Computer Engineering" <?php if ($student['course'] === 'Computer Engineering') echo 'selected'; ?>>Computer Engineering</option>
<option value="Information Technology" <?php if ($student['course'] === 'Information Technology') echo 'selected'; ?>>Information Technology</option>
<option value="Electronics" <?php if ($student['course'] === 'Electronics') echo 'selected'; ?>>Electronics</option>
<option value="Mechanical" <?php if ($student['course'] === 'Mechanical') echo 'selected'; ?>>Mechanical</option>
</select>
</div>
<div class="mb-3"><label class="form-label">Year:</label><input type="number" name="year" min="1" max="4" class="form-control" value="<?php echo $student['year'];
?>" required></div>
<div class="mb-3"><label class="form-label">Phone:</label><input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']
?? ''); ?>"></div>
<button type="submit" class="btn btn-warning">Update Student</button>
<a href="students.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>


