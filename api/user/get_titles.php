<?php
session_start();

// Security: only allow logged in users with 'user' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

// Connect to database
$servername = "localhost";
$username = "root";
$password_db = "root";
$dbname = "chatbot";

$conn = new mysqli($servername, $username, $password_db, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([]);
    exit;
}

// Assuming you have a table "option_trees" with column "title" storing top-level titles (like clothes, vehicles etc)
$sql = "SELECT DISTINCT title FROM option_trees"; // Adjust table/column names as needed

$result = $conn->query($sql);
$titles = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $titles[] = $row['title'];
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($titles);
