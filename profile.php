<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details including profile image
$query = "SELECT name, balance, profile_image FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$profile_image = !empty($user['profile_image']) ? "images/" . $user['profile_image'] : "images/default.png";

// Fetch total investment per project
$query = "SELECT p.title, p.impact, SUM(i.amount) AS total_investment
          FROM investments i
          JOIN projects p ON i.project_id = p.id
          WHERE i.user_id = ?
          GROUP BY p.title, p.impact";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$investments = $stmt->get_result();
$investmentData = [];
while ($row = $investments->fetch_assoc()) {
    $investmentData[] = $row;
}
$stmt->close();

// Fetch total contributions per project
$query = "SELECT p.title, SUM(pc.amount) AS total_contributed
          FROM project_contributions pc
          JOIN projects p ON pc.project_id = p.id
          WHERE pc.user_id = ?
          GROUP BY p.title";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$contributions = $stmt->get_result();
$contributionData = [];
while ($row = $contributions->fetch_assoc()) {
    $contributionData[] = $row;
}
$stmt->close();

// Fetch total investment
$query = "SELECT SUM(amount) AS total_investment FROM investments WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalInvestment = $row['total_investment'] ?? 0; // Default to 0 if no investment
$stmt->close();

// Assign badge level and user tier
if ($totalInvestment >= 5000) {
    $badge = "diamond.png";
    $tier = "Diamond";
} elseif ($totalInvestment >= 1000) {
    $badge = "gold.png";
    $tier = "Gold";
} elseif ($totalInvestment >= 500) {
    $badge = "silver.png";
    $tier = "Silver";
} elseif ($totalInvestment >= 100) {
    $badge = "bronze.png";
    $tier = "Bronze";
} else {
    $badge = ""; // No badge
    $tier = "No Tier";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=375, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>GreenVest - Profile</title>
    <link rel="stylesheet" href="profile.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
        <div class="profile">
    <div class="profile-pic-container">
        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-icon">
        <?php if ($badge): ?>
            <img src="images/<?php echo $badge; ?>" alt="Badge" class="badge-icon">
        <?php endif; ?>
    </div>
    <div>
        <div class="greeting">Hello, <?php echo htmlspecialchars($user['name']); ?>!</div>
        <div class="balance">RM <?php echo number_format($user['balance'], 2); ?></div>
    </div>
</div>

            <a href="greenvest.php" class="logout">←</a>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="profile-section">
                <h2>Dashboard</h2>

                <!-- Investment Chart -->
                <div class="chart-container">
                    <h3>Investment Breakdown</h3>
                    <canvas id="investmentChart"></canvas>
                </div>

                <!-- Investment Summary -->
                <div class="impact-summary">
                    <h3>Investment Impact</h3>
                    <div id="investmentList">
                        <?php foreach ($investmentData as $investment): ?>
                            <p>
                                <strong><?php echo htmlspecialchars($investment['title']); ?></strong>: 
                                RM<?php echo number_format($investment['total_investment'], 2); ?> invested → 
                                <?php echo htmlspecialchars($investment['impact']); ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Contribution Chart -->
                <div class="chart-container">
                    <h3>Contribution Breakdown</h3>
                    <canvas id="contributionChart"></canvas>
                </div>
                
                                <!-- Contribution Summary -->
                                <div class="impact-summary">
                    <h3>Round-Up Contributions</h3>
                    <div id="contributionList">
                        <?php foreach ($contributionData as $contribution): ?>
                            <p>
                                <strong><?php echo htmlspecialchars($contribution['title']); ?></strong>: 
                                RM<?php echo number_format($contribution['total_contributed'], 2); ?> contributed
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ADD REWARDS HERE -->
                <div class="impact-summary rewards-section">
                    <h3>Your Rewards</h3>
                    <div id="rewardsList">
                        <?php
                        $query = "SELECT reward_name, partner, description, reward_type
                                FROM rewards 
                                WHERE min_investment <= ? 
                                ORDER BY min_investment DESC";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("d", $totalInvestment);
                        $stmt->execute();
                        $rewards = $stmt->get_result();
                        $stmt->close();

                        if ($rewards->num_rows > 0):
                            while ($reward = $rewards->fetch_assoc()):
                                // Set reward icon based on type
                                $rewardIcons = [
                                    "Free Ticket" => "🎟️",
                                    "Voucher" => "🛒",
                                    "Discount" => "💸",
                                    "Cashback" => "💰",
                                    "Gift" => "🎁"
                                ];
                                $icon = $rewardIcons[$reward['reward_type']] ?? "🏆"; // Default icon
                        ?>
                            <div class="reward-item">
                                <span class="reward-icon"><?php echo $icon; ?></span>
                                <div class="reward-info">
                                    <h4><?php echo htmlspecialchars($reward['reward_name']); ?></h4>
                                    <p><strong>Partner:</strong> <?php echo htmlspecialchars($reward['partner']); ?></p>
                                    <p><?php echo htmlspecialchars($reward['description']); ?></p>
                                </div>
                            </div>
                        <?php endwhile;
                        else: ?>
                            <p>No rewards available. Increase your investment to unlock rewards!</p>
                        <?php endif; ?>
                    </div>
                </div>
                        </div>


    </div>
        <!-- Footer -->
        <footer class="footer">
            <a href="index.php">🏠</a>
            <a href="greenvest.php">🌱</a>
            <a href="profile.php" class="active">👤</a>
        </footer>
    <script>
        
        // Fetch investment data
        const investmentData = <?php echo json_encode(array_map(function($row) {
            return ['title' => $row['title'], 'amount' => $row['total_investment']];
        }, $investmentData)); ?>;

        // Fetch contribution data
        const contributionData = <?php echo json_encode(array_map(function($row) {
            return ['title' => $row['title'], 'amount' => $row['total_contributed']];
        }, $contributionData)); ?>;

// Ensure data is converted to numbers
investmentData.forEach(item => item.amount = parseFloat(item.amount));
contributionData.forEach(item => item.amount = parseFloat(item.amount));

        const backgroundColors = ['#28a745', '#ffd700', '#1976d2', '#d32f2f', '#ff9800', '#9c27b0', '#2196f3', '#8bc34a'];

        // Create Investment Chart
        const ctxInvestment = document.getElementById('investmentChart').getContext('2d');
        new Chart(ctxInvestment, {
            type: 'doughnut',
            data: {
                labels: investmentData.map(item => item.title),
                datasets: [{
                    data: investmentData.map(item => item.amount),
                    backgroundColor: backgroundColors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Investment Breakdown' }
                }
            }
        });

        // Create Contribution Chart
        const ctxContribution = document.getElementById('contributionChart').getContext('2d');
        new Chart(ctxContribution, {
            type: 'doughnut',
            data: {
                labels: contributionData.map(item => item.title),
                datasets: [{
                    data: contributionData.map(item => item.amount),
                    backgroundColor: backgroundColors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Contribution Breakdown' }
                }
            }
        });
    </script>
</body>
</html>
