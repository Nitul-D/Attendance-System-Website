<?php
session_start();
require_once 'config.php';// contains $conn


if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header("Location: index.php");
    exit();
}

// Check if 'id' is set and is a number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

   if ($stmt->execute()) {
        $_SESSION['success'] = "Intern removed successfully.";
    } else {
        $_SESSION['error'] = "Error removing intern: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid intern ID.";
}

$conn->close();
header("Refresh: 0; URL=dashboard.php");
exit();
?>