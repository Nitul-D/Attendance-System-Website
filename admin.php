<?php
require_once 'config.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header("Location: index.php");
    exit();
}

$interns = [];
$sql = "SELECT internid AS id, name FROM users WHERE role = 'user' AND approved = 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $interns[] = $row;
    }
}

$message = '';
if (isset($_SESSION['success'])) {
    $message = '<div class="alert success">'.$_SESSION['success'].' <span onclick="this.parentelement.style.display=\'none\'" style="cursor:pointer;float:right;">&times;</span> </div>';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $message = '<div class="alert error">'.$_SESSION['error'].' <span onclick="this.parentelement.style.display=\'none\'" style="cursor:pointer;float:right;">&times;</span> </div>';
    unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Daily Attendance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" href="static/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <?= $message ?>
    <div class="wrapper">
        <form action="attendance.php" method="POST">

            <div class="calendar-section">
                <label for="attendance-date">Select Date:</label>
                <input type="date" id="attendance-date" name="date" required>
            </div>

            <template id="status-select-template">
                <select name="status[]" class="attendance-select">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </template>

            <table>
                <thead>
                    <tr>
                        <th>Intern ID</th>
                        <th>Intern</th>
                        <th>Status</th>
                    </tr>
                </thead>
                    <tbody id="attendance-body">
                    </tbody>
            </table>

            <button type="submit" class="btn-save">Save Attendance</button>
        </form>

        <!--Script-->
        <script>
            const interns = <?php echo json_encode($interns); ?>;
        </script>
        <script src="script.js"></script>
        <script>
            const dateInput = document.getElementById('attendance-date');
            const todayStr = new Date().toISOString().split('T')[0];
            dateInput.value = todayStr;
        </script>
    </div>

</body>
</html>