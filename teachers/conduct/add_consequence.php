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
    $pdo = new PDO("mysql:host=localhost;dbname=OSMAP;charset=utf8mb4", "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("INSERT INTO Consequences (Student_ID, Consequence_Type, Reason) VALUES (?, ?, ?)");
    $stmt->execute([$studentId, $type, $reason]);

    echo json_encode(["success" => true, "message" => "Consequence added successfully."]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error adding consequence."]);
}
