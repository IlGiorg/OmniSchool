<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $newTeacher = json_decode($input, true);

    if (isset($newTeacher['username']) && isset($newTeacher['password'])) {
        $filePath = 'teachers_cred.json';

        // Read existing teachers
        $teachers = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

        // Add new teacher
        $teachers[] = $newTeacher;

        // Save back to JSON
        if (file_put_contents($filePath, json_encode($teachers, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save teacher.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
