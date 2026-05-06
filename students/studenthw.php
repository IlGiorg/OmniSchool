<?php
require_once '../db/db.php';
 $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}


$username = $_GET['username']; 


$stmt = $conn->prepare("
    SELECT Class_ID 
    FROM students 
    WHERE Username = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Studente non trovato.";
    exit;
}

$row = $result->fetch_assoc();
$class_id = $row['Class_ID'];

$stmt = $conn->prepare("
    SELECT subject, title, due_date 
    FROM homework 
    WHERE ClassID = ?
    ORDER BY due_date ASC
");
$stmt->bind_param("s", $class_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h2>Compiti per la tua classe</h2>";
    echo "<ul>";
    while ($hw = $result->fetch_assoc()) {
        echo "<li><strong>" . htmlspecialchars($hw['subject']) . "</strong>: " 
           . htmlspecialchars($hw['title']) 
           . " (scadenza: " . htmlspecialchars($hw['due_date']) . ")</li>";
    }
    echo "</ul>";
} else {
    echo "Nessun compito assegnato per la tua classe.";
}

$conn->close();
?>
