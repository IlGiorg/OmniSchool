<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $newStudent = json_decode($input, true);

    if (isset($newStudent['username']) && isset($newStudent['password'])) {
        $filePath = 'students_cred.json';

        // Read existing students
        $students = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

        // Add new student
        $students[] = $newStudent;

        // Save back to JSON
        if (file_put_contents($filePath, json_encode($students, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save student.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
