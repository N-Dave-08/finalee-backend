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
  <link rel="stylesheet" href="/finalee/public/assets/css/admin.css" />
</head>
<body>
  <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>
    <div class="sidebar-edge-indicator" id="sidebarEdgeIndicator" style="display:none;"></div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

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
  <script src="/finalee/public/assets/js/admin.js"></script>
  <script src="/finalee/public/assets/js/common.js"></script>
  <script>
    // Show the green edge indicator only on mobile
    function updateSidebarEdgeIndicator() {
      var sidebar = document.getElementById('sidebar');
      var edge = document.getElementById('sidebarEdgeIndicator');
      var overlay = document.getElementById('sidebarOverlay');
      if (!sidebar || !edge || !overlay) return;
      if (window.innerWidth <= 900 && !sidebar.classList.contains('open') && !sidebar.classList.contains('active')) {
        edge.style.display = 'block';
        overlay.style.display = 'none';
      } else {
        edge.style.display = 'none';
      }
    }
    document.addEventListener('DOMContentLoaded', function() {
      var sidebar = document.getElementById('sidebar');
      var edge = document.getElementById('sidebarEdgeIndicator');
      var overlay = document.getElementById('sidebarOverlay');
      if (edge && sidebar) {
        edge.addEventListener('click', function(e) {
          sidebar.classList.add('open');
          edge.style.display = 'none';
          overlay.style.display = 'block';
        });
        window.addEventListener('resize', updateSidebarEdgeIndicator);
        updateSidebarEdgeIndicator();
        // Also hide edge when sidebar is opened by other means
        sidebar.addEventListener('transitionend', updateSidebarEdgeIndicator);
      }
      if (overlay && sidebar) {
        overlay.addEventListener('click', function() {
          sidebar.classList.remove('open');
          sidebar.classList.remove('active');
          overlay.style.display = 'none';
          setTimeout(updateSidebarEdgeIndicator, 300);
        });
      }
    });
    // Also update on sidebar open/close
    document.addEventListener('click', function() { setTimeout(updateSidebarEdgeIndicator, 10); });
  </script>
</body>
</html>

