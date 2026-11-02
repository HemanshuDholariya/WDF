<?php
require_once 'db.php';
$category = trim($_GET['category'] ?? '');
$status  = trim($_GET['status'] ?? '');


$query = "SELECT * FROM events WHERE 1=1";
$params = [];
$types = "";

if (!empty($category)) {
$query .= " AND category = ?";
$params[] = $category;
$types .= "s";
}


if (!empty($status)) {
$query .= " AND status = ?";
$params[] = $status;
$types .= "s";
}


$query .= " ORDER BY event_date ASC";
$stmt = $conn->prepare($query); if (!empty($params)) {
$stmt->bind_param($types, ...$params);
}
$stmt->execute();
$events = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Event Management - StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f4f6f8; padding: 25px; }
.box { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
h3 { color: #1e3a8a; }
.poster-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }

</style>
</head>
<body>
<div class="container box">
<div class="d-flex justify-content-between align-items-center mb-3">
<h3>Campus Events Management</h3>
<a href="add_event.php" class="btn btn-primary" style="background-color: #1e3a8a;">+ Add New Event</a>
</div>


<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-success py-2 small"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>


<!-- Filter Form -->
<form method="GET" class="row g-2 mb-3">
<div class="col-md-5">
<select name="category" class="form-select">
<option value="">All Categories</option>
<option value="Workshop" <?php if ($category === 'Workshop') echo 'selected';
?>>Workshop</option>
<option value="Seminar" <?php if ($category === 'Seminar') echo 'selected';
?>>Seminar</option>
<option value="Cultural" <?php if ($category === 'Cultural') echo 'selected';
?>>Cultural</option>
<option value="Sports" <?php if ($category === 'Sports') echo 'selected';
?>>Sports</option>
<option value="Technical" <?php if ($category === 'Technical') echo 'selected';
?>>Technical</option>
</select>
</div>
<div class="col-md-4">
<select name="status" class="form-select">

<option value="">All Statuses</option>
<option value="Open" <?php if ($status === 'Open') echo 'selected';
?>>Open</option>
<option value="Closed" <?php if ($status === 'Closed') echo 'selected';
?>>Closed</option>
</select>
</div>
<div class="col-md-3">
<button type="submit" class="btn btn-outline-primary w-100">Filter Events</button>
</div>
</form>


<!-- Events Table -->
<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
<thead class="table-dark" style="background-color: #1e3a8a;">
<tr>
<th>Poster</th>
<th>Event Title</th>
<th>Date</th>
<th>Category</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php if ($events->num_rows === 0): ?>
<tr><td colspan="6" class="text-center text-muted">No events found matching your filter criteria.</td></tr>
<?php else: ?>
<?php while ($ev = $events->fetch_assoc()): ?>
<tr>

<td class="text-center">
<?php if (!empty($ev['poster']) && file_exists(  DIR	. '/uploads/' .
$ev['poster'])): ?>
<img src="uploads/<?php echo htmlspecialchars($ev['poster']); ?>"
class="poster-thumb" alt="Poster">
<?php else: ?>
<span class="badge bg-secondary">No Poster</span>
<?php endif; ?>
</td>
<td>
<strong><?php echo htmlspecialchars($ev['title']); ?></strong><br>
<small class="text-muted"><?php echo htmlspecialchars($ev['description'] ?? ''); ?></small>
</td>
<td><?php echo htmlspecialchars($ev['event_date']); ?></td>
<td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($ev['category']); ?></span></td>
<td>
<?php if ($ev['status'] === 'Open'): ?>
<span class="badge bg-success">Open</span>
<?php else: ?>
<span class="badge bg-danger">Closed</span>
<?php endif; ?>
</td>
<td>
<a href="edit_event.php?id=<?php echo $ev['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="delete_event.php?id=<?php echo $ev['id']; ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Delete this event?');">Delete</a>
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


