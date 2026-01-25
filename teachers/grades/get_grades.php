<?php
session_start();
header("Content-Type: application/json");

// Ensure the teacher is logged in
if (!isset($_SESSION['teacher'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

try {
    // Database connection
    $pdo = new PDO("mysql:host=127.0.0.1:3307;dbname=omnischool;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Get all grades with student names
    $stmt = $pdo->query("SELECT grades.ID, grades.Student_ID, grades.Grade, grades.Assignment, grades.Assessment_Date, students.First_name, students.Last_name
                         FROM grades
                         INNER JOIN students ON grades.Student_ID = students.ID
                         ORDER BY grades.Student_ID ASC");

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "records" => $records]);

} catch (PDOException $e) {
    // Handle database connection errors
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    // Handle other errors
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
