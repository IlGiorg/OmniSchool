<?php
// Enable error reporting to debug 500 errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Define the correct password
$correctPassword = 'SQLBypass';

// Check if the password is correct
if (isset($_POST['password']) && $_POST['password'] === $correctPassword) {
    // Database connection details
    $host = 'localhost';
    $db = 'OSMAP';
    $user = 'root';
    $pass = 'root'; // MAMP default
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_POST['query']) && !empty($_POST['query'])) {
            // Prepare and execute the SQL query
            $query = $_POST['query'];
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                echo "<h2>Query Results:</h2>";
                echo "<pre>";
                print_r($results);
                echo "</pre>";
            } else {
                echo "<p>No results returned or the query was successful with no data.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} elseif (isset($_POST['password']) && $_POST['password'] !== $correctPassword) {
    echo "<p style='color:red;'>Incorrect password! Access denied.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Query Executor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            position: relative;
        }
        textarea {
            width: 100%;
            height: 150px;
            margin-top: 10px;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        input[type="password"] {
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }
        button {
            padding: 10px 15px;
            background-color: #1976d2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1565c0;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>SQL Query Executor</h1>

<?php if (!isset($_POST['password'])): ?>
    <form method="post">
        <label for="password">Enter Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Submit</button>
    </form>
<?php elseif (isset($_POST['password']) && $_POST['password'] === $correctPassword): ?>
    <h2>Enter SQL Query</h2>
    <form method="post">
        <textarea name="query" required></textarea><br>
        <button type="submit">Execute Query</button>
    </form>
<?php endif; ?>

</body>
</html>
