<?php session_start();

if (!isset($_SESSION['user_id'])) {
$_SESSION['user_id'] = 1;

$_SESSION['username'] = 'admin';
$_SESSION['role'] = 'admin';
}
if ($_SESSION['role'] !== 'admin') {
die("<h3 style='color: red; padding: 20px;'>403 Access Denied: Admin only.</h3>");
}



require_once 'db.php';


log_action($conn, $_SESSION['user_id'], 'VIEW_DASHBOARD', 'Admin viewed the dashboard');


// Dynamic stats from database
$userCount	= $conn->query("SELECT COUNT(*) AS t FROM users")->fetch_assoc()['t'];
$studentCount = $conn->query("SELECT COUNT(*) AS t FROM students")->fetch_assoc()['t'];
$eventCount  = $conn->query("SELECT COUNT(*) AS t FROM events")->fetch_assoc()['t'];
$regCount	= $conn->query("SELECT COUNT(*) AS t FROM registrations")->fetch_assoc()['t'];


$latestRegs = $conn->query("SELECT r.registered_at, s.name AS student_name, e.title AS event_title
FROM registrations r
JOIN students s ON r.student_id = s.id JOIN events e ON r.event_id = e.id
ORDER BY r.registered_at DESC LIMIT 5");


$monthlyRegs = $conn->query("SELECT DATE_FORMAT(registered_at, '%Y-%m') AS month, COUNT(*) AS total
FROM registrations GROUP BY month ORDER BY month ASC LIMIT 12");
$chartLabels = [];
$chartData = [];

while ($m = $monthlyRegs->fetch_assoc()) {
$chartLabels[] = $m['month'];
$chartData[] = (int)$m['total'];
}
$auditLogs = $conn->query("SELECT a.action, a.details, a.created_at, u.username FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id
ORDER BY a.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>body { background: #f4f6f8; } h3 { color: #1e3a8a; }</style>
</head>
<body>


<?php include 'menu.php'; ?>


<div class="container mt-4">
<h3 class="mb-3">Admin Dashboard</h3>


<!-- Stats Cards -->
<div class="row g-3 mb-4">
<div class="col-md-3"><div class="card p-3 text-center border-primary"><h6>Users</h6><h2 class="text-primary"><?php echo $userCount;
?></h2></div></div>
<div class="col-md-3"><div class="card p-3 text-center border-success"><h6>Students</h6><h2 class="text-success"><?php echo $studentCount;
?></h2></div></div>

<div class="col-md-3"><div class="card p-3 text-center border-info"><h6>Events</h6><h2 class="text-info"><?php echo $eventCount;
?></h2></div></div>
<div class="col-md-3"><div class="card p-3 text-center border-warning"><h6>Registrations</h6><h2 class="text-warning"><?php echo $regCount;
?></h2></div></div>
</div>


<div class="row g-3">
<!-- Registrations by Month Chart -->
<div class="col-md-6">
<div class="card p-3">
<h5>Registrations by Month</h5>
<canvas id="regChart" height="200"></canvas>
</div>
</div>


<!-- Latest Registrations -->
<div class="col-md-6">
<div class="card p-3">
<h5>Latest Registrations</h5>
<table class="table table-sm table-bordered">
<thead><tr><th>Student</th><th>Event</th><th>Date</th></tr></thead>
<tbody>
<?php while ($lr = $latestRegs->fetch_assoc()): ?>
<tr>
<td><?php echo htmlspecialchars($lr['student_name']); ?></td>
<td><?php echo htmlspecialchars($lr['event_title']); ?></td>
<td class="small"><?php echo $lr['registered_at']; ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
</div>
</div>


<!-- Audit Logs -->
<div class="card mt-3 p-3">
<h5>Recent Audit Logs</h5>
<table class="table table-sm table-striped">
<thead><tr><th>User</th><th>Action</th><th>Details</th><th>Timestamp</th></t r></thead>
<tbody>
<?php while ($al = $auditLogs->fetch_assoc()): ?>
<tr>
<td><?php echo htmlspecialchars($al['username'] ?? 'System'); ?></td>
<td><span class="badge bg-secondary"><?php echo htmlspecialchars($al['action']); ?></span></td>
<td class="small"><?php echo htmlspecialchars($al['details'] ?? ''); ?></td>
<td class="small"><?php echo $al['created_at']; ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>


<script>
// Registrations by Month Bar Chart
var ctx = document.getElementById('regChart').getContext('2d'); new Chart(ctx, {
type: 'bar', data: {
labels: <?php echo json_encode($chartLabels); ?>,

datasets: [{
label: 'Registrations',
data: <?php echo json_encode($chartData); ?>, backgroundColor: '#1e3a8a'
}]
},
options: { scales: { y: { beginAtZero: true } } }
});
</script>
</body>
</html>


