<?php
header('Content-Type: application/json');

// --- DB Connection ---
$host = "sql109.infinityfree.com";
$dbname = "if0_38817814_omnischool";
$user = "if0_38817814";
$pass = "OMNISoftware25";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connect failed: ' . $conn->connect_error]);
    exit;
}

// Read JSON POST data
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid or empty JSON input']);
    exit;
}

// Validate input fields
$firstName = trim($data['First_Name'] ?? '');
$lastName = trim($data['Last_Name'] ?? '');
$username = trim($data['Username'] ?? '');
$passwordRaw = trim($data['Password'] ?? '');
$house = trim($data['Academic_House'] ?? '');
$dob = trim($data['DOB'] ?? '');

if (!$firstName || !$lastName || !$username || !$passwordRaw || !$house || !$dob) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Escape strings for safe SQL
$firstName = $conn->real_escape_string($firstName);
$lastName = $conn->real_escape_string($lastName);
$username = $conn->real_escape_string($username);
$house = $conn->real_escape_string($house);
$dob = $conn->real_escape_string($dob);

// Check username uniqueness
$res = $conn->query("SELECT ID FROM students WHERE Username='$username'");
if ($res === false) {
    echo json_encode(['success' => false, 'message' => 'DB query failed: ' . $conn->error]);
    exit;
}
if ($res->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username not available']);
    exit;
}

// Hash password
$hashedPassword = password_hash($passwordRaw, PASSWORD_DEFAULT);
if ($hashedPassword === false) {
    echo json_encode(['success' => false, 'message' => 'Password hashing failed']);
    exit;
}

// Insert new student
$sql = "INSERT INTO students (First_name, Last_Name, Username, Password, Academic_House, DOB) VALUES ('$firstName', '$lastName', '$username', '$hashedPassword', '$house', '$dob')";
if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $conn->error]);
}

$conn->close();
?>
