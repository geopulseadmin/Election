<?php
// Set the content type to JSON
header('Content-Type: application/json');

require 'db.php'; // Ensure db.php contains the correct database credentials

// Read input from the request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if the 'constituency' parameter is provided in the JSON body
if (isset($data['constituency']) && !empty($data['constituency'])) {
    $constituency = $data['constituency'];

    // Create the PDO connection
    try {
        echo "Attempting to connect to the database...\n";
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Database connection successful!\n";

        // Query to fetch data from the 'candidates' table
        $sql = "SELECT image_path, name, party FROM candidates WHERE constituency = :constituency";
        echo "Preparing the SQL query...\n";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':constituency', $constituency, PDO::PARAM_STR);

        echo "Executing the query...\n";
        $stmt->execute();

        // Fetch the results as an associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if any data was found
        if ($data) {
            echo "Data found. Sending response...\n";
            echo json_encode($data);
        } else {
            echo json_encode(["message" => "No data found for the given constituency"]);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Please provide a constituency parameter in JSON body"]);
}
?>
