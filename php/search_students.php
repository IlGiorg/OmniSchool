<?php
header("Content-Type: application/json");
$pdo = new PDO("mysql:host=localhost;dbname=OSMAP;charset=utf8mb4", "root", "root", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$q = $_GET['q'] ?? '';
$stmt = $pdo->prepare("SELECT ID, First_name, Last_Name FROM Students WHERE First_name LIKE ? OR Last_Name LIKE ?");
$stmt->execute(["%$q%", "%$q%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
