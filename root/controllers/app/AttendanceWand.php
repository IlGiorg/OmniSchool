<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
// 🧙 Get all classes
function getAllClasses() {
    global $pdo;

    $stmt = $pdo->query("SELECT ClassID, Year, Form FROM classes ORDER BY Year, Form");
    return $stmt->fetchAll();
}

// 🧙 Get students in class
function getStudentsByClass($classID) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT ID, First_name, Last_Name FROM students WHERE ClassID = ?");
    $stmt->execute([$classID]);

    return $stmt->fetchAll();
}

// 🪄 Submit attendance
function submitAttendance($classID, $statuses, $teacherUsername) {
    global $pdo;

    // Get teacher ID
    $stmt = $pdo->prepare("SELECT TeachID FROM teachers WHERE Username = ?");
    $stmt->execute([$teacherUsername]);
    $teacher = $stmt->fetch();

    if (!$teacher) {
        return "❌ Invalid teacher";
    }

    $teacherID = $teacher['TeachID'];

    $insert = $pdo->prepare(
        "INSERT INTO attendance (StudentID, Status, Date, ClassID, Recorded_By)
         VALUES (?, ?, CURDATE(), ?, ?)"
    );

    foreach ($statuses as $studentID => $status) {
        $insert->execute([
            $studentID,
            $status,
            $classID,
            $teacherID
        ]);
    }

    return "✅ Attendance submitted successfully";
}