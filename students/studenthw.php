<?php
// Connessione al database
$host = 'localhost';
$db = 'omni';
$user = 'your_db_user';
$pass = 'your_db_password';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Username dello studente (può venire da sessione o parametro GET/POST)
$username = $_GET['username']; // Assicurati di sanitizzare se viene da input utente

// Query per ottenere la classe dello studente
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

// Ora otteniamo i compiti per quella classe
$stmt = $conn->prepare("
    SELECT subject, title, due_date 
    FROM homework 
    WHERE ClassID = ?
    ORDER BY due_date ASC
");
$stmt->bind_param("s", $class_id);
$stmt->execute();
$result = $stmt->get_result();

// Mostriamo i compiti
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
