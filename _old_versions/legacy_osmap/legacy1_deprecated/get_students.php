<?php
header('Content-Type: application/json');

// Path to the students.json file
$studentsFile = 'students_cred.json';

// Check if the file exists
if (!file_exists($studentsFile)) {
    echo json_encode(["success" => false, "message" => "Students file not found."]);
    exit;
}

// Read and return the student data
$data = file_get_contents($studentsFile);
echo $data;
?>
