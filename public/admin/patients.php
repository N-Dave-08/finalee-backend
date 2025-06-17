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
          <button id="toggleArchivedBtn" class="btn toggle-archived-btn" style="float:right;margin-top:-8px;">Show Archived</button>
        </div>
        <div class="search-container">
          <input type="text" id="searchInput" placeholder="🔍 Search" />
        </div>
        <div id="patientLoading" style="display:none; text-align:center; margin:20px 0;">
          <span class="loader"></span> Loading patients...
        </div>
        <div class="patient-list" id="patientList">
          <!-- JS will insert patients here -->
        </div>
        <div class="patient-list" id="archivedPatientList" style="display:none;">
          <!-- JS will insert archived patients here -->
        </div>
      </div>
    </div>
  </div>

  <div id="profileModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100vw; height: 100vh; overflow: auto; background: rgba(0,0,0,0.4);">
    <div class="modal-content" style="background: #fff; margin: 10% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 400px; position: relative;">
      <span class="close" id="closeModalBtn" style="position: absolute; right: 16px; top: 8px; font-size: 28px; cursor: pointer;">&times;</span>
      <h2>Patient Profile</h2>
      <div id="modalDetails"></div>
    </div>
  </div>

  <script src="assets/js/patients.js"></script>
  <script src="assets/js/common.js"></script>
</body>
</html>
