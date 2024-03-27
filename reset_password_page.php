
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geopulse</title>
    <!-- Add your CSS link below -->
    <link rel="stylesheet" href="style.css">

</head>
<body>


<?php
session_start();
include('db.php'); // Include your PostgreSQL connection code
date_default_timezone_set('UTC');
// $token = $_GET["token"];
$token = isset($_SESSION['reset_token']) ? $_SESSION['reset_token'] : $_GET["token"];


$error_message = ''; // Initialize the error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST["password"];

    // Validate the new password (add your own validation rules)
   // Validate the new password (add your own validation rules)
   if (strlen($newPassword) < 8) {
    $error_message = "Password must be at least 8 characters long.";
} else {

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password and clear the reset token
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ?");
    $stmt->execute([$hashedPassword, $token]);

    echo "Password updated successfully.";

    unset($_SESSION['reset_token']); // Clear the session variable after updating the password
    // Debugging information
    echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";
    echo "Token Expiration: {$user['token_expiration']}<br>";

    
// Add this line to debug the expiration comparison result
echo "Expiration Comparison Result: " . ($user['token_expiration'] > $current_time ? 'Valid' : 'Expired') . "<br>";
    echo "Token: $token<br>";
    exit();

}
}
// echo "Token: $token<br>";

$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
// echo "SQL Query: " . $stmt->queryString . "<br>"; // Debugging line
$stmt->execute([$token]);

// Check for errors
$errorInfo = $stmt->errorInfo();
if ($errorInfo[0] !== '00000') {
    die("Error: " . $errorInfo[2]);
}
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// Debugging information
// echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";
// echo "Token Expiration: " . ($user ? $user['token_expiration'] : "Not available") . "<br>";
// echo "Token: $token<br>";

// Check if the user is found based on the token
if ($user !== false) {
    // Display the reset password form
    echo '<div class="container">';
    echo '<div class="right-section">';
    echo '<div class="login-container">';
    echo '<form class="login-form" action="reset_password_page.php" method="post">';
    echo '<h2 class="login-title"> Reset Password</h2>';
    if (!empty($error_message)) {
        echo '<p style="color: red;">' . $error_message . '</p>';
    }
    echo '<div class="form-control">';
    echo ' <i class="fas fa-lock icon"></i>';
    echo '<input type="password" name="password" placeholder="New Password" required>';
    echo '</div>';
    echo '<button type="submit">Update Password</button>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

} else {
    echo "Invalid or expired token.";
}

?>
</body>
</html>
