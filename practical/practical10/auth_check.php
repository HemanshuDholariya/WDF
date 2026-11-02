<?php
if (session_status() === PHP_SESSION_NONE) { session_start();
}


// 1. Session Inactivity Timeout (15 minutes = 900 seconds)
$timeout_duration = 900;
if (isset($_SESSION['last_activity'])) {
if ((time() - $_SESSION['last_activity']) > $timeout_duration) { session_unset();
session_destroy();
header("Location: login.php?msg=Session+expired+due+to+inactivity"); exit;
}
}
$_SESSION['last_activity'] = time(); // Update activity timestamp


// 2. Role-Based Access Control (RBAC) & Unauthorized Access Blocker
function check_auth($required_role = null) {
if (!isset($_SESSION['user_id'])) {
header("Location: login.php?msg=Please+login+to+access+dashboard"); exit;

}


if ($required_role && $_SESSION['role'] !== $required_role) {
// Block unauthorized access to role-protected pages
die("<div style='font-family: Arial; padding: 20px; color: red;'>
<h3>403 Access Denied</h3>
<p>You do not have permission to access this page.</p>
<a href='logout.php'>Logout</a> | <a href='javascript:history.back()'>Go Back</a>
</div>");
}
}
?>


