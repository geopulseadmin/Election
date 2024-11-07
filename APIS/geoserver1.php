//http://localhost/png/geoserver1.php?url=http://iwmsgis.pmc.gov.in/geoserver/&workspaceName=PMC_test&layerNames=plot1_layouts_test,Assets

<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

function getGeoServerData($url, $workspaceName, $layerNames, $columns) {
    $combined_data = [];

    // Split layer names and columns by comma and strip whitespace
    $layerNames = array_map('trim', explode(',', $layerNames));
    $columns = array_filter(array_map('trim', explode(',', $columns)));

    // Loop through each layer name
    foreach ($layerNames as $layerName) {
        // Construct the WFS request URL for each layer
        $wfs_url = "$url/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=$workspaceName:$layerName&outputFormat=application/json";
        
        // Debugging: Print the constructed URL
        error_log("WFS URL for $layerName: $wfs_url");

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $wfs_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // Execute cURL request
        $response = curl_exec($ch);
        
        // Check for errors
        if (curl_errno($ch)) {
            echo json_encode(["error" => "Error fetching layer $layerName: " . curl_error($ch)]);
            curl_close($ch);
            return;
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        // Check if data was successfully parsed
        if (isset($data['features'])) {
            foreach ($data['features'] as $feature) {
                $feature_data = ['id' => $feature['id']];  // Start with the id
                
                if (!empty($columns)) {  // If specific columns are provided
                    foreach ($columns as $column) {
                        if (isset($feature['properties'][$column])) {
                            $feature_data[$column] = $feature['properties'][$column];
                        }
                    }
                } else {
                    // Add all properties if no specific columns
                    $feature_data = array_merge($feature_data, $feature['properties']);
                }

                // Append feature data to combined results
                $combined_data[] = $feature_data;
            }
        } else {
            error_log("No valid features found for layer $layerName.");
        }
    }

    return $combined_data;
}

// Fetch the request parameters
$url = isset($_GET['url']) ? $_GET['url'] : null;
$workspaceName = isset($_GET['workspaceName']) ? $_GET['workspaceName'] : null;
$layerNames = isset($_GET['layerNames']) ? $_GET['layerNames'] : null;
$columns = isset($_GET['columns']) ? $_GET['columns'] : '';

// Validate required parameters
if (!$url || !$workspaceName || !$layerNames) {
    echo json_encode(["error" => "Missing required parameters: 'url', 'workspaceName', or 'layerNames'."]);
    return;
}

// Get GeoServer data for the layers
$combined_data = getGeoServerData($url, $workspaceName, $layerNames, $columns);

// Return the combined data as JSON
echo json_encode($combined_data);
?>
