<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

require_once '../db/db.php';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $username = $_SESSION['username'];

    // Get student
    $stmt = $pdo->prepare("SELECT ID, First_name FROM students WHERE Username = ?");
    $stmt->execute([$username]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(["success" => false, "message" => "Student not found"]);
        exit;
    }

    // Get conduct records
    $stmt = $pdo->prepare("SELECT Consequence_Type, Reason, Date_Assigned FROM conduct WHERE Student_ID = ?");
    $stmt->execute([$student['ID']]);
    $conduct = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "name" => $student["First_name"],
        "conduct" => $conduct
    ]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
