<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $occupation = $_POST["occupation"];
    $contact_no = $_POST["contact_no"];

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, occupation, contact_no) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $occupation, $contact_no]);
    // Redirect to index.php after successful registration
    header("Location: login.php");
    exit();
}
?>
