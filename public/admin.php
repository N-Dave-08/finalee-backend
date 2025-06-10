<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/admin.css" />
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <img src="assets/images/newimus.png" alt="Barangay Logo" class="logo" />
      <h2>BARANGAY CLINIC<br>ONLINE APPOINTMENT SYSTEM</h2>
      <p>Imus, Cavite</p>
      <nav>
        <button onclick="location.href='admin.html'">Dashboard</button>
        <button onclick="location.href='appointmentss.html'">Appointments</button>
        <button onclick="location.href='appointmentss.html'">Consultation</button>
        <button onclick="location.href='patients.html'">Patients</button>
        <button onclick="location.href='medical-request.html'">View Medical Request</button>
        <hr />
        <button onclick="logout('index.html')">LOGOUT</button>
      </nav>
    </aside>

    <main class="main-content">
      <h1>Dashboard</h1>
      <h2>Choose Dashboard</h2>

      <div class="charts-grid">
        <div class="chart" onclick="location.href='chart1.html'">Total Appointments Throughout the Day (Line Chart)</div>
        <div class="chart" onclick="location.href='chart2.html'">Total Appointments (Weekdays Only) (Line Chart)</div>
        <div class="chart" onclick="location.href='chart3.html'">Total Appointment in a Week (Bar Chart)</div>
        <div class="chart" onclick="location.href='chart4.html'">Total Appointment in a Month (Bar Chart)</div>
      </div>

      <div class="summary-box" onclick="location.href='patients.html'">
        <h3>Total No. of Patients</h3>
        <div class="summary-values">
          <div>
            <p>Today</p>
            <h2>26</h2>
          </div>
          <div>
            <p>in a Week</p>
            <h2>128</h2>
          </div>
          <div>
            <p>in a Month</p>
            <h2>560</h2>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    function logout() {
  alert("Logging out...");
  localStorage.clear(); // Clear stored login info if using localStorage
  window.location.href = "index.html";
}

  </script>
</body>
</html>

