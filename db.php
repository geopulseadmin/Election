<?php
$host = "localhost";
$port = "5432";
$dbname = "mojani";
$user = "postgres";
$password = "123";

// // Establish a connection to the PostgreSQL database
// $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
// try {
//     $pdo = new PDO($dsn);
// } catch (PDOException $e) {
//     die("Connection failed: " . $e->getMessage());
// }


try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname;user=$user;password=$password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1); // Set auto-commit mode
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
