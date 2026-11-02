<?php
header('Content-Type: application/json'); require_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];


if ($method === 'GET') {
$search = trim($_GET['search'] ?? ''); if (!empty($search)) {
$stmt = $conn->prepare("SELECT * FROM events WHERE title LIKE ? ORDER BY event_date ASC");
$like = "%$search%";
$stmt->bind_param("s", $like);
} else {
$stmt = $conn->prepare("SELECT * FROM events ORDER BY event_date ASC");
}
$stmt->execute();
$result = $stmt->get_result();
$events = [];
while ($row = $result->fetch_assoc()) {
$events[] = $row;
}
$stmt->close(); http_response_code(200);
echo json_encode(["status" => "success", "data" => $events]); exit;
}


if ($method === 'POST') {
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';


// ADD

if ($action === 'add') {
$title	= trim($input['title'] ?? '');
$description = trim($input['description'] ?? '');
$event_date = trim($input['event_date'] ?? '');
$category  = trim($input['category'] ?? 'Workshop');


if (empty($title) || empty($event_date)) { http_response_code(400);
echo json_encode(["status" => "error", "message" => "Title and date are required."]); exit;
}


$stmt = $conn->prepare("INSERT INTO events (title, description, event_date, category, status) VALUES (?, ?, ?, ?, 'Open')");
$stmt->bind_param("ssss", $title, $description, $event_date, $category); if ($stmt->execute()) {
http_response_code(201);
echo json_encode(["status" => "success", "message" => "Event added.", "id" =>
$conn->insert_id]);
} else {
http_response_code(500);
echo json_encode(["status" => "error", "message" => "Insert failed."]);
}
$stmt->close(); exit;
}


if ($action === 'update') {
$id	= (int)($input['id'] ?? 0);
$title	= trim($input['title'] ?? '');

$event_date = trim($input['event_date'] ?? '');
$category  = trim($input['category'] ?? '');


if ($id <= 0 || empty($title)) { http_response_code(400);
echo json_encode(["status" => "error", "message" => "Valid ID and title are required."]);
exit;
}


$stmt = $conn->prepare("UPDATE events SET title=?, event_date=?, category=?
WHERE id=?");
$stmt->bind_param("sssi", $title, $event_date, $category, $id); if ($stmt->execute()) {
http_response_code(200);
echo json_encode(["status" => "success", "message" => "Event updated."]);
} else {
http_response_code(500);
echo json_encode(["status" => "error", "message" => "Update failed."]);
}
$stmt->close(); exit;
}


// DELETE
if ($action === 'delete') {
$id = (int)($input['id'] ?? 0); if ($id <= 0) {
http_response_code(400);
echo json_encode(["status" => "error", "message" => "Valid ID is required."]);

exit;
}


$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id); if ($stmt->execute()) {
http_response_code(200);
echo json_encode(["status" => "success", "message" => "Event deleted."]);
} else {
http_response_code(500);
echo json_encode(["status" => "error", "message" => "Delete failed."]);
}
$stmt->close(); exit;
}


http_response_code(400);
echo json_encode(["status" => "error", "message" => "Unknown action."]); exit;
}


http_response_code(405);
echo json_encode(["status" => "error", "message" => "Method not allowed."]);




