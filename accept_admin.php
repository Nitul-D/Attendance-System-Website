<?php
session_start();
require_once 'config.php';

$admins = $conn->query("SELECT * FROM users WHERE role='admin' AND approved = 0");
?>

<h2>Approve New Admins</h2>
<table>
    <tr>
        <th> Intern ID </th>
        <th> Name </th>
        <th> Email </th>
        <th> Action </th>
    </tr>

    <?php while ($admin = $admins->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($admin['internid']) ?></td>
            <td><?= htmlspecialchars($admin['name']) ?></td>
            <td><?= htmlspecialchars($admin['email']) ?></td>
            <td>
                <form method = "POST" action="approve.php">
                    <input type="hidden" name="user_id" value="<?= $admin['id'] ?>">
                    <button type="submit" name="approve">Approve</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>