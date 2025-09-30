
<?php
header('Content-Type: application/json');

// MySQL config
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = 'password'; // as per instructions
$db_name = 'login_signup';

// Redis config
$redis_host = '127.0.0.1';
$redis_port = 6379;

// Get POST data

$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$fetch_profile = isset($_POST['fetch_profile']) ? $_POST['fetch_profile'] : false;


if ($fetch_profile && $email) {
	// Fetch full user profile
	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
	if ($mysqli->connect_error) {
		echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
		exit;
	}
	$stmt = $mysqli->prepare('SELECT name, email, age, dob, contact FROM users WHERE email = ?');
	if (!$stmt) {
		echo json_encode(['success' => false, 'message' => 'Query error.']);
		exit;
	}
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0) {
		echo json_encode(['success' => false, 'message' => 'User not found.']);
		exit;
	}
	$stmt->bind_result($name, $email, $age, $dob, $contact);
	$stmt->fetch();
	echo json_encode(['success' => true, 'profile' => [
		'name' => $name,
		'email' => $email,
		'age' => $age,
		'dob' => $dob,
		'contact' => $contact
	]]);
	exit;
}

if (!$email || !$password) {
	echo json_encode(['success' => false, 'message' => 'Email and password required.']);
	exit;
}

// Connect to MySQL
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
	echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
	exit;
}

// Prepared statement to fetch user
$stmt = $mysqli->prepare('SELECT id, password FROM users WHERE email = ?');
if (!$stmt) {
	echo json_encode(['success' => false, 'message' => 'Query error.']);
	exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
	echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
	exit;
}

$stmt->bind_result($user_id, $hashed_password);
$stmt->fetch();

// Verify password (assuming password is hashed)
if (!password_verify($password, $hashed_password)) {
	echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
	exit;
}

// Generate session ID
$session_id = bin2hex(random_bytes(16));

// Store session in Redis
try {
	$redis = new Redis();
	$redis->connect($redis_host, $redis_port);
	$redis->set('session:' . $session_id, $user_id, 3600); // 1 hour expiry
} catch (Exception $e) {
	echo json_encode(['success' => false, 'message' => 'Redis error.']);
	exit;
}

echo json_encode(['success' => true, 'session_id' => $session_id]);
exit;
