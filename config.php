<?php 
//host
$host =  "localhost";
//database name
$dbname = "Login";
//user & pass
$user = "root";
$pass = "";

// Connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>