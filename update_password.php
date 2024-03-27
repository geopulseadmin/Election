<?php
include('db.php'); // Include your PostgreSQL connection code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Update the user's password and clear the reset token
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ?");
    $stmt->execute([$password, $token]);

    echo "Password updated successfully.";
}
?>
