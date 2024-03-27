<?php
session_start(); // Start the session
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = bin2hex(random_bytes(32));
    $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, token_expiration = ? WHERE email = ?");
    $stmt->execute([$token, $expiration, $email]);

    $reset_link = "http://yourdomain.com/reset_password_page.php?token=$token";
    $reset_email_subject = "Password Reset";
    $reset_email_body = "Click the following link to reset your password: $reset_link";

    // mail($email, $reset_email_subject, $reset_email_body); // Uncomment this line when using mail

    // Store the token in the session
    $_SESSION['reset_token'] = $token;

    // Redirect to reset_password_page.php
    header("Location: reset_password_page.php");
    exit();
}
?>
