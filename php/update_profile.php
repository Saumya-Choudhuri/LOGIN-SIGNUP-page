<?php
header('Content-Type: application/json');

// MySQL config
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = 'password';
$db_name = 'login_signup';

// Get POST data

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$age = isset($_POST['age']) ? intval($_POST['age']) : null;
$dob = isset($_POST['dob']) ? trim($_POST['dob']) : '';
$contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';

if (!$email || !$name) {
    echo json_encode(['success' => false, 'message' => 'Email and name required.']);
    exit;
}

// Connect to MySQL
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Update user profile
$stmt = $mysqli->prepare('UPDATE users SET name = ?, age = ?, dob = ?, contact = ? WHERE email = ?');
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Query error.']);
    exit;
}
$stmt->bind_param('sisss', $name, $age, $dob, $contact, $email);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed.']);
}
$stmt->close();
$mysqli->close();
exit;
