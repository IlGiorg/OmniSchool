<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "<p>Unauthorized. Please log in as a student.</p>";
    exit;
}

$homeworkList = [];
$className = "";
$studentName = "";

try {
    $pdo = new PDO("mysql:host=sql109.infinityfree.com;dbname=if0_38817814_omnischool;charset=utf8mb4", "if0_38817814", "OMNISoftware25", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Get student class info
    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT First_name, Class_ID FROM students WHERE Username = ?");
    $stmt->execute([$username]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "<p>Student not found.</p>";
        exit;
    }

    $className = $student['Class'];
    $studentName = $student['First_name'];

    // Get homework for that class
    $stmt = $pdo->prepare("SELECT * FROM homework WHERE class = ? ORDER BY duedate ASC");
    $stmt->execute([$className]);
    $homeworkList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<pre>" . $e->getMessage() . "</pre>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Homework</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f8e9;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #388e3c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #c8e6c9;
            text-align: center;
        }

        th {
            background-color: #388e3c;
            color: white;
        }

        tr:hover {
            background-color: #e8f5e9;
        }

        .no-homework {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Hello, <?= htmlspecialchars($studentName) ?> 👋</h1>
    <h2>Your Class: <?= htmlspecialchars($className) ?></h2>
    <h2>Assigned Homework</h2>

    <?php if (count($homeworkList) === 0): ?>
        <p class="no-homework">🎉 No homework has been assigned to your class yet.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Description</th>
                <th>Due Date</th>
                <th>Assigned By</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($homeworkList as $hw): ?>
                <tr>
                    <td><?= htmlspecialchars($hw['description']) ?></td>
                    <td><?= htmlspecialchars($hw['duedate']) ?></td>
                    <td><?= htmlspecialchars($hw['assignedby']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
