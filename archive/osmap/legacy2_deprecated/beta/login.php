<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check in Students table
    $sql_student = "SELECT * FROM Students WHERE username = '$username' AND password = '$password'";
    $result_student = $conn->query($sql_student);

    if ($result_student->num_rows > 0) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: student.php");
        exit;
    }

    // Check in Teachers table
    $sql_teacher = "SELECT * FROM Teachers WHERE username = '$username' AND password = '$password'";
    $result_teacher = $conn->query($sql_teacher);

    if ($result_teacher->num_rows > 0) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: teacher.php");
        exit;
    }

    echo "Invalid login credentials.";
}
?>

<form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Login</button>
</form>
