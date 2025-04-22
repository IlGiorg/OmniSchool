<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $newClass = json_decode($input, true);

    if (isset($newClass['className']) && isset($newClass['students'])) {
        $filePath = 'classes.json';

        // Read existing classes
        $classes = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

        // Add or update the class
        $classes[$newClass['className']] = $newClass['students'];

        // Save back to JSON
        if (file_put_contents($filePath, json_encode($classes, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save class.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
