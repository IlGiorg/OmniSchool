<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=OSMAP;charset=utf8mb4", "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $username = $_SESSION['username'];

    // Get student
    $stmt = $pdo->prepare("SELECT ID, First_name FROM Students WHERE Username = ?");
    $stmt->execute([$username]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(["success" => false, "message" => "Student not found"]);
        exit;
    }

    // Get consequences
    $stmt = $pdo->prepare("SELECT Consequence_Type, Reason, Date_Assigned FROM Consequences WHERE Student_ID = ?");
    $stmt->execute([$student['ID']]);
    $consequences = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "name" => $student["First_name"],
        "consequences" => $consequences
    ]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
