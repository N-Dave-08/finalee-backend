<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/view consultation.css" />
  <title>Barangay Clinic Appointment</title>
</head>
<body>
  <div class="container">
    <?php $activePage = 'view-consultation.php'; include 'sidebar.php'; ?>

  <div class="main">
    <h2>VIEW CONSULTATION RESULT</h2>
    <div class="description-box">
      <p><strong>Pending Request Consultation</strong> refers to requests or consultations that are still awaiting action, decision, or resolution, while <strong>Past Request Consultation</strong> refers to those that have already been completed or resolved.</p>
      <p>Ang <strong>Pending Request Consultation</strong> ay tumutukoy sa mga kahilingan o konsultasyon na naghihintay pa ng aksyon, desisyon, o resolusyon, habang ang <strong>Past Request Consultation</strong> ay tumutukoy sa mga kahilingan o konsultasyon na natapos na o nararesolba na.</p>
    </div>

    <div class="tab-buttons">
      <button class="active" onclick="showTab('pending')">Pending</button>
      <button onclick="showTab('past')">Past Request</button>
    </div>

    <div id="pending" class="tab-content active">
      <table>
        <thead>
          <tr>
            <th>Consult Reference Number</th>
            <th>Main Complaint</th>
            <th>Date of Complaint</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="pending-table-body">
          <tr>
            <td>#03-011324345</td>
            <td>Pregnant Check up</td>
            <td>February 11, 2025</td>
            <td>9:20-9:40 AM</td>
            <td>Pending</td>
            <td><button onclick="cancelAppointment(this)">Cancel</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div id="past" class="tab-content">
      <table>
        <thead>
          <tr>
            <th>Consult Reference Number</th>
            <th>Main Complaint</th>
            <th>Date of Complaint</th>
            <th>Time</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>#02-01152025</td>
            <td>Immunization</td>
            <td>January 15, 2025</td>
            <td>8:20-8:40 AM</td>
            <td>Completed</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="assets/js/common.js"></script>
  <script>
    function showTab(tabId) {
      const buttons = document.querySelectorAll('.tab-buttons button');
      const tabs = document.querySelectorAll('.tab-content');
      buttons.forEach(btn => btn.classList.remove('active'));
      tabs.forEach(tab => tab.classList.remove('active'));
      document.getElementById(tabId).classList.add('active');
      if(tabId === 'pending') buttons[0].classList.add('active');
      else buttons[1].classList.add('active');
    }

    function navigateTo(page) {
      window.location.href = page;
    }

    function bookNow() {
      alert("Redirecting to booking page...");
    }

    function goToGeneralConcern() {
      window.location.href = "general-concern.php";
    }

    function cancelAppointment(button) {
      const confirmCancel = confirm("Are you sure you want to cancel this appointment?");
      if (confirmCancel) {
        const row = button.closest("tr");
        row.remove();
        alert("Appointment canceled successfully.");
      }
    }

    // Highlight active sidebar button
    document.addEventListener('DOMContentLoaded', function() {
      const buttons = document.querySelectorAll('.sidebar nav button');
      const currentPage = window.location.pathname.split('/').pop();
      buttons.forEach(btn => {
        const match = btn.getAttribute('onclick')?.match(/navigateTo\('([^']+)'\)/);
        if (match && match[1] === currentPage) {
          btn.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>

