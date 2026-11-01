<?php



// 1. Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
die("Error: Form must be submitted using POST method. <a href='form.html'>Back to Form</a>");
}


// 2. Retrieve and sanitize input values
$name	= trim($_POST['name'] ?? '');
$email	= trim($_POST['email'] ?? '');
$enrollment = trim($_POST['enrollment'] ?? '');
$course	= trim($_POST['course'] ?? '');
$gender	= trim($_POST['gender'] ?? '');


$errors = [];


// 3. Server-side validation
if (empty($name)) {
$errors[] = "Name is required.";
} elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
$errors[] = "Only letters and white space allowed in name.";
}


if (empty($email)) {
$errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$errors[] = "Invalid email format.";
}


if (empty($enrollment)) {
$errors[] = "Enrollment number is required.";
}


if (empty($course)) {
$errors[] = "Please select a course.";
}


if (empty($gender)) {

$errors[] = "Please select your gender.";
}


// 4. Handle errors or save data
if (!empty($errors)) {
echo "<div style='font-family: Arial; padding: 20px;'>"; echo "<h3 style='color: red;'>Validation Errors:</h3><ul>"; foreach ($errors as $error) {
echo "<li style='color: red;'>" . htmlspecialchars($error) . "</li>";
}
echo "</ul><a href='javascript:history.back()'>Go Back and Fix Errors</a></div>"; exit;
}


// 5. Safe file writing using flock
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
mkdir($dataDir, 0777, true);
}


$file = fopen($dataDir . '/registrations.csv', 'a'); if ($file) {
// Acquire exclusive lock before writing
if (flock($file, LOCK_EX)) {
$record = [date("Y-m-d H:i:s"), $enrollment, $name, $email, $course, $gender]; fputcsv($file, $record);
flock($file, LOCK_UN); // Release lock
}
fclose($file);
}


// 6. Display success response

?>
<!DOCTYPE html>
<html>
<head>
<title>Registration Successful</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4" style="background-color: #f4f6f8;">
<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
<h3 class="text-success mb-3">&check; Registration Successful!</h3>
<p>Your details have been validated and saved to
<code>data/registrations.csv</code>.</p>
<table class="table table-bordered">
<tr><th>Enrollment</th><td><?php echo htmlspecialchars($enrollment); ?></td></tr>
<tr><th>Name</th><td><?php echo htmlspecialchars($name); ?></td></tr>
<tr><th>Email</th><td><?php echo htmlspecialchars($email); ?></td></tr>
<tr><th>Course</th><td><?php echo htmlspecialchars($course); ?></td></tr>
<tr><th>Gender</th><td><?php echo htmlspecialchars($gender); ?></td></tr>
</table>
<a href="form.html" class="btn btn-outline-primary">Register Another</a>
<a href="view.php" class="btn btn-primary" style="background-color: #1e3a8a;">View All Registrations</a>
</div>
</body>
</html>


