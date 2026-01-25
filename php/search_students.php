<?php
header("Content-Type: application/json");
$pdo = new PDO("mysql:host=127.0.0.1:3307;dbname=omnischool;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$q = $_GET['q'] ?? '';
$stmt = $pdo->prepare("SELECT ID, First_name, Last_Name FROM students WHERE First_name LIKE ? OR Last_Name LIKE ?");
$stmt->execute(["%$q%", "%$q%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
