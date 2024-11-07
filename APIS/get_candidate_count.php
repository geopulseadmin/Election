<?php
require 'db.php'; 

// Read input (the constituency name from the client request)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if the 'constituency' key is present in the input data
if (isset($data['constituency'])) {
    $constituency_name = $data['constituency']; // No need to escape in PDO, it's handled by prepared statements

    try {
        // Prepare the query to count candidates for the provided constituency
        $query = "SELECT COUNT(*) FROM candidates WHERE constituency = :constituency";
        
        // Prepare the statement
        $stmt = $pdo->prepare($query);
        
        // Bind the constituency name to the parameter
        $stmt->bindParam(':constituency', $constituency_name, PDO::PARAM_STR);
        
        // Execute the query
        $stmt->execute();
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get the count of candidates
        $count = $result['count'];

        // Return the count as a JSON response
        echo json_encode(["status" => "success", "count" => $count]);

    } catch (PDOException $e) {
        // Handle query execution errors
        echo json_encode(["status" => "error", "message" => "Error executing query: " . $e->getMessage()]);
    }

} else {
    // If no constituency name was provided, return an error message
    echo json_encode(["status" => "error", "message" => "Constituency name is required."]);
}
?>
