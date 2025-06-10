<?php
require_once __DIR__ . '/../app/helpers/auth.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Patients List</title>
  <link rel="stylesheet" href="assets/css/patients.css" />
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
          <img src="assets/images/newimus.png" alt="Barangay Logo" class="logo" />
          <h2>BARANGAY CLINIC<br>ONLINE APPOINTMENT SYSTEM</h2>
          <p>Imus, Cavite</p>
          <nav>
            <button onclick="location.href='admin.php'">Dashboard</button>
            <button onclick="location.href='appointmentss.php'">Appointments</button>
            <button onclick="location.href='appointmentss.php'">Consultation</button>
            <button onclick="location.href='patients.php'">Patients</button>
            <button onclick="location.href='medical-request.php'">View Medical Request</button>
            <hr />
            <button onclick="location.href='index.html'">LOGOUT</button>
          </nav>
        </aside>
  
        <div class="main-content">
    <h2 class="section-title">Patients</h2>
    <div class="content-box">
      <div class="header">
        <h3>Patients List</h3>
      </div>
      <div class="search-container">
        <input type="text" id="searchInput" placeholder="🔍 Search" />
      </div>
      <div class="patient-list" id="patientList">
        <!-- JS will insert patients here -->
      </div>
    </div>
  </div>

  <script src="assets/js/patients.js"></script>
</body>
</html>
