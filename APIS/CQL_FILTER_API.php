//Link for 
// http://localhost/png/PID.php?url=http://iwmsgis.pmc.gov.in/geoserver/&workspaceName=pmc&layerName=IWMS_point,IWMS_line&cql_filter = PID

<?php

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

// Your GeoServer configuration
define("GEOSERVER_URL", "http://iwmsgis.pmc.gov.in/geoserver/");

function get_geoserver_village_name($url, $workspaceName, $layerNames, $columns, $cql_filter) {
    $all_features = []; // Store all features from all layers
    $pid_list = []; // Store unique PIDs

    // Loop through each layer in the layerNames array
    foreach ($layerNames as $layerName) {
        // Construct the WFS request URL with CQL_FILTER
        $wfs_url = sprintf(
            "%s/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=%s:%s&outputFormat=application/json&CQL_FILTER=%s",
            $url,
            $workspaceName,
            $layerName,
            urlencode($cql_filter) // Apply URL encoding to the CQL filter
        );

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
        error_log("Raw GeoServer response for layer $layerName: " . print_r($data, true));

        // Process the features and extract PIDs
        foreach ($data['features'] as $feature) {
            $feature_data = ['id' => $feature['id']]; // Start with the id
            
            if (!empty($columns)) {  // If specific columns are provided
                foreach ($columns as $column) {
                    $column = trim($column);  // Strip whitespace
                    if (isset($feature['properties'][$column])) {
                        $feature_data[$column] = $feature['properties'][$column];
                    }
                }
            } else {
                // Add all properties if no specific columns are provided
                $feature_data = array_merge($feature_data, $feature['properties']);
            }

            // Add the feature data to the all_features list
            $all_features[] = $feature_data;

            // Check if PID is present and add to pid_list if not already added
            if (isset($feature['properties']['PID']) && !in_array($feature['properties']['PID'], $pid_list)) {
                $pid_list[] = $feature['properties']['PID']; // Ensure unique PIDs
            }
        }
    }

    // Return the unique PID list as JSON
    echo json_encode([
        "unique_pids" => $pid_list,
        "all_features" => $all_features
    ]);
}

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $url = $_GET['url'] ?? GEOSERVER_URL;  // Default to GEOSERVER_URL
    $workspaceName = $_GET['workspaceName'] ?? '';
    $layerNames = isset($_GET['layerName']) ? explode(',', $_GET['layerName']) : [];  // Multiple layer names as array
    $columns = isset($_GET['columns']) ? explode(',', $_GET['columns']) : [];  // Get columns as an array
    $cql_filter = $_GET['cql_filter'] ?? '';  // The CQL filter string

    // Call the function to fetch data from multiple layers with CQL filters
    get_geoserver_village_name($url, $workspaceName, $layerNames, $columns, $cql_filter);
} else {
    http_response_code(405);  // Method Not Allowed
    echo json_encode(["error" => "Method not allowed"]);
}
?>
