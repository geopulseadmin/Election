<?php

// //live databse
// $host = "157.173.222.9";
// $port = "5432";
// $dbname = "Mojani_new";
// $user = "postgres";
// $password = "Mojani@992101";


// test database
$host = "157.173.222.9";
$port = "5432";
$dbname = "mojani";
$user = "postgres";
$password = "Mojani@992101";


try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}
?>
