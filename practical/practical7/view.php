<?php


$csvFile = __DIR__ . '/data/registrations.csv';
$records = [];

if (file_exists($csvFile)) {
    $file = fopen($csvFile, 'r');
    if ($file) {
        // Shared lock for reading safely
        if (flock($file, LOCK_SH)) {
            while (($row = fgetcsv($file)) !== false) {
                if (!empty($row)) {
                    $records[] = $row;
                }
            }
            flock($file, LOCK_UN);
        }
        fclose($file);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Students - StudentHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f8; padding: 30px; font-family: Arial, sans-serif; }
        .container { max-width: 900px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { color: #1e3a8a; }
        th { background-color: #1e3a8a !important; color: white !important; }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Registered Students (CSV Storage)</h2>
        <a href="form.html" class="btn btn-primary" style="background-color: #1e3a8a;">+ New Registration</a>
    </div>

    <?php if (empty($records)): ?>
        <div class="alert alert-info">No registrations recorded yet in <code>data/registrations.csv</code>. <a href="form.html">Add the first registration</a>.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Timestamp</th>
                        <th>Enrollment</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $index => $r): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($r[0] ?? ''); ?></td>
                        <td><strong><?php echo htmlspecialchars($r[1] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($r[2] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($r[3] ?? ''); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($r[4] ?? ''); ?></span></td>
                        <td><?php echo htmlspecialchars($r[5] ?? ''); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="text-muted small">Total Records: <?php echo count($records); ?> | File lock (flock LOCK_SH) verified.</p>
    <?php endif; ?>
</div>
</body>
</html>
