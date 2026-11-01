<?php


require_once 'db.php';


$tables = ['users', 'students', 'events', 'registrations'];
$counts = [];


foreach ($tables as $t) {
$res = $conn->query("SELECT COUNT(*) AS total FROM $t");
$counts[$t] = $res ? $res->fetch_assoc()['total'] : 0;
}
?>
<!DOCTYPE html>
<html>
<head>

<title>Database Connection Test - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4" style="background-color: #f4f6f8;">
<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 650px;">
<h3 style="color: #1e3a8a;" class="mb-3">MySQL Database Connection Status</h3>


<div class="alert alert-success">
<strong>&check; Connected Successfully!</strong><br> Server: <?php echo $conn->server_info; ?><br>
Database: <code>studenthub</code> (Port: <?php echo $port; ?>)
</div>


<h5>Table Verification:</h5>
<table class="table table-bordered table-striped">
<thead class="table-dark" style="background-color: #1e3a8a;">
<tr>
<th>Table Name</th>
<th>Status</th>
<th>Record Count</th>
</tr>
</thead>
<tbody>
<?php foreach ($counts as $tbl => $cnt): ?>
<tr>
<td><strong><?php echo $tbl; ?></strong></td>
<td class="text-success">&check; Active</td>
<td><?php echo $cnt; ?> records</td>
</tr>
<?php endforeach; ?>
</tbody>

</table>
</div>
</body>
</html>


