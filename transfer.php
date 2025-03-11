<?php
session_start();
include 'db.php';
include 'roundup_contribution.php';

// Detect if request is AJAX
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Ensure response is always JSON for AJAX requests
if ($is_ajax) {
    header('Content-Type: application/json');
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    if ($is_ajax) {
        echo json_encode(["success" => false, "message" => "Not logged in"]);
        exit();
    } else {
        header("Location: login.php");
        exit();
    }
}

$user_id = $_SESSION['user_id'];

// Handle AJAX request
if ($_SERVER["REQUEST_METHOD"] === "POST" && $is_ajax) {
    $data = json_decode(file_get_contents("php://input"), true);
    $recipient_id = isset($data['recipient']) ? intval($data['recipient']) : 0;
    $amount = isset($data['amount']) ? floatval($data['amount']) : 0;

    if ($recipient_id <= 0 || $amount <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid recipient or amount"]);
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

    if ($amount > $current_balance) {
        echo json_encode(["success" => false, "message" => "Insufficient balance"]);
        exit();
    }

    // Calculate round-up
    $rounded_amount = ceil($amount);
    $round_up_difference = $rounded_amount - $amount;
    $new_balance = $current_balance - $rounded_amount;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Deduct sender's balance
        $query = "UPDATE users SET balance = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $new_balance, $user_id);
        $stmt->execute();

        // Check if recipient exists
        $query = "SELECT user_id FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $recipient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $recipient_exists = $result->num_rows > 0;

        if ($recipient_exists) {
            $query = "UPDATE users SET balance = balance + ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("di", $amount, $recipient_id);
            $stmt->execute();
        } else {
            $recipient_id = 0;
        }

        $query = "INSERT INTO transfers (sender_id, recipient_id, amount, round_up, date) 
          VALUES (?, NULLIF(?, 0), ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iidd", $user_id, $recipient_id, $amount, $round_up_difference);

        $stmt->execute();

        distributeRoundUp($conn, $user_id, $round_up_difference);
        $conn->commit();

        echo json_encode(["success" => true, "new_balance" => $new_balance, "recipient_valid" => $recipient_exists]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Transaction failed: " . $e->getMessage()]);
    }
    

    exit();
}

// If not an AJAX request, show the form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Money</title>
    <link rel="stylesheet" href="transfer.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2>Transfer Money</h2>
        <form id="transferForm">
            <label for="recipient">Recipient User ID:</label>
            <input type="number" id="recipient" required autofocus>
            <label for="amount">Amount (RM):</label>
            <input type="number" id="amount" min="1" step="0.01" required>
            <button type="submit">Transfer</button>
        </form>
    </div>

    <script>
        document.getElementById('transferForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let recipient = document.getElementById('recipient').value.trim();
            let amount = parseFloat(document.getElementById('amount').value);

            if (!recipient || amount <= 0) {
                Swal.fire("Error", "Please enter valid details.", "error");
                return;
            }

            fetch('transfer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // Mark request as AJAX
                },
                body: JSON.stringify({ recipient: recipient, amount: amount })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let message = data.recipient_valid
                        ? `Transfer successful. New Balance: RM${data.new_balance.toFixed(2)}`
                        : "Transfer successful. Recipient is invalid, but your round-up was distributed.";

                    Swal.fire("Transfer Successful", message, "success")
                    .then(() => window.location.href = "index.php");
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire("Error", "Failed to process the transfer.", "error");
            });
        });
    </script>
</body>
</html>
