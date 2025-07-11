<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patients List</title>
  <link rel="stylesheet" href="/finalee/public/assets/css/patients.css" />
</head>
<body>
  <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>
    <div class="sidebar-edge-indicator" id="sidebarEdgeIndicator" style="display:none;"></div>
    <div class="main-content">
      <h2 class="section-title">Patients</h2>
      <div class="content-box">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
          <h3 style="margin: 0;">Patients List</h3>
          <button id="toggleArchivedBtn" class="btn toggle-archived-btn">Show Archived</button>
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

  <script src="/finalee/public/assets/js/patients.js"></script>
  <script src="/finalee/public/assets/js/common.js"></script>
  <script>
    function updateSidebarEdgeIndicator() {
      var sidebar = document.getElementById('sidebar');
      var edge = document.getElementById('sidebarEdgeIndicator');
      if (!sidebar || !edge) return;
      if (window.innerWidth <= 900 && !sidebar.classList.contains('open') && !sidebar.classList.contains('active')) {
        edge.style.display = 'block';
      } else {
        edge.style.display = 'none';
      }
    }
    document.addEventListener('DOMContentLoaded', function() {
      var sidebar = document.getElementById('sidebar');
      var edge = document.getElementById('sidebarEdgeIndicator');
      if (edge && sidebar) {
        edge.addEventListener('click', function(e) {
          sidebar.classList.add('open');
          edge.style.display = 'none';
        });
        window.addEventListener('resize', updateSidebarEdgeIndicator);
        updateSidebarEdgeIndicator();
        sidebar.addEventListener('transitionend', updateSidebarEdgeIndicator);
      }
    });
    document.addEventListener('click', function() { setTimeout(updateSidebarEdgeIndicator, 10); });
  </script>
</body>
</html>
