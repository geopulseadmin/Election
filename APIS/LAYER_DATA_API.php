
<?php

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

// Your GeoServer configuration
define("GEOSERVER_URL", "http://iwmsgis.pmc.gov.in/geoserver/");

function get_geoserver_village_name($url, $workspaceName, $layerName, $columns) {
    // Construct the WFS request URL
    $wfs_url = sprintf("%s/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%s:%s&outputFormat=application/json",
                       $url, $workspaceName, $layerName);
    
    // Debugging: Print the constructed URL
    error_log("WFS URL: $wfs_url");

    // Initialize cURL
    $ch = curl_init($wfs_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Execute the request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Close the cURL session
    curl_close($ch);

    if ($http_code !== 200) {
        http_response_code(500);
        echo json_encode(["error" => "Request failed with status: $http_code"]);
        return;
    }

    // Decode the JSON response
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to decode JSON"]);
        return;
    }

    // Debug print to check the full response
    error_log("Raw GeoServer response: " . print_r($data, true));

    $filtered_data = [];

    foreach ($data['features'] as $feature) {
        $feature_data = ['id' => $feature['id']];  // Start with the id
        
        if (!empty($columns)) {  // If specific columns are provided
            foreach ($columns as $column) {
                $column = trim($column);  // Strip whitespace
                if (isset($feature['properties'][$column])) {
                    $feature_data[$column] = $feature['properties'][$column];
                }
            }
        } else {
            // Print properties before updating
            error_log("Adding all properties: " . print_r($feature['properties'], true));  // Debug print for properties
            $feature_data = array_merge($feature_data, $feature['properties']);  // Add all properties if no specific columns
        }

        $filtered_data[] = $feature_data;  // Append the feature data to the list
    }

    // Return the filtered data as JSON
    echo json_encode($filtered_data);
}

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $url = $_GET['url'] ?? GEOSERVER_URL;  // Default to GEOSERVER_URL
    $workspaceName = $_GET['workspaceName'] ?? '';
    $layerName = $_GET['layerName'] ?? '';
    $columns = isset($_GET['columns']) ? explode(',', $_GET['columns']) : [];  // Get columns as an array

    get_geoserver_village_name($url, $workspaceName, $layerName, $columns);
} else {
    http_response_code(405);  // Method Not Allowed
    echo json_encode(["error" => "Method not allowed"]);
}
?>
