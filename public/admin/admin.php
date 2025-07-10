<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
require_once dirname(__DIR__, 2) . '/app/models/UserModel.php';
$userModel = new UserModel();
$totalPatientsToday = $userModel->countUsersToday('user');
$totalPatientsWeek = $userModel->countUsersThisWeek('user');
$totalPatientsMonth = $userModel->countUsersThisMonth('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body>
  <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
      <h1>Dashboard</h1>
      <h2>Choose Dashboard</h2>

      <div class="charts-grid">
        <div class="chart">
          <canvas id="chart1"></canvas>
          <div class="chart-title">Total Appointments Throughout the Day (Line Chart)</div>
        </div>
        <div class="chart">
          <canvas id="chart2"></canvas>
          <div class="chart-title">Total Appointments (Weekdays Only) (Line Chart)</div>
        </div>
        <div class="chart">
          <canvas id="chart3"></canvas>
          <div class="chart-title">Total Appointment in a Week (Bar Chart)</div>
        </div>
        <div class="chart">
          <canvas id="chart4"></canvas>
          <div class="chart-title">Total Appointment in a Month (Bar Chart)</div>
        </div>
      </div>

      <div class="summary-box" onclick="location.href='patients.html'">
        <h3>Total No. of Patients</h3>
        <div class="summary-values">
          <div>
            <p>Today</p>
            <h2><?= htmlspecialchars($totalPatientsToday) ?></h2>
          </div>
          <div>
            <p>in a Week</p>
            <h2><?= htmlspecialchars($totalPatientsWeek) ?></h2>
          </div>
          <div>
            <p>in a Month</p>
            <h2><?= htmlspecialchars($totalPatientsMonth) ?></h2>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../assets/js/admin.js"></script>
  <script src="../assets/js/common.js"></script>
</body>
</html>

