<?php
if (!isset($_GET['title'])) {
    http_response_code(400);
    echo "Missing title.";
    exit;
}

$title = $_GET['title'];
$conn = new mysqli('localhost', 'root', 'root', 'chatbot');

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}

$stmt = $conn->prepare("SELECT tree_data FROM option_trees WHERE title = ?");
$stmt->bind_param("s", $title);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    header('Content-Type: application/json');
    echo $row['tree_data'];
} else {
    echo json_encode([]);
}
