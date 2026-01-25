<?php
header("Content-Type: application/json");
require_once '../db/db.php';

try {
    $q = $_GET['q'] ?? '';
    $q_wildcard = "%{$q}%";
    
    $stmt = $conn->prepare("SELECT ID, First_name, Last_Name FROM students WHERE First_name LIKE ? OR Last_Name LIKE ?");
    $stmt->bind_param("ss", $q_wildcard, $q_wildcard);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
