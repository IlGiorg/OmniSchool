<?php
session_start();
header('Content-Type: application/json');

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// DB config
$host = 'sql308.infinityfree.com';
$db = 'if0_38801093_XXX'; // Replace XXX
$user = 'if0_38801093';
$pass = 'OSMAPFreeForeve';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("SELECT * FROM Students WHERE Username = ? AND Password = ?");
    $stmt->execute([$username, $password]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        $_SESSION['username'] = $username;
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid username or password."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
