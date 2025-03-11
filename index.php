<?php
session_start();
include 'db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT name, balance, profile_image FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


// Now correctly set the profile image path
$profile_image = !empty($user['profile_image']) ? "images/" . $user['profile_image'] : "images/default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=375, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>GreenVest - Banking App Homepage</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="profile">
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-icon">
                <div>
                    <div class="greeting">Hello, <?php echo htmlspecialchars($user['name']); ?>!</div>
                    <div class="balance">RM <?php echo number_format($user['balance'], 2); ?></div>
                </div>
            </div>
            <a href="logout.php" class="logout">←</a>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="grid">
                <!-- GreenVest Button -->
                <a href="greenvest.php" class="grid-item greenvest">
                    <img src="images/GreenVest logo.jpeg" alt="GreenVest Logo">
                    <span>GreenVest</span>
                </a>
                <!-- Other Function Buttons -->
                <a href="pay.php" class="grid-item">
                    <img src="images/pay.png" alt="Pay Icon">
                    <span>Pay</span>
                </a>
                <a href="transfer.php" class="grid-item">
                    <img src="images/transfer.png" alt="Transfer Icon">
                    <span>Transfer</span>
                </a>
                <a href="topup.php" class="grid-item">
                    <img src="images/topup.png" alt="Top Up Icon">
                    <span>Top Up</span>
                </a>
                <a href="#" class="grid-item">
                    <img src="images/scan.png" alt="Scan Icon">
                    <span>Scan</span>
                </a>
                <a href="#" class="grid-item">
                    <img src="images/more.png" alt="More Icon">
                    <span>More</span>
                </a>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <a href="index.php" class="active">🏠</a>
            <a href="greenvest.php">🌱</a>
            <a href="profile.php">👤</a>
        </footer>
    </div>

    <?php if (isset($_SESSION['topup_success'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "<?php echo $_SESSION['topup_success'] ? 'Top Up Successful!' : 'Error'; ?>",
                text: "<?php echo $_SESSION['topup_success'] ? 'Your balance has been updated to RM5000.' : 'Failed to update balance.'; ?>",
                icon: "<?php echo $_SESSION['topup_success'] ? 'success' : 'error'; ?>",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    <?php unset($_SESSION['topup_success']); ?>
<?php endif; ?>

</body>
</html>
