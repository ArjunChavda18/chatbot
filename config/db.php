<?php
$host = 'localhost';
$db   = 'chatbot';  // 🔁 change if your DB name is different
$user = 'root';
$pass = 'root';            // 🔁 use your MySQL password (empty in XAMPP)

$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Try to create a new PDO connection
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // If the connection is successful, return a success message
} catch (\PDOException $e) {
    // If the connection fails, return an error message
    die(json_encode([
        'status' => 'error',
        'message' => 'DB Connection Failed: ' . $e->getMessage()
    ]));
}
?>
