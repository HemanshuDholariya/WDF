<?php
require_once 'auth_check.php';


// Enforce student role access check_auth('student');
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Dashboard - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4" style="background-color: #f4f6f8;">
<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
<h3 style="color: #1e3a8a;">Student Portal Dashboard</h3>
<div>
<span class="me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>

<a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
</div>
</div>


<div class="alert alert-info">
&check; Role: <strong>Student</strong> | Session Active | Inactivity Timer: 15 mins.
</div>


<div class="p-3 border rounded bg-light">
<h5>Quick Student Links:</h5>
<ul>
<li>View enrolled events and workshops</li>
<li>Download registration receipts and event schedules</li>
<li>Update personal profile details</li>
</ul>
</div>
</div>
</body>
</html>


