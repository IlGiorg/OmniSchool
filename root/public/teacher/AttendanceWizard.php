<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: /");
    exit;
}

require_once '../../controllers/app/AttendanceWand.php'; // adjust path if needed

// Step 1: get classes
$classes = getAllClasses();

// Step 2: if class selected → get students
$classID = $_GET['class'] ?? null;
$students = [];

if ($classID) {
    $students = getStudentsByClass($classID);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Wizard</title>
</head>
<body>

<h2>Select Class</h2>

<form method="GET">
    <select name="class" required>
        <option value="">Select class</option>
        <?php foreach ($classes as $c): ?>
            <option value="<?= $c['ClassID'] ?>">
                Year <?= $c['Year'] ?> <?= $c['Form'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Load</button>
</form>

<?php if ($classID && $students): ?>
<hr>

<h2>Take Attendance</h2>

<form method="POST">
    <input type="hidden" name="classID" value="<?= htmlspecialchars($classID) ?>">

    <?php foreach ($students as $s): ?>
        <p>
            <?= htmlspecialchars($s['First_name'] . ' ' . $s['Last_Name']) ?>
            <select name="status[<?= $s['ID'] ?>]" required>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Late">Late</option>
                <option value="Out of School">Out of School</option>
            </select>
        </p>
    <?php endforeach; ?>

    <button type="submit">Submit Attendance</button>
</form>
<?php endif; ?>

<?php
// Step 3: handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../controllers/app/AttendanceWand.php';

    $result = submitAttendance(
        $_POST['classID'],
        $_POST['status'],
        $_SESSION['username']
    );

    echo "<p>$result</p>";
}
?>

</body>
</html>