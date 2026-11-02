<?php
require_once 'auth_check.php'; require_once 'db.php';

check_auth('admin');


$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$studentCount = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$eventCount = $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - StudentHub</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4" style="background-color: #f4f6f8;">
<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 800px;">
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
<h3 style="color: #1e3a8a;">Admin Dashboard</h3>
<div>
<span class="me-3">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> (Admin)</span>
<a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
</div>
</div>


<div class="alert alert-success">
&check; Access Granted: Admin Role verified via <code>auth_check.php</code>.
</div>


<div class="row g-3 mb-4">
<div class="col-md-4">
<div class="card p-3 text-center border-primary">
<h5>Total Users</h5>
<h2 class="text-primary"><?php echo $userCount; ?></h2>
</div>
</div>
<div class="col-md-4">
<div class="card p-3 text-center border-success">
<h5>Total Students</h5>
<h2 class="text-success"><?php echo $studentCount; ?></h2>
</div>
</div>
<div class="col-md-4">

<div class="card p-3 text-center border-info">
<h5>Total Events</h5>
<h2 class="text-info"><?php echo $eventCount; ?></h2>
</div>
</div>
</div>
</div>
</body>
</html>


