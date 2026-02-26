<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$user = $conn->query("SELECT id, name, internid FROM users WHERE email = '$email' AND role = 'user' AND approved = 1")->fetch_assoc();

if (!$user) {
    echo "<h2 style='text-align:center;margin-top:100px;'>Access Denied. You are not approved </h2>";
    exit();
}

$user_id = $user['id'];
$intern_id = $user['internid'];

$selectedMonth = $_GET['month'] ?? date('Y-m'); //Current Month

$records = $conn->query("SELECT date, status FROM attendance WHERE intern_id = '$intern_id' AND DATE_FORMAT(date, '%Y-%m') = '$selectedMonth' ORDER BY date DESC");

$present = 0;
$absent = 0;
$weeklyData = [0, 0, 0, 0];
$weeklyAbsent = [0, 0, 0, 0];

$today = new DateTime();
foreach ($records as $rec) {
    $date = new DateTime($rec['date']);
    $day = (int) $date -> format('j');
    $week = min(intval(($day - 1) / 7), 3);

    if ($rec['status'] === 'Present') {
        $present++;
        $weeklyData[$week]++;
    } elseif ($rec['status'] === 'Absent') {
        $absent++;
        $weeklyAbsent[$week]++;
    }
}

$totalDays = $present + $absent;
$attendanceRate = $totalDays > 0 ? round(($present / $totalDays) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Intern Dashboard - IOCL</title>
  <link rel="icon" href="static/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
  <div class="dashboard-header">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>
    <a href = "logout.php" class = "logout-btn">Logout</a>
  </div>

  <form method = "GET" style="text-align:center; margin-bottom: -20px; padding: 10px 550px;">
    <label for = "month" style="color: #fff;">Filter by Month: </label>
    <input type = "month" id = "month" name = "month" value="<?= htmlspecialchars($selectedMonth) ?>">
    <button type = "submit" class = "btn-save" style = "max-width:80px; padding: 6px 10px; font-size: 15px;">Filter</button>
  </form>

  <div class="card-container">
    <div class="card">
      <h3>Attendance Rate</h3>
      <div class="value"><?= $attendanceRate ?>%</div>
      <p class="description"><?= date("F Y", strtotime($selectedMonth)) ?></p>
    </div>
    <div class="card">
      <h3>Days Present</h3>
      <div class="value"><?= $present ?> Days</div>
      <p class="description">Total</p>
    </div>
    <div class="card">
      <h3>Days Absent</h3>
      <div class="value"><?= $absent ?> Days</div>
      <p class="description">Total</p>
    </div>
  </div>

  <div class="chart-container">
    <canvas id="attendanceChart"></canvas>
  </div>

  <script>
    const weeklyData = <?= json_encode(array_reverse($weeklyData)) ?>;
    const weeklyAbsent = <?= json_encode(array_reverse($weeklyAbsent)) ?>;
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script src="script.js"></script>
</body>
</html>
