<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
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
    <?php include 'sidebar.php'; ?>
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
  </div>

  <script src="assets/js/patients.js"></script>
  <script src="assets/js/common.js"></script>
</body>
</html>
