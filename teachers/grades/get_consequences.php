<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['teacher'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

require_once '../db/db.php';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->query("SELECT * FROM Grades ORDER BY Student_ID ASC");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "records" => $records]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error fetching data"]);
}
