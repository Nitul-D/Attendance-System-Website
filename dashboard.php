<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header ("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$checkApproval = $conn->query("SELECT approved FROM users WHERE email = '$email'");

if ($checkApproval && $row = $checkApproval->fetch_assoc()) {
    if ($row['approved'] != 1) {
        // Not approved
        echo "<h2 style='text-align:center;margin-top:100px;'>Access Denied! Your account is awaiting for admin approval </h2>";
        exit();
    }
} else {
    // No such user found
    session_destroy();
    header("Location: index.php");
    exit();
}

$pendingInterns = $conn->query("SELECT * FROM users WHERE role = 'user' AND approved = 0");
$interns = $conn->query("SELECT *  FROM users WHERE role = 'user' AND approved = 1");
$pendingAdmins = $conn->query("SELECT * FROM users WHERE role = 'admin' AND approved = 0");

$message = '';
if (isset($_SESSION['success'])) {
    $message = '<div class = "alert success">' .$_SESSION['success'] .' <span onclick="this.parentElement.style.display=\'none\'" style="cursor:pointer;float:right;">&times;</span></div>';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $message = '<div class = "alert error">' .$_SESSION['error'] .' <span onclick="this.parentElement.style.display=\'none\'" style="cursor:pointer;float:right;">&times;</span></div>';
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" href="static/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?= $message ?>
    <header class="l2-header">
            <div class = "nav-right">
                <?php if (isset($_SESSION['name'])): ?>
                    <span class = "welcome-text"><strong><?= htmlspecialchars($_SESSION['name']) ?></strong></span>
                <?php endif; ?>
                </div>
    </header>
    <div class = "dashboard">
        <aside class = "sidebar">
            <h2> Admin Panel </h2>
            <ul>
                <li onclick = "showSection('register')"><i class = "fa fa-user-plus"></i> Register Intern </li>
                <li onclick = "showSection('manage')"><i class = "fa fa-users"></i> Manage Intern </li>
                <li onclick = "showSection('attendance')"><i class = "fa fa-calendar-check"></i> Mark Attendance </li>
                <li onclick = "showSection('admin')"><i class = "fa fa-user-shield"></i> Accept Admin </li>
                <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout </a></li>
            </ul>
        </aside>

        <main class = "main-content">
            <section id = "register" class="content-section">
                <h2>Pending Intern Approvals</h2>
                <table>
                    <tr>
                        <th> Intern ID </th>
                        <th> Name </th>
                        <th> Email </th>
                        <th> Action </th>
                    </tr>

                    <?php while ($row = $pendingInterns->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['internid'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td>
                                <a href="approve.php?id=<?= $row['id'] ?>&type=user" class="btn-approve"> Approve </a>
                                <a href="reject.php?id=<?= $row['id'] ?>" class="btn-reject"> Reject </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </section>

            <section id="manage" class="content-section" style="display: none;">
                <h2> Current Interns </h2>
                <table>
                    <tr>
                        <th> Intern ID </th>
                        <th> Name </th>
                        <th> Email </th>
                        <th> Action </th>
                    </tr>

                    <?php while ($row = $interns->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['internid'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td>
                                <a href="remove_user.php?id=<?= $row['id'] ?>" class="btn-reject"> Remove </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </section>

            <section id="attendance" class="content-section" style="display: none;">
                <?php include 'admin.php'; ?>
            </section>

            <section id="admin" class="content-section" style="display: none;">
                <h2> Pending Admin Approvals </h2>
                <table>
                    <tr>
                        <th> Name </th>
                        <th> Email </th>
                        <th> Action </th>
                    </tr>

                    <?php while ($row = $pendingAdmins->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td>
                                <a href="approve.php?id=<?= $row['id'] ?>&type=admin" class="btn-approve"> Approve </a>
                                <a href="reject.php?id=<?= $row['id'] ?>" class="btn-reject"> Reject </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </section>
        </main>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(sec => sec.style.display = 'none');
            document.getElementById(sectionId).style.display = 'block';
        }
    </script>

</body>
</html>



