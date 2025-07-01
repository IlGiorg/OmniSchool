<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['teacher'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$studentId = $data['studentId'] ?? null;
$type = $data['type'] ?? null;
$reason = $data['reason'] ?? '';

try {
    $pdo = new PDO("mysql:host=sql109.infinityfree.com;dbname=if0_38817814_omnischool;charset=utf8mb4", "if0_38817814", "OMNISoftware25", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("INSERT INTO `grades` (`Student_ID`, `Grade`, `Assignment`, `Assessment_Date`) VALUES (?, ?, ?, ?);");
    $stmt->execute([$studentId, $type, $reason]);

    echo json_encode(["success" => true, "message" => "Grade added successfully."]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error adding Grade."]);
}
