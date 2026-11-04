<?php
$role = $_SESSION['role'] ?? 'guest';
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1e3a8a;">
<div class="container-fluid">
<a class="navbar-brand fw-bold" href="#">StudentHub</a>
<div class="navbar-nav">
<?php if ($role === 'admin'): ?>
<a class="nav-link" href="dashboard.php">Dashboard</a>
<a class="nav-link" href="#">Manage Students</a>
<a class="nav-link" href="#">Manage Events</a>
<a class="nav-link" href="#">Audit Logs</a>
<a class="nav-link" href="#">Users</a>
<?php elseif ($role === 'student'): ?>
<a class="nav-link" href="#">My Profile</a>
<a class="nav-link" href="#">My Events</a>
<?php else: ?>
<a class="nav-link" href="#">Login</a>

<?php endif; ?>
</div>
<span class="navbar-text text-light small">
<?php if (isset($_SESSION['username'])): ?>
Logged in: <strong><?php echo htmlspecialchars($_SESSION['username']);
?></strong>
(<?php echo ucfirst($role); ?>)
<?php endif; ?>
</span>
</div>
</nav>


