
<?php
header('Content-Type: application/json');

// MySQL config
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = 'password'; // as per instructions
$db_name = 'login_signup';

// Get POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$name || !$email || !$password) {
	echo json_encode(['success' => false, 'message' => 'All fields are required.']);
	exit;
}

// Connect to MySQL
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
	echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
	exit;
}

// Check if email already exists
$stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ?');
if (!$stmt) {
	echo json_encode(['success' => false, 'message' => 'Query error.']);
	exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
	echo json_encode(['success' => false, 'message' => 'Email already registered.']);
	exit;
}
$stmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $mysqli->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
if (!$stmt) {
	echo json_encode(['success' => false, 'message' => 'Insert error.']);
	exit;
}
$stmt->bind_param('sss', $name, $email, $hashed_password);
if ($stmt->execute()) {
	echo json_encode(['success' => true]);
} else {
	echo json_encode(['success' => false, 'message' => 'Registration failed.']);
}
$stmt->close();
$mysqli->close();
exit;
