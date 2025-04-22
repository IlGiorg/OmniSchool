<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /errors/error/401.html");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=OSMAP;charset=utf8mb4", "root", "root", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$success = "";
$error = "";
$students = [];
$editingStudent = null;

// Handle delete
if (isset($_POST['delete']) && isset($_POST['ID'])) {
    $stmt = $pdo->prepare("DELETE FROM Students WHERE ID = ?");
    $stmt->execute([$_POST['ID']]);
    $success = "✅ Student deleted successfully.";
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE Students SET First_name=?, Last_Name=?, Username=?, Password=?, Academic_House=?, DOB=? WHERE ID=?");
    $stmt->execute([
        $_POST['First_Name'],
        $_POST['Last_Name'],
        $_POST['Username'],
        $_POST['Password'],
        $_POST['Academic_House'],
        $_POST['DOB'],
        $_POST['ID']
    ]);
    $success = "✅ Student updated successfully!";
}

// Load student list after search
if (isset($_GET['search'])) {
    $searchQuery = "%" . $_GET['search'] . "%";
    $stmt = $pdo->prepare("SELECT * FROM Students WHERE First_name LIKE ? OR Last_Name LIKE ? OR Username LIKE ?");
    $stmt->execute([$searchQuery, $searchQuery, $searchQuery]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Load student details for editing
if (isset($_POST['edit']) && isset($_POST['ID'])) {
    $stmt = $pdo->prepare("SELECT * FROM Students WHERE ID = ?");
    $stmt->execute([$_POST['ID']]);
    $editingStudent = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student - OSMAP</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            padding: 30px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #1976d2;
        }
        form {
            margin-top: 20px;
        }
        input, select, button {
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input, select {
            width: 100%;
            margin-top: 10px;
        }
        button {
            background: #1976d2;
            color: white;
            border: none;
            transition: background 0.3s;
            cursor: pointer;
        }
        button:hover {
            background: #125ea2;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .actions button.delete {
            background: #e53935;
        }
        .actions button.delete:hover {
            background: #b71c1c;
        }
        .success {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .search-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .search-group input[type="text"],
        .search-group select {
            flex: 1;
        }
    </style>
    <script>
        let searchTimeout;

        function searchStudents() {
            const query = document.getElementById("searchBox").value.trim();
            if (!query) return;

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                window.location.href = "/technical/error/618.html";
            }, 6000); // Redirect after 6 seconds if no results

            window.location.href = `?search=${encodeURIComponent(query)}`;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Edit Student</h2>

        <div class="search-group">
            <input type="text" id="searchBox" placeholder="Search by name, username..." onkeyup="if(event.key === 'Enter') searchStudents();">
            <button onclick="searchStudents()">Search</button>
        </div>

        <?php if (!empty($students)): ?>
            <form method="POST">
                <div class="search-group">
                    <select id="studentSelect" name="ID">
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['ID'] ?>"><?= htmlspecialchars($student['First_name'] . ' ' . $student['Last_Name']) ?> (<?= htmlspecialchars($student['Username']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="edit">Edit</button>
                </div>
            </form>
        <?php elseif (isset($_GET['search'])): ?>
            <p class="error">No students found matching your search.</p>
        <?php endif; ?>

        <?php if ($editingStudent): ?>
            <form method="POST">
                <input type="hidden" name="ID" value="<?= htmlspecialchars($editingStudent['ID']) ?>">
                <input type="text" name="First_Name" placeholder="First Name" value="<?= htmlspecialchars($editingStudent['First_name']) ?>" required>
                <input type="text" name="Last_Name" placeholder="Last Name" value="<?= htmlspecialchars($editingStudent['Last_Name']) ?>" required>
                <input type="text" name="Username" placeholder="Username" value="<?= htmlspecialchars($editingStudent['Username']) ?>" required>
                <input type="text" name="Password" placeholder="Password" value="<?= htmlspecialchars($editingStudent['Password']) ?>" required>
                <input type="text" name="Academic_House" placeholder="Academic House" value="<?= htmlspecialchars($editingStudent['Academic_House']) ?>">
                <input type="date" name="DOB" value="<?= htmlspecialchars($editingStudent['DOB']) ?>">
                    <button type="submit" name="update">Update Student</button>
                    <button type="submit" name="delete" class="delete" onclick="return confirm('Are you sure you want to delete this student?');">Delete Student</button>
                </div>
            </form>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
