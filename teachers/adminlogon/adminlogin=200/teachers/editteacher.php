<?php
session_start();

if (!isset($_SESSION['admin'])) {
    die("Unauthorized access.");
}

$message = '';
$teacher = [
    'Username' => '',
    'Password' => ''
];

// --- Database connection ---
require_once '../db/db.php';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);}catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// --- Handle search ---
if (isset($_POST['search_username'])) {
    $searchUsername = $_POST['search_username'];

    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE Username = ?");
    $stmt->execute([$searchUsername]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$teacher) {
        $message = "Teacher not found.";
        $teacher = ['Username' => '', 'Password' => ''];
    }
}

// --- Handle update ---
if (isset($_POST['update_teacher'])) {
    $originalUsername = $_POST['original_username'];
    $newUsername = $_POST['Username'];
    $newPassword = $_POST['Password'];

    if (!empty($newUsername) && !empty($newPassword)) {
        try {
            $stmt = $pdo->prepare("UPDATE teachers SET Username = ?, Password = ? WHERE Username = ?");
            $stmt->execute([$newUsername, $newPassword, $originalUsername]);
            $message = "Teacher updated successfully.";
            $teacher = ['Username' => '', 'Password' => ''];
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
  <title>Edit Teacher</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100vh;
      padding: 20px;
    }

    form {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      margin-bottom: 20px;
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

  <h1>Edit Teacher</h1>

  <?php if (!empty($message)): ?>
    <div class="message <?php echo (strpos($message, 'successfully') !== false) ? '' : 'error'; ?>">
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <!-- Search Form -->
  <form method="POST">
    <label for="search_username">Search by Username</label>
    <input id="search_username" name="search_username" type="text" placeholder="Enter username" required>
    <button type="submit">Search</button>
  </form>

  <!-- Edit Form (only shows if a teacher is found) -->
  <?php if (!empty($teacher['Username'])): ?>
  <form method="POST">
    <input type="hidden" name="original_username" value="<?php echo htmlspecialchars($teacher['Username']); ?>">

    <label for="Username">Username</label>
    <input id="Username" name="Username" type="text" value="<?php echo htmlspecialchars($teacher['Username']); ?>" required>

    <label for="Password">Password</label>
    <input id="Password" name="Password" type="text" value="<?php echo htmlspecialchars($teacher['Password']); ?>" required>

    <button type="submit" name="update_teacher">Update Teacher</button>
  </form>
  <?php endif; ?>

</body>
</html>
