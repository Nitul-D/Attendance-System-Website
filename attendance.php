<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $intern_ids = $_POST['intern_id'] ?? [];
    $statuses = $_POST['status'] ?? [];

    if (!empty($date) && is_array($intern_ids) && is_array($statuses) && count($intern_ids) === count($statuses)) {
         $stmt = $conn->prepare("REPLACE INTO attendance (intern_id, date, status) VALUES (?, ?, ?)");
         if ($stmt) {
            for ($i = 0; $i < count($intern_ids); $i++) {
                $intern_id = $intern_ids[$i];
                $status = $statuses[$i];
                $stmt->bind_param("sss", $intern_id, $date, $status);
                $stmt->execute();
            }

            $_SESSION['success'] = "Attendance saved successfully!";
        } else {
            $_SESSION['error'] = "Database prepare failed: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Invalid!!!";
    }
    header("Refresh: 0; URL=dashboard.php");
    exit();
}
?>
