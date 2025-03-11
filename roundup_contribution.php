<?php
function distributeRoundUp($conn, $user_id, $round_up_difference) {
    // Validate round-up amount
    if ($round_up_difference <= 0) {
        error_log("Invalid round-up amount: $round_up_difference");
        return;
    }

    // Get enabled projects
    $query = "SELECT project_id FROM user_project_settings WHERE (user_id = ? OR user_id IS NULL) AND auto_round_up = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $projects = [];
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row['project_id'];
    }

    $num_projects = count($projects);
    if ($num_projects == 0) {
        error_log("No enabled projects found for user: $user_id");
        return; // No projects enabled
    }

    // Compute base distribution (without exceeding total amount)
    $base_amount = floor(($round_up_difference / $num_projects) * 100) / 100; // Convert to 2 decimal places
    $remaining_amount = round($round_up_difference - ($base_amount * $num_projects), 2);

    // Prepare INSERT query only once
    $query = "INSERT INTO project_contributions (user_id, project_id, amount, date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);

    // Distribute contributions
    foreach ($projects as $index => $project_id) {
        // Give extra 0.01 to the first 'remaining_amount' projects
        $amount = ($index < $remaining_amount * 100) ? $base_amount + 0.01 : $base_amount;

        // Set user_id to NULL explicitly if needed
        $final_user_id = ($user_id == 0) ? NULL : $user_id;

        // Bind parameters
        $stmt->bind_param("isd", $final_user_id, $project_id, $amount);

        // Execute the query
        if (!$stmt->execute()) {
            error_log("Insert failed for user $user_id, project $project_id: " . $stmt->error);
        }
    }

    error_log("Round-up distributed: User $user_id, Total Amount $round_up_difference");
}
?>
