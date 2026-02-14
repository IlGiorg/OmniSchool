<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection details
$host = 'sql109.infinityfree.com';
$db = 'if0_38817814_omnischool';
$user = 'if0_38817814';
$pass = 'OMNISoftware25'; // Change if different in MAMP
$charset = 'utf8mb4';

// Missing DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Create a PDO instance
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}


// Check if a search term was entered
if (!empty($_GET['searchTerm'])) {
    $searchTerm = '%' . $_GET['searchTerm'] . '%';
    $query = "SELECT * FROM students WHERE First_name LIKE :searchTerm OR Last_Name LIKE :searchTerm";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch all students
    $stmt = $pdo->query("SELECT * FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
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
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 300px;
        }
        .search-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #1976d2;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #1565c0;
        }
        .view-conduct-btn {
            padding: 10px 20px;
            background-color: #f57c00;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .view-conduct-btn:hover {
            background-color: #e65100;
        }
    </style>
</head>
<body>

    <h1>Search for a Student</h1>

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET">
            <input type="text" name="searchTerm" placeholder="Search by name or surname..." value="<?php echo isset($_GET['searchTerm']) ? htmlspecialchars($_GET['searchTerm']) : ''; ?>" />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Display students -->
    <h2>Student List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Surname</th>
                <th>Username</th>
                <th>Date of Birth</th>
                <th>House</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['First_name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($student['Last_Name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($student['Username'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($student['DOB'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($student['Academic_House'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($student['ClassID'] ?? ''); ?></td>

                            <td>
    <a href="view_conduct.php?studentID=<?php echo urlencode($student['ID']); ?>" target="_blank">
        <button class="view-conduct-btn">View Conduct</button>
    </a>
</td>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
