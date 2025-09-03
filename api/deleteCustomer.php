<?php
require '../config/db.php'; // Adjust path as needed

if (!isset($_GET['id'])) {
    header('Location: ../../pages/forms/manageCustomer.php');
    exit;
}

$id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect with success message (using session or query param)
    header('Location: ../../pages/forms/manageCustomer.php?success=Customer deleted successfully');
    exit;
} catch (PDOException $e) {
    // Handle error, e.g. redirect with error message
    header('Location: ../../pages/forms/manageCustomer.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
