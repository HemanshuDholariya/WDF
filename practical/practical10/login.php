<?php


require_once 'db.php';


$message = '';

$msgType = '';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
$username = trim($_POST['username'] ?? '');
$email	= trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$role	= $_POST['role'] ?? 'student';


// 1. Server-side validation
if (empty($username) || empty($email) || empty($password)) {
$message = "All required fields must be filled.";
$msgType = "danger";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$message = "Please enter a valid email address.";
$msgType = "danger";
} elseif (strlen($password) < 6) {
$message = "Password must be at least 6 characters long.";
$msgType = "danger";
} elseif ($password !== $confirm) {
$message = "Passwords do not match.";
$msgType = "danger";
} else {
// 2. Check for duplicate username or email using prepared statement
$checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$checkStmt->bind_param("ss", $username, $email);
$checkStmt->execute();
$checkStmt->store_result();


if ($checkStmt->num_rows > 0) {
$message = "Username or Email already registered. Please use another.";

$msgType = "warning";
} else {
// 3. Hash password using bcrypt and insert securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$insertStmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
$insertStmt->bind_param("ssss", $username, $email, $hashedPassword, $role);


if ($insertStmt->execute()) {
$message = "Registration successful! You can now log in.";
$msgType = "success";
} else {
$message = "Registration failed: " . $conn->error;
$msgType = "danger";
}
$insertStmt->close();
}
$checkStmt->close();
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Secure User Registration - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f4f6f8; padding: 25px; }
.reg-box { max-width: 500px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin: auto; }
h3 { color: #1e3a8a; }
</style>

</head>
<body>
<div class="reg-box">
<h3 class="mb-3 text-center">User Registration</h3>


<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $msgType; ?>"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>


<form method="POST" id="regForm" onsubmit="return validateFrontend();">
<div class="mb-3">
<label class="form-label">Username:</label>
<input type="text" name="username" id="username" class="form-control" placeholder="e.g. hemanshu025" required>
</div>
<div class="mb-3">
<label class="form-label">Email Address:</label>
<input type="email" name="email" id="email" class="form-control" placeholder="e.g. hemanshu@example.com" required>
</div>
<div class="mb-3">
<label class="form-label">Password:</label>
<input type="password" name="password" id="password" class="form-control" placeholder="At least 6 characters" required>
</div>
<div class="mb-3">
<label class="form-label">Confirm Password:</label>
<input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter password" required>
</div>
<div class="mb-3">
<label class="form-label">Account Role:</label>

<select name="role" class="form-select">
<option value="student">Student</option>
<option value="admin">Administrator</option>
</select>
</div>
<button type="submit" class="btn btn-primary w-100" style="background-color: #1e3a8a;">Register Account</button>
</form>
</div>


<!-- Matching Frontend JavaScript Validation -->
<script>
function validateFrontend() {
const user = document.getElementById('username').value.trim(); const email = document.getElementById('email').value.trim(); const pass = document.getElementById('password').value;
const confirm = document.getElementById('confirm_password').value;


if (user.length < 3) {
alert("Username must be at least 3 characters."); return false;
}
if (!email.includes('@') || !email.includes('.')) { alert("Please enter a valid email address."); return false;
}
if (pass.length < 6) {
alert("Password must be at least 6 characters."); return false;
}
if (pass !== confirm) { alert("Passwords do not match.");

return false;
}
return true;
}
</script>
</body>
</html>


