<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $updatedStudent = $data['updatedStudent'];

    $studentsFile = 'students_cred.json';
    $students = json_decode(file_get_contents($studentsFile), true);

    foreach ($students as &$student) {
        if ($student['username'] === $username) {
            $student = array_merge($student, $updatedStudent);
            break;
        }
    }

    file_put_contents($studentsFile, json_encode($students, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
