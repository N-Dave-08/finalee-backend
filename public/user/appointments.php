<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/appointment.css" />
  <title>Barangay Clinic Appointment</title>
</head>
<body>
  <div class="container">
    <?php $activePage = 'appointments.php'; include 'sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="main">
      <h2>REQUEST MEDICAL DOCUMENTS</h2>

      <div class="info-box">
        <p>Pumili ng dokumentong nais i-download o i-print.<br><i>Select a document you want to download or print.</i></p>
      </div>

      <div class="tab-buttons">
        <button class="active" onclick="showTab('ready')">Ready for Release</button>
        <button onclick="showTab('history')">Request History</button>
      </div>

      <div id="ready" class="tab-content active">
        <table>
          <thead>
            <tr>
              <th>Document Reference Number</th>
              <th>Document Type</th>
              <th>Date Requested</th>
              <th>Status</th>
              <th>Options</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#DOC-001</td>
              <td>Medical Certificate</td>
              <td>April 25, 2025</td>
              <td>Ready</td>
              <td>
                <button onclick="viewDocument()">View</button>
                <button onclick="printDocument()">Print</button>
                <button onclick="downloadDocument()">Download</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div id="history" class="tab-content">
        <table>
          <thead>
            <tr>
              <th>Document Reference Number</th>
              <th>Document Type</th>
              <th>Date Requested</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#DOC-0005</td>
              <td>Consultation Summary</td>
              <td>March 15, 2025</td>
              <td>Completed</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Hidden printable document (for printing only) -->
  <div id="printable-document" style="display:none;">
    <h1>Medical Certificate</h1>
    <p>Patient Name: Juan Dela Cruz</p>
    <p>Date Issued: April 25, 2025</p>
    <p>This is to certify that the patient has undergone medical consultation.</p>
  </div>

  <!-- JAVASCRIPT FUNCTIONS -->
  <script>
    function showTab(tabId) {
      const buttons = document.querySelectorAll('.tab-buttons button');
      const tabs = document.querySelectorAll('.tab-content');
      buttons.forEach(btn => btn.classList.remove('active'));
      tabs.forEach(tab => tab.classList.remove('active'));
      document.getElementById(tabId).classList.add('active');
      if (tabId === 'ready') buttons[0].classList.add('active');
      else buttons[1].classList.add('active');
    }

    function viewDocument() {
      alert("Viewing the document...");
    }
      

    function printDocument() {
      const printable = document.getElementById('printable-document');
      printable.style.display = 'block'; // Show the printable content
      window.print();
      printable.style.display = 'none';  // Hide again after printing
    }

    function downloadDocument() {
      const link = document.createElement('a');
      link.href = 'path_to_your_document.pdf'; // actual file path
      link.download = 'Medical_Document.pdf';
      link.click();
    }

    function navigateTo(page) {
      window.location.href = page;
    }


    function bookNow() {
      alert("Redirecting to booking page...");
      // Add booking logic here
    }

    function goToGeneralConcern() {
      window.location.href = "general-concern.php"
    }
  </script>
  <script src="assets/js/common.js"></script>
</body>
</html>
