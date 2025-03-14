<?php
$host = "localhost"; // Change if using a live server
$user = "root"; // Default XAMPP user
$pass = ""; // Default XAMPP password
$db = "greenvest_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
