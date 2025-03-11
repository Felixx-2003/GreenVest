<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);
$project_id = $data['project_id'] ?? null;
$auto_round_up = isset($data['auto_round_up']) ? (int) $data['auto_round_up'] : 0;

if (!$project_id) {
    echo json_encode(["success" => false, "message" => "Invalid project"]);
    exit();
}

// Debugging: Log received values
error_log("Received: user_id=$user_id, project_id=$project_id, auto_round_up=$auto_round_up");

// Insert or update user setting
$query = "INSERT INTO user_project_settings (user_id, project_id, auto_round_up) 
          VALUES (?, ?, ?) 
          ON DUPLICATE KEY UPDATE auto_round_up = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("isii", $user_id, $project_id, $auto_round_up, $auto_round_up);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    error_log("Database update failed: " . $stmt->error);
    echo json_encode(["success" => false, "message" => "Database update failed"]);
}

$stmt->close();
$conn->close();
?>
