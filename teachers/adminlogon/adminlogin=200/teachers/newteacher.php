<?php
session_start();

// Uncomment this if you want error messages to show during development
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// --- Check if user is logged in as admin ---
if (!isset($_SESSION['admin'])) {
    die("Unauthorized access.");
}

// --- Handle form submission ---
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Username = $_POST['Username'] ?? '';
    $Password = $_POST['Password'] ?? '';

    if (!empty($Username) && !empty($Password)) {
        try {
            $pdo = new PDO(
                "mysql:host=sql109.infinityfree.com;dbname=if0_38817814_omnischool;charset=utf8mb4",
                "if0_38817814",
                "OMNISoftware25",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );

            $stmt = $pdo->prepare("INSERT INTO teachers (Username, Password) VALUES (?, ?)");
            $stmt->execute([$Username, $Password]);

            $message = "Teacher added successfully.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Username already exists. Please choose another.";
            } else {
                $message = "Database error: " . htmlspecialchars($e->getMessage());
            }
        }
    } else {
        $message = "Please fill in both Username and Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Teacher</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    form {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    h1 {
      font-size: 24px;
      margin-bottom: 20px;
      text-align: center;
      color: #333;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      font-size: 14px;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    button {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background-color: #2c88d9;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background-color: #2172b8;
    }

    .message {
      text-align: center;
      margin-bottom: 20px;
      font-size: 16px;
      color: green;
    }

    .error {
      color: red;
    }
  </style>
</head>
<body>

  <form method="POST">
    <h1>Add New Teacher</h1>

    <?php if (!empty($message)): ?>
      <div class="message <?php echo (strpos($message, 'success') !== false) ? '' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <label for="username">Username</label>
    <input id="username" name="Username" type="text" placeholder="Username" required>

    <label for="password">Password</label>
    <input id="password" name="Password" type="text" placeholder="Password" required>

    <button type="submit">Add Teacher</button>
  </form>

</body>
</html>
