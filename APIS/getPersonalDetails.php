<?php
require 'db.php'; 

// Read input (candidate name and constituency name from the client request)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if the 'name' and 'constituency' keys are present in the input data
if (isset($data['name']) && isset($data['constituency'])) {
    // Get the input values and trim whitespace
    $candidate_name = trim($data['name']);
    $constituency_name = trim($data['constituency']);

    // Use PDO's quote method to escape input and avoid SQL injection
    $candidate_name = $pdo->quote('%' . $candidate_name . '%'); // Wrap with wildcards for partial matching
    $constituency_name = $pdo->quote('%' . $constituency_name . '%'); // Wrap with wildcards for partial matching

    // SQL query to get candidate details for the given name and constituency
    $query = "SELECT * FROM candidates WHERE name ILIKE $candidate_name AND constituency ILIKE $constituency_name";

    try {
        // Execute the query
        $stmt = $pdo->query($query);

        // Check if any result is returned
        if ($stmt->rowCount() > 0) {
            // Fetch the candidate details
            $candidate_details = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the candidate details as a JSON response
            echo json_encode([
                "status" => "success",
                "data" => $candidate_details
            ]);
        } else {
            // If no candidate found, return an error message
            echo json_encode([
                "status" => "error",
                "message" => "No candidate found with the given name and constituency."
            ]);
        }
    } catch (PDOException $e) {
        // If an error occurs, return an error message
        echo json_encode([
            "status" => "error",
            "message" => "Error executing query: " . $e->getMessage()
        ]);
    }
} else {
    // If either name or constituency is missing, return an error message
    echo json_encode([
        "status" => "error",
        "message" => "Both candidate name and constituency name are required."
    ]);
}
?>
