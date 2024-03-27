<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful, set session variable and redirect
        $_SESSION['login_status'] = "success";
        header("Location: index.php"); // Change to your actual dashboard page
        exit();
    } else {
        // Login failed, set session variable and redirect
        $_SESSION['login_status'] = "failed";
        $_SESSION['error_message'] = "Invalid email or password. Please try again.";
        header("Location: login.php"); // Change to your actual login page
        exit();
    }
}
?>
