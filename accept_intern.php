<?php
session_start();
require_once 'config.php';

$interns = $conn->query("SELECT * FROM users WHERE role='user' AND approved = 0");
?>

<h2>Approve New Interns</h2>
<table>
    <tr>
        <th> Intern ID </th>
        <th> Name </th>
        <th> Email </th>
        <th> Action </th>
    </tr>

    <?php while ($intern = $interns->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($intern['internid']) ?></td>
            <td><?= htmlspecialchars($intern['name']) ?></td>
            <td><?= htmlspecialchars($intern['email']) ?></td>
            <td>
                <form method = "POST" action="approve.php">
                    <input type="hidden" name="user_id" value="<?= $intern['id'] ?>">
                    <button type="submit" name="approve">Approve</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>