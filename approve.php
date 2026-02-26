<?php
session_start();
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $conn->query("UPDATE users SET approved = 1 WHERE id = $user_id");
    $_SESSION['success'] = "User approved successfully!";
} else {
    $_SESSION['error'] = "Invalid user ID!";
}

header("Location: dashboard.php");
exit();
