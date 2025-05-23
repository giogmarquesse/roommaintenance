<?php
$host = 'localhost'; // Your MySQL host
$db = 'student_information_system'; // Your database name
$user = 'root'; // MySQL username
$pass = ''; // MySQL password (leave empty if not set)

try {
    // Establish the connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
