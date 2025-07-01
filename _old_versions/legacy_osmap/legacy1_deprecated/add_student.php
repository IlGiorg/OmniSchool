<?php
header('Content-Type: application/json');

// Path to the students.json file
$studentsFile = 'students_cred.json';

// Read the input data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validate the input data
if (!isset($data['username'], $data['password'], $data['name'])) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit;
}

// Load existing students
$students = file_exists($studentsFile) ? json_decode(file_get_contents($studentsFile), true) : ["students" => []];

// Check for duplicate username
foreach ($students['students'] as $student) {
    if ($student['username'] === $data['username']) {
        echo json_encode(["success" => false, "message" => "Username already exists."]);
        exit;
    }
}

// Create a new student entry
$newStudent = [
    "id" => uniqid(),
    "username" => $data['username'],
    "password" => password_hash($data['password'], algo: PASSWORD_DEFAULT),
    "name" => $data['name']
];

// Add the new student to the list
$students['students'][] = $newStudent;

// Save the updated students data
if (file_put_contents($studentsFile, json_encode($students, JSON_PRETTY_PRINT))) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save student."]);
}
?>
