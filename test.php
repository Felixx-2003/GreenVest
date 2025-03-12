<?php
session_start();
include 'db.php';

// Define the new password
$new_password = "dhieban123"; // Change this to the desired new password

// Hash the new password securely
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update the database for Bob Smith
$query = "UPDATE users SET password = ? WHERE name = 'Dhieban'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $hashed_password);

if ($stmt->execute()) {
    echo "Password reset successfully for Dhieban. New password: " . $new_password;
} else {
    echo "Error updating password: " . $conn->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
