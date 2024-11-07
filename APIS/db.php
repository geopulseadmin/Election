

<?php


$host = "157.173.222.9";
$port = "5432";
$dbname = "mojani";
$user = "postgres";
$password = "Mojani@992101";


try {
    // Establish a connection to the database
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Connected successfully!";
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}
?>
