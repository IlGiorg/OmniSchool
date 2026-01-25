<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=127.0.0.1:3307;dbname=omnischool;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $username = $_SESSION['username'];

    // Get all children
    $stmt = $pdo->prepare("SELECT Child1, Child2, Child3 FROM parents WHERE Username = ?");
    $stmt->execute([$username]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(["success" => false, "message" => "Children not found"]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "children" => [
            $student["Child1"],
            $student["Child2"],
            $student["Child3"]
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
