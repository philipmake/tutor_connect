<?php
// Database configuration
$host     = "localhost";  
$dbname   = "tutor_connect"; 
$username = "root";       
$password = "";       

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set PDO error mode to Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // If connection fails, stop and show error
    die("Database connection failed: " . $e->getMessage());
}
?>
