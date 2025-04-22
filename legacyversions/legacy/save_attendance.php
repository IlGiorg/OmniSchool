<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $class = $data['class']; // Get the class
    $attendanceRecords = $data['attendanceRecords']; // Array of student attendance

    $file = 'attendance.json';

    // Load existing data
    $existingData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    // Append new records
    foreach ($attendanceRecords as $record) {
        $record['class'] = $class; // Add class to each record
        $existingData[] = $record;
    }

    // Save back to file
    file_put_contents($file, json_encode($existingData, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true]);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
}
