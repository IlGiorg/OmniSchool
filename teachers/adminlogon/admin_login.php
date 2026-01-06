<?php
session_start();
header('Content-Type: application/json');

// Read raw input and decode JSON
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// DB connection
$host = 'sql109.infinityfree.com';
$db = 'if0_38817814_omnischool';
$user = 'if0_38817814';
$pass = 'OMNISoftware25'; // Change if different in MAMP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Check credentials in the database
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE Username = ? AND Password = ?");
    $stmt->execute([$username, $password]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the student is found, set session and return success
    if ($student) {
        $_SESSION['admin'] = $username; // ✅ Set session
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error 603: Invalid username or password."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
