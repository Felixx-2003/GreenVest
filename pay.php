<?php
session_start();
include 'db.php';
include 'roundup_contribution.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// **PROCESS PAYMENT ONLY IF REQUEST IS AJAX**
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    $merchant = $data['merchant'] ?? '';
    $amount = isset($data['amount']) ? floatval($data['amount']) : 0;

    // Validate payment amount
    if ($amount <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid amount"]);
        exit();
    }

    // Fetch user balance
    $query = "SELECT balance FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $current_balance = $user['balance'];

    // Ensure sufficient balance
    if ($amount > $current_balance) {
        echo json_encode(["success" => false, "message" => "Insufficient balance"]);
        exit();
    }

    // Calculate round-up
    $rounded_amount = ceil($amount);
    $round_up_difference = $rounded_amount - $amount;
    $new_balance = $current_balance - $rounded_amount;

    // Update user balance
    $query = "UPDATE users SET balance = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("di", $new_balance, $user_id);
    $stmt->execute();

    // Record payment
    $query = "INSERT INTO payments (user_id, merchant, amount, round_up, date) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isdd", $user_id, $merchant, $amount, $round_up_difference);
    $stmt->execute();

    // Distribute auto round-up contributions
    distributeRoundUp($conn, $user_id, $round_up_difference);

    echo json_encode(["success" => true, "new_balance" => $new_balance]);
    exit();  // **Exit after processing AJAX**
}
?>

<!-- **SHOW PAYMENT FORM ONLY IF NOT AN AJAX REQUEST** -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Merchant</title>
    <link rel="stylesheet" href="pay.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2>Pay Merchant</h2>
        <form id="payForm">
            <label for="merchant">Merchant Name:</label>
            <input type="text" id="merchant" required>
            <label for="amount">Amount (RM):</label>
            <input type="number" id="amount" min="1" step="0.01" required>
            <button type="submit">Pay</button>
        </form>
    </div>

    <script>
        document.getElementById('payForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let merchant = document.getElementById('merchant').value.trim();
            let amount = parseFloat(document.getElementById('amount').value);

            if (!merchant || amount <= 0) {
                Swal.fire("Error", "Please enter valid details.", "error");
                return;
            }

            fetch('pay.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ merchant: merchant, amount: amount })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Payment Successful", `New Balance: RM${data.new_balance.toFixed(2)}`, "success")
                    .then(() => window.location.href = "index.php");
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire("Error", "Failed to process payment.", "error");
            });
        });
    </script>
</body>
</html>
