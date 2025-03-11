<?php
function getUserContributions($conn, $user_id) {
    $query = "SELECT project_id, SUM(amount) as total_amount FROM project_contributions
              WHERE user_id = ? GROUP BY project_id";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}
?>
