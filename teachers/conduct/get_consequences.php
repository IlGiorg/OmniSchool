<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['teacher'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=127.0.0.1:3307;dbname=omnischool;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->query("SELECT * FROM conduct ORDER BY Date_Assigned DESC");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "records" => $records]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching data: " . $e->getMessage()
    ]);
}

