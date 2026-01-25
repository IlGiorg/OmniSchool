<?php
$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'] ?? '';
    $duedate = $_POST['duedate'] ?? '';
    $class = $_POST['class'] ?? '';
    $assignedby = $_POST['assignedby'] ?? '';

    if (empty($description) || empty($duedate) || empty($class) || empty($assignedby)) {
        $errorMessage = "❌ All fields are required.";
    } else {
        require_once '../db/db.php';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

            $stmt = $pdo->prepare("INSERT INTO homework (description, duedate, class, assignedby) VALUES (?, ?, ?, ?)");
            $stmt->execute([$description, $duedate, $class, $assignedby]);

            $successMessage = "✅ Homework assigned successfully!";
        } catch (PDOException $e) {
            $errorMessage = "❌ Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Homework</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f8e9;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #388e3c;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        input, textarea {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            background-color: #388e3c;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2e7d32;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>Assign Homework</h2>

    <?php if ($successMessage): ?>
        <div class="message success"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="description">Homework Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="duedate">Due Date</label>
        <input type="date" id="duedate" name="duedate" required>

        <label for="class">Class</label>
        <input type="text" id="class" name="class" placeholder="e.g., 10A or 9B" required>

        <label for="assignedby">Your Teacher Username</label>
        <input type="text" id="assignedby" name="assignedby" placeholder="Enter your username" required>

        <button type="submit">Assign Homework</button>
    </form>
</div>
</body>
</html>
