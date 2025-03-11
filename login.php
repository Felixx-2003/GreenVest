<?php
session_start();
include 'db.php';

// Check if session user_id exists and verify it in the database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT user_id FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists, redirect to index.php
    if ($result->num_rows > 0) {
        header("Location: index.php");
        exit();
    } else {
        // If session is invalid, destroy it
        session_destroy();
    }
}

$error = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT user_id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: index.php"); // Redirect to GreenVest dashboard
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bank Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <img src="images/bank-logo.png" alt="Bank Logo" class="logo">
        <h2>Welcome to Online Banking</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter Email" required autofocus>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
