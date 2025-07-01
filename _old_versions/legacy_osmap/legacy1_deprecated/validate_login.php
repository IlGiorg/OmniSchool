<?php
// validate_login.php
header('Content-Type: application/json');

// Get the raw POST data
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if inputData exists
if ($inputData) {
    // Path to the JSON file containing student credentials
    $jsonFile = 'students_cred.json';

    // Check if the JSON file exists and read it
    if (file_exists($jsonFile)) {
        $studentsData = json_decode(file_get_contents($jsonFile), true);

        // Validate if the students data is correctly formatted
        if (isset($studentsData['students']) && is_array($studentsData['students'])) {
            $valid = false;
            // Loop through the students' data to find a match
            foreach ($studentsData['students'] as $student) {
                // Check if the username matches
                if ($student['username'] === $inputData['username']) {
                    // If password is hashed, use password_verify() to check
                    if (password_verify($inputData['password'], $student['password']) || $inputData['password'] === $student['password']) {
                        $valid = true;
                        break;
                    }
                }
            }

            // Return the response based on validation
            if ($valid) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid student data format']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Student credentials file not found']);
    }
} else {
    // Error handling if input is not JSON
    echo json_encode(['success' => false, 'error' => 'Invalid input format']);
}
?>
