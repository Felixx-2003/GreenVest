<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT balance FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo json_encode(["success" => true, "balance" => $user['balance']]);
$stmt->close();
$conn->close();
?>
