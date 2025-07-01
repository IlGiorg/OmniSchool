<?php
header('Content-Type: application/json');

// Path to the students.json file
$studentsFile = 'students.json';

// Read the input data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validate the input data
if (!isset($data['id'], $data['username'], $data['name'])) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit;
}

// Load existing students
if (!file_exists($studentsFile)) {
    echo json_encode(["success" => false, "message" => "Students file not found."]);
    exit;
}

$students = json_decode(file_get_contents($studentsFile), true);

// Find and update the student
$updated = false;
foreach ($students['students'] as &$student) {
    if ($student['id'] === $data['id']) {
        $student['username'] = $data['username'];
        $student['name'] = $data['name'];

        // Update password if provided
        if (!empty($data['password'])) {
            $student['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $updated = true;
        break;
    }
}

// Save changes if the student was updated
if ($updated && file_put_contents($studentsFile, json_encode($students, JSON_PRETTY_PRINT))) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update student."]);
}
?>
