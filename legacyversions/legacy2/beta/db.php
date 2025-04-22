<?php
$host = 'localhost';
$user = 'php'; 
$password = 'yessir'; 
$dbname = 'osmap';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
