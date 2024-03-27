<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST Data: " . print_r($_POST, true) . "<br>";
    echo "Files Data: " . print_r($_FILES, true) . "<br>";

    $district = $_POST["input1"];
    $taluka = $_POST["input2"];
    $village = $_POST["input3"];
    $surveyNumber = $_POST["input4"];

    // Handle multiple file uploads
    $surveyMapPaths = uploadFiles('surveyMapFilePath', 'uploads/');
    $villageMapPaths = uploadFiles('villageMapFilePath', 'uploads/');
    $pdf712Paths = uploadFiles('pdf7_12FilePath', 'uploads/');
    
    if (!empty($surveyMapPaths)) {
        // Insert data into the database
        $stmt = $pdo->prepare("INSERT INTO form_data (district, taluka, village, survey_number, survey_map_path, village_map_path, pdf_7_12_path) VALUES (?, ?, ?, ?, ?, ?, ?)");

        try {
            foreach ($surveyMapPaths as $key => $surveyMapPath) {
                $villageMapPath = $villageMapPaths[$key];
                $pdf712Path = $pdf712Paths[$key];
                $stmt->execute([$district, $taluka, $village, $surveyNumber, $surveyMapPath, $villageMapPath, $pdf712Path]);
            }
            echo("success");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            // Log the error to a file or use your preferred logging method
        }
    } else {
        echo "No files uploaded.";
    }

    exit();
}
function uploadFiles($fileInputName, $uploadDir) {
    $uploadedFiles = array();

    if (isset($_FILES[$fileInputName]['name']) && is_array($_FILES[$fileInputName]['name'])) {
        foreach ($_FILES[$fileInputName]['name'] as $key => $filename) {
            $tmpFilePath = $_FILES[$fileInputName]['tmp_name'][$key];
            $error = $_FILES[$fileInputName]['error'][$key];

            if ($error != UPLOAD_ERR_OK) {
                echo "File upload error: $error";
                continue; // Skip this file and proceed with the next one
            }

            $newFilePath = $uploadDir . $filename;

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $uploadedFiles[] = $newFilePath;
            } else {
                echo "Error moving file to destination. Debug info: " . print_r(error_get_last(), true);
            }
        }
    }

    return $uploadedFiles;
}
?>
