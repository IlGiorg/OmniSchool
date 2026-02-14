<?php
include 'db.php';

session_start();
$username = $_SESSION['username']; // Assume login sets this

// Get student data
$sql = "SELECT conduct, form FROM Students WHERE username = '$username'";
$result = $conn->query($sql);
$student = $result->fetch_assoc();

// Get homework for the student's form
$form = $student['form'];
$sql_hw = "SELECT assignment, due_date FROM Homework WHERE class = '$form'";
$hw_result = $conn->query($sql_hw);

?>

<h2>Your Conduct</h2>
<p><?php echo $student['conduct']; ?></p>

<h2>Your Homework</h2>
<table>
    <tr>
        <th>Assignment</th>
        <th>Due Date</th>
    </tr>
    <?php while ($row = $hw_result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['assignment']; ?></td>
        <td><?php echo $row['due_date']; ?></td>
    </tr>
    <?php } ?>
</table>
