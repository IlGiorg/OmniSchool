<?php
// Enable error reporting to debug 500 errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection details
$host = 'localhost';
$db = 'OSMAP';
$user = 'root';
$pass = 'root'; // MAMP default
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Create PDO instance
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db: " . $e->getMessage());
}

// Get student ID from URL (using studentID)
$studentID = $_GET['studentID'] ?? null;

if (!is_numeric($studentID)) {
    echo "<p style='color:red;font-weight:bold;'>Invalid or missing student ID. Please use ?studentID=123 in the URL.</p>";
    exit;
}

// Fetch conduct records for the student
$query = "SELECT * FROM Consequences WHERE Student_ID = :studentID ORDER BY Date_Assigned DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':studentID', $studentID, PDO::PARAM_INT);
$stmt->execute();
$conductRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

$noRecords = empty($conductRecords);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conduct Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            position: relative;
            min-height: 100vh;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #1976d2;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-records {
            color: red;
            text-align: center;
            margin-top: 20px;
        }
        .last-updated {
            position: fixed;
            bottom: 10px;
            right: 20px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>

    <h1>Conduct Records for Student ID: <?php echo htmlspecialchars($studentID); ?></h1>

    <?php if ($noRecords): ?>
        <p class="no-records">No conduct records found for this student.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Reason</th>
                    <th>Date Assigned</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($conductRecords as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['Consequence_Type']); ?></td>
                        <td><?php echo htmlspecialchars($record['Reason']); ?></td>
                        <td><?php echo htmlspecialchars(date("Y-m-d H:i", strtotime($record['Date_Assigned']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="last-updated">
        Last updated: <?php echo date("Y-m-d H:i:s"); ?>
    </div>

</body>
</html>
