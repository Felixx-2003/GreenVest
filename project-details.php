<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$project_id = $_GET['project'] ?? null;
if (!$project_id) {
    die("Invalid project.");
}

// Fetch user details
$query = "SELECT name, balance, profile_image FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile image
$profile_image = !empty($user['profile_image']) ? "images/" . $user['profile_image'] : "images/default.png";

// Fetch project details
$query = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $project_id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();
if (!$project) {
    die("Project not found.");
}

// Fetch auto round-up setting for this project from user_project_settings
$query = "SELECT auto_round_up FROM user_project_settings WHERE user_id = ? AND project_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $project_id);
$stmt->execute();
$result = $stmt->get_result();
$setting = $result->fetch_assoc();
$auto_round_up = isset($setting['auto_round_up']) ? (int)$setting['auto_round_up'] : 0;


// Handle project image
$image_path = !empty($project['image']) ? "images/" . $project['image'] : "images/default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=375, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>GreenVest - Project Details</title>
    <link rel="stylesheet" href="project-details.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.all.min.js"></script>
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
            <a href="greenvest.php" class="logout">←</a>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="project-title"><?php echo htmlspecialchars($project['title']); ?></div>
            <div class="project-details">
                <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="project-image">
                <p><strong>Goal:</strong> <?php echo htmlspecialchars($project['goal']); ?></p>
                <p><strong>Timeline:</strong> <?php echo htmlspecialchars($project['timeline']); ?></p>
                <p><strong>Expected ROI:</strong> <?php echo htmlspecialchars($project['roi']); ?></p>
                <p><strong>Additional Benefits:</strong> <?php echo htmlspecialchars($project['benefits']); ?></p>
                <p class="info"><strong>Impact Metrics:</strong> <?php echo htmlspecialchars($project['impact']); ?></p>
            </div>

            <div class="investment-options">
                <h3>Choose or enter your invest amount</h3>
                <div class="amount-buttons">
                    <button data-amount="5">RM5</button>
                    <button data-amount="10">RM10</button>
                    <button data-amount="20">RM20</button>
                    <button data-amount="50">RM50</button>
                </div>
                <div class="custom-amount">
                    <label>Custom Amount (RM)</label>
                    <input type="number" id="customAmount" placeholder="Enter amount" min="1">
                </div>
                <div class="toggle-section">
                    <label>Auto round-up expenses <span class="info-icon" onclick="showAutoRoundUpInfo()">💡</span></label>
                    <label class="switch">
                        <input type="checkbox" id="autoRoundUp" <?php echo ($auto_round_up == 1) ? "checked" : ""; ?>>

                        <span class="slider"></span>
                    </label>
                </div>
                <button class="confirm-btn" onclick="confirmInvestment()">Confirm</button>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <a href="index.php">🏠</a>
            <a href="greenvest.php" class="active">🌱</a>
            <a href="profile.php">👤</a>
        </footer>
    </div>

    <script>
let selectedAmount = 0;
const buttons = document.querySelectorAll('.amount-buttons button');
const customAmountInput = document.getElementById('customAmount');
const balanceElement = document.querySelector('.balance');
const autoRoundUpCheckbox = document.getElementById("autoRoundUp");

// ✅ Fetch the latest balance before confirming the investment
function getLatestBalance() {
    return fetch("get-balance.php")
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error("Failed to retrieve balance");
            }
            return parseFloat(data.balance);
        });
}

// ✅ Handle button selection
buttons.forEach(button => {
    button.addEventListener('click', () => {
        if (button.classList.contains('selected')) {
            // Deselect if already selected
            button.classList.remove('selected');
            selectedAmount = 0;
            customAmountInput.disabled = false;
        } else {
            // Remove selection from all buttons and select the clicked one
            buttons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
            selectedAmount = parseInt(button.getAttribute('data-amount'));
            customAmountInput.value = '';
            customAmountInput.disabled = true;
        }
    });
});


// ✅ Handle custom amount input
customAmountInput.addEventListener('input', () => {
    const customValue = parseFloat(customAmountInput.value);
    buttons.forEach(btn => btn.classList.remove('selected'));

    if (!isNaN(customValue) && customValue > 0) {
        selectedAmount = customValue;
        buttons.forEach(btn => btn.disabled = true);
    } else {
        selectedAmount = 0;
        buttons.forEach(btn => btn.disabled = false);
    }
});

// ✅ Re-enable buttons when custom input is cleared
customAmountInput.addEventListener('change', () => {
    if (customAmountInput.value === '') {
        buttons.forEach(btn => btn.disabled = false);
    }
});

// ✅ Display Auto Round-Up Info
function showAutoRoundUpInfo() {
    Swal.fire({
        title: 'Auto Round-Up Expenses',
        text: 'When enabled, your expenses are rounded up to the nearest RM, and the difference is invested.',
        icon: 'info',
        confirmButtonText: 'Got it',
        confirmButtonColor: '#28a745'
    });
}

// ✅ Confirm Investment (Ensures Updated Balance)
function confirmInvestment() {
    if (selectedAmount <= 0) {
        Swal.fire({
            title: 'Error',
            text: 'Please select or enter a valid investment amount.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745'
        });
        return;
    }

    getLatestBalance().then(currentBalance => {
        console.log("Latest Balance:", currentBalance);

        if (selectedAmount > currentBalance) {
            Swal.fire({
                title: 'Insufficient Balance',
                text: 'You do not have enough funds for this investment.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
            return;
        }

        console.log("Sending request to deduct-balance.php with:", selectedAmount);

        // ✅ Send AJAX request to deduct balance
        fetch("deduct-balance.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                amount: selectedAmount,
                project_id: "<?php echo $project_id; ?>"
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Server response:", data); // Debugging

            if (data.success) {
                balanceElement.innerText = `RM ${data.new_balance.toFixed(2)}`;

                Swal.fire({
                    title: 'Investment Confirmed',
                    text: `You have invested RM${selectedAmount.toFixed(2)} in <?php echo htmlspecialchars($project['title']); ?>.`,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    window.location.href = "profile.php";
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
            }
        })
        .catch(error => {
            console.error("Error in fetch:", error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to connect to server. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        });
    }).catch(error => {
        console.error("Error fetching balance:", error);
        Swal.fire("Error", "Failed to retrieve balance. Please try again.", "error");
    });
}

// ✅ Update Auto Round-Up Toggle
autoRoundUpCheckbox.addEventListener("change", function() {
    fetch("update-roundup.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            auto_round_up: this.checked ? 1 : 0,
            project_id: "<?php echo $project_id; ?>"
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data);
        if (!data.success) {
            Swal.fire("Error", data.message, "error");
        } else {
            Swal.fire({
                title: "Success",
                text: "Auto Round-Up setting updated.",
                icon: "success",
                timer: 1000,
                showConfirmButton: false
            });
        }
    })
    .catch(error => {
        console.error("Fetch Error:", error);
    });
});



    </script>
</body>
</html>
