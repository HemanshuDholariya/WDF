<?php
require_once 'db.php';


// Handle CSV Export Extension
if (isset($_GET['export']) && $_GET['export'] === 'csv') { header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="students_export.csv"');
$out = fopen('php://output', 'w');

fputcsv($out, ['ID', 'Name', 'Email', 'Course', 'Year', 'Phone', 'Created At']);
$res = $conn->query("SELECT id, name, email, course, year, phone, created_at FROM students ORDER BY id ASC");
while ($row = $res->fetch_assoc()) { fputcsv($out, $row);
}
fclose($out); exit;
}


$search = trim($_GET['search'] ?? '');
$courseFilter = trim($_GET['course'] ?? '');


$query = "SELECT * FROM students WHERE 1=1";
$params = [];
$types = "";


if (!empty($search)) {
$query .= " AND (name LIKE ? OR email LIKE ? OR course LIKE ?)";
$searchWild = "%$search%";
$params[] = $searchWild;
$params[] = $searchWild;
$params[] = $searchWild;
$types .= "sss";
}


if (!empty($courseFilter)) {
$query .= " AND course = ?";
$params[] = $courseFilter;
$types .= "s";
}

$query .= " ORDER BY id DESC";
$stmt = $conn->prepare($query); if (!empty($params)) {
$stmt->bind_param($types, ...$params);
}
$stmt->execute();
$students = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Management - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f4f6f8; padding: 25px; }
.box { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
h3 { color: #1e3a8a; }
</style>
</head>
<body>
<div class="container box">
<div class="d-flex justify-content-between align-items-center mb-3">
<h3>Student Records Management</h3>
<div>
<a href="add_student.php" class="btn btn-primary btn-sm" style="background-color: #1e3a8a;">+ Add New Student</a>
<a href="students.php?export=csv" class="btn btn-success btn-sm">Export CSV</a>
</div>
</div>


<?php if (isset($_GET['msg'])): ?>

<div class="alert alert-success py-2 small"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>


<!-- Search and Filter Form -->
<form method="GET" class="row g-2 mb-3">
<div class="col-md-5">
<input type="text" name="search" class="form-control" placeholder="Search by name, email or course..." value="<?php echo htmlspecialchars($search); ?>">
</div>
<div class="col-md-4">
<select name="course" class="form-select">
<option value="">All Courses</option>
<option value="Computer Engineering" <?php if ($courseFilter === 'Computer Engineering') echo 'selected'; ?>>Computer Engineering</option>
<option value="Information Technology" <?php if ($courseFilter === 'Information Technology') echo 'selected'; ?>>Information Technology</option>
<option value="Electronics" <?php if ($courseFilter === 'Electronics') echo 'selected'; ?>>Electronics</option>
<option value="Mechanical" <?php if ($courseFilter === 'Mechanical') echo 'selected'; ?>>Mechanical</option>
</select>
</div>
<div class="col-md-3">
<button type="submit" class="btn btn-outline-primary w-100">Filter Records</button>
</div>
</form>


<!-- Students Table -->
<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
<thead class="table-dark" style="background-color: #1e3a8a;">
<tr>

<th>ID</th>
<th>Full Name</th>
<th>Email Address</th>
<th>Course</th>
<th>Year</th>
<th>Phone</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php if ($students->num_rows === 0): ?>
<tr><td colspan="7" class="text-center text-muted">No matching student records found.</td></tr>
<?php else: ?>
<?php while ($s = $students->fetch_assoc()): ?>
<tr>
<td><?php echo $s['id']; ?></td>
<td class="fw-semibold"><?php echo htmlspecialchars($s['name']); ?></td>
<td><?php echo htmlspecialchars($s['email']); ?></td>
<td><?php echo htmlspecialchars($s['course']); ?></td>
<td>Year <?php echo $s['year']; ?></td>
<td><?php echo htmlspecialchars($s['phone'] ?? 'N/A'); ?></td>
<td>
<a href="edit_student.php?id=<?php echo $s['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="delete_student.php?id=<?php echo $s['id']; ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure you want to delete this student record?');">Delete</a>
</td>
</tr>
<?php endwhile; ?>
<?php endif; ?>

</tbody>
</table>
</div>
</div>
</body>
</html>


