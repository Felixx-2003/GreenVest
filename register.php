<?php
session_start();
include 'db.php';

$error = "";

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing
    $profile_image = "user.png"; // Default profile picture
    $default_balance = 5000.00; // Default balance for new users

    // Check if email already exists
    $query = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "❌ Email is already registered!";
    } else {
        // Insert new user
        $query = "INSERT INTO users (name, email, password, balance, profile_image) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssds", $name, $email, $password, $default_balance, $profile_image);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id; // Auto-login after registration
            header("Location: index.php"); // Redirect to dashboard
            exit();
        } else {
            $error = "❌ Registration failed. Please try again.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - GreenVest</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <img src="images/bank-logo.png" alt="Bank Logo" class="logo">
        <h2>Create Your Account</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Your Name" required autofocus>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
