<?php
include 'db.php';

session_start();
$username = $_SESSION['username']; // Assume login sets this

// Get all students' conduct
$sql = "SELECT name, surname, conduct FROM Students";
$students_result = $conn->query($sql);

// Handle homework assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class = $_POST['class'];
    $assignment = $_POST['assignment'];
    $due_date = $_POST['due_date'];

    $sql_insert = "INSERT INTO Homework (class, assignment, due_date) VALUES ('$class', '$assignment', '$due_date')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "Homework assigned successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Students' Conduct</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Surname</th>
        <th>Conduct</th>
    </tr>
    <?php while ($row = $students_result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['surname']; ?></td>
        <td><?php echo $row['conduct']; ?></td>
    </tr>
    <?php } ?>
</table>

<h2>Assign Homework</h2>
<form method="post" action="">
    <label for="class">Class:</label>
    <input type="text" id="class" name="class" required>
    
    <label for="assignment">Assignment:</label>
    <textarea id="assignment" name="assignment" required></textarea>
    
    <label for="due_date">Due Date:</label>
    <input type="date" id="due_date" name="due_date" required>
    
    <button type="submit">Assign Homework</button>
</form>
