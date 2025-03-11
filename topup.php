<?php
session_start();
include 'db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Update balance to RM5000
$query = "UPDATE users SET balance = 5000 WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$success = $stmt->execute();

// Refresh balance from database to make sure it's updated
$query = "SELECT balance FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$new_balance = $user['balance'];

// Set session flag and new balance for UI update
$_SESSION['topup_success'] = $success;
$_SESSION['new_balance'] = $new_balance;

// Redirect back to index
header("Location: index.php");
exit();
