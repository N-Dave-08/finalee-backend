<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('user');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';
require_once dirname(__DIR__, 2) . '/config/config.php';

$user_id = $_SESSION['user']['id'];
$conn = get_db_connection();

// Fetch all appointments for the user
$appointments = [];
$sql = "SELECT * FROM appointments WHERE user_id = ? ORDER BY preferred_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$appt_result = $stmt->get_result();
while ($row = $appt_result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();

// Fetch all medical documents for the user
$docs = [];
$sql = "SELECT * FROM medical_documents_requests WHERE user_id = ? ORDER BY date_requested DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$doc_result = $stmt->get_result();
while ($row = $doc_result->fetch_assoc()) {
    $docs[] = $row;
}
$stmt->close();
$conn->close();
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
      <h2>Appointments</h2>

      <div class="info-box">
        <p>Pumili ng dokumentong nais i-download o i-print.<br><i>Select a document you want to download or print.</i></p>
      </div>

      <div class="tab-buttons">
        <button class="active" onclick="showTab('ready')">Ready for Release</button>
        <button onclick="showTab('history')">Request History</button>
      </div>

      <div id="ready" class="tab-content active">
        <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Reference #</th>
              <th>Complaint</th>
              <th>Date</th>
              <th>Time Slot</th>
              <th>Status</th>
              <th>Document</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appointments as $appt): ?>
              <tr>
                <td><?= '#APPT-' . str_pad($appt['id'], 3, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($appt['complaint']) ?></td>
                <td><?= date('F d, Y', strtotime($appt['preferred_date'])) ?></td>
                <td><?= htmlspecialchars($appt['time_slot']) ?></td>
                <td><?= htmlspecialchars($appt['status']) ?></td>
                <td>
                  <a href="<?= $baseUrl ?>/public/user/view_appointment_doc.php?id=<?= $appt['id'] ?>" target="_blank" class="view-doc-btn">View</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        </div>
      </div>

      <div id="history" class="tab-content">
        <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Document Reference Number</th>
              <th>Document Type</th>
              <th>Date Requested</th>
              <th>Status</th>
              <th>File</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($docs) > 0): ?>
              <?php foreach ($docs as $doc): ?>
                <tr>
                  <td><?= htmlspecialchars($doc['reference_number'] ?? ('#DOC-' . str_pad($doc['id'], 3, '0', STR_PAD_LEFT))) ?></td>
                  <td><?= htmlspecialchars($doc['document_type']) ?></td>
                  <td><?= isset($doc['date_requested']) ? date('F d, Y', strtotime($doc['date_requested'])) : '' ?></td>
                  <td>
                    <?php
                      if ($doc['status'] === 'Pending' && !empty($doc['file_path'])) {
                        echo 'For Pick Up';
                      } else {
                        echo htmlspecialchars($doc['status']);
                      }
                    ?>
                  </td>
                  <td>
                    <?php if (!empty($doc['file_path'])): ?>
                      <a href="<?= $baseUrl ?>/uploads/medical_docs/<?= htmlspecialchars($doc['file_path']) ?>" target="_blank">View/Download</a>
                    <?php else: ?>
                      N/A
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5">No document requests found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
        </div>
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

    function viewDocument(referenceNumber) {
      alert("Viewing the document...");
    }
      

    function printDocument(referenceNumber) {
      const printable = document.getElementById('printable-document');
      printable.style.display = 'block'; // Show the printable content
      window.print();
      printable.style.display = 'none';  // Hide again after printing
    }

    function downloadDocument(referenceNumber) {
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
