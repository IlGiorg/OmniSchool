<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=sql109.infinityfree.com;dbname=if0_38817814_omnischool;charset=utf8mb4", "if0_38817814", "OMNISoftware25", [
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

    // Get grades
    $stmt = $pdo->prepare("SELECT Grade, Assignment, Assessment_Date FROM grades WHERE Student_ID = ?");
    $stmt->execute([$student['ID']]);
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "name" => $student["First_name"],
        "grades" => $grades
    ]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
