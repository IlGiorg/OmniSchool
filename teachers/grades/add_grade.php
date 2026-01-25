<?php
session_start();
header("Content-Type: application/json");

// Ensure the teacher is logged in
if (!isset($_SESSION['teacher'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

// Get the data from the frontend (JSON)
$data = json_decode(file_get_contents("php://input"), true);

$studentId = $data['studentId'] ?? null;  // Student ID
$type = $data['type'] ?? null;  // Grade Type (HP, L0, etc.)
$reason = $data['reason'] ?? '';  // Assignment reason
$assessmentDate = $data['Assessment_Date'] ?? '';  // Assessment date

try {
    // Set up the database connection
    $pdo = new PDO("mysql:host=127.0.0.1:3307;dbname=omnischool;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Prepare the INSERT query for grades table
    $stmt = $pdo->prepare("INSERT INTO `grades`(`Student_ID`, `Grade`, `Assignment`, `Assessment_Date`) VALUES (?, ?, ?, ?)");
    
    // Execute the query
    $stmt->execute([$studentId, $type, $reason, $assessmentDate]);

    // Return success response
    echo json_encode(["success" => true, "message" => "Grade added successfully."]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error adding grade: " . $e->getMessage()]);
}
