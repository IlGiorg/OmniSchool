<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['admin'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$First_Name = $data['First_Name'] ?? null;
$Last_Name = $data['Last_Name'] ?? null;
$Username = $data['Username'] ?? '';
$Password = $data['Password'] ?? '';
$Academic_House = $data['Academic_House'] ?? '';
$DOB = $data['DOB'] ?? '';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=OSMAP;charset=utf8mb4", "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("INSERT INTO `Students` (`First_name`, `Last_Name`, `Username`, `Password`, `Academic_House`, `DOB`) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$First_Name, $Last_Name, $Username, $Password, $Academic_House, $DOB]);

    echo json_encode(["success" => true, "message" => "Student added successfully."]);

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(["success" => false, "message" => "Username not available."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding student."]);
    }
}
?>
