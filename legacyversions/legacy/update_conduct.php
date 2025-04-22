<?php
header('Content-Type: application/json');

// Path to the conduct log
$conductLogFile = 'conduct_log.json';

// Get the incoming data
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Validate input
if (!isset($data['studentid'], $data['level'], $data['comment'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

$studentid = $data['studentid'];
$level = $data['level'];
$comment = $data['comment'];

// Create the log entry
$logEntry = [
    'studentid' => $studentid,
    'level' => $level,
    'comment' => $comment,
    'timestamp' => date('Y-m-d H:i:s')
];

// Read existing log entries
if (!file_exists($conductLogFile)) {
    $logEntries = [];
} else {
    $logEntries = json_decode(file_get_contents($conductLogFile), true) ?? [];
}

// Append the new log entry
$logEntries[] = $logEntry;

// Save the updated log back to the file
if (file_put_contents($conductLogFile, json_encode($logEntries, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Conduct level updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save conduct log.']);
}
?>
