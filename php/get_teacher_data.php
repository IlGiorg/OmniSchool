<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['teacher'])) {
    echo json_encode(["success" => false]);
    exit;
}

echo json_encode([
    "success" => true,
    "username" => $_SESSION['teacher']
]);
