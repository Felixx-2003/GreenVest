<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

// Decode JSON from request
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user_id'];
$amount = isset($data['amount']) ? floatval($data['amount']) : 0;
$project_id = $data['project_id'] ?? null;

if (!$project_id) {
    echo json_encode(["success" => false, "message" => "Invalid project ID"]);
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

// Ensure user has enough balance
if ($amount > $current_balance) {
    echo json_encode(["success" => false, "message" => "Insufficient balance"]);
    exit();
}

// Deduct balance
$new_balance = $current_balance - $amount;
$query = "UPDATE users SET balance = ? WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("di", $new_balance, $user_id);
$stmt->execute();

// Record investment
$query = "INSERT INTO investments (user_id, project_id, amount, date) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("isd", $user_id, $project_id, $amount);
$stmt->execute();

// Success response
echo json_encode(["success" => true, "new_balance" => $new_balance]);
$stmt->close();
$conn->close();

?>
