<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT name, balance, profile_image FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Ensure profile image is set correctly
$profile_image = !empty($user['profile_image']) ? "images/" . htmlspecialchars($user['profile_image']) : "images/default.png";

// Fetch categories dynamically
$query = "SELECT DISTINCT category FROM projects";
$result = $conn->query($query);

// Get selected category from URL
$category = $_GET['category'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=375, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>GreenVest Module</title>
    <link rel="stylesheet" href="greenvest.css">
    <script>
        function toggleCategoryFilter() {
            var filterDiv = document.getElementById("categoryFilter");
            if (filterDiv.style.display === "none" || filterDiv.style.display === "") {
                filterDiv.style.display = "block";
            } else {
                filterDiv.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="profile">
                <img src="<?php echo $profile_image; ?>" alt="Profile Image" class="profile-icon">
                <div>
                    <div class="greeting">Hello, <?php echo htmlspecialchars($user['name']); ?>!</div>
                    <div class="balance">RM <?php echo number_format($user['balance'], 2); ?></div>
                </div>
            </div>
            <a href="index.php" class="logout">←</a>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <h2>Investment Projects</h2>

            <!-- Filter by Category Button -->
            <button class="filter-btn" onclick="toggleCategoryFilter()">Filter by Category ⏬</button>

            <!-- Category Filter (Initially Hidden) -->
            <div id="categoryFilter" class="categories" style="display: none;">
                <a href="greenvest.php" class="category">All Projects</a>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <a href="greenvest.php?category=<?php echo urlencode($row['category']); ?>" class="category">
                        <?php echo htmlspecialchars($row['category']); ?>
                    </a>
                <?php endwhile; ?>
            </div>

            <?php
            // Fetch projects based on selected category
            if ($category) {
                $stmt = $conn->prepare("SELECT * FROM projects WHERE category = ?");
                $stmt->bind_param("s", $category);
                $stmt->execute();
                $projects = $stmt->get_result();
                echo "<h3>Projects in " . htmlspecialchars($category) . "</h3>";
            } else {
                $projects = $conn->query("SELECT * FROM projects"); // Show all projects if no category selected
                echo "<h3>All Projects</h3>";
            }
            ?>

            <div class="grid">
                <?php while ($project = $projects->fetch_assoc()): 
                    $image_path = !empty($project['image']) ? "images/" . htmlspecialchars($project['image']) : "images/default.png";
                ?>
                    <a href="project-details.php?project=<?php echo urlencode($project['id']); ?>" class="grid-item">
                        <img src="<?php echo file_exists($image_path) ? $image_path : 'images/default.png'; ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        <span><?php echo htmlspecialchars($project['title']); ?></span>
                    </a>
                <?php endwhile; ?>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <a href="index.php">🏠</a>
            <a href="greenvest.php" class="active">🌱</a>
            <a href="profile.php">👤</a>
        </footer>
    </div>
</body>
</html>
