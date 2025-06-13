<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('user');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';
$user_id = $_SESSION['user']['id'];
$conn = get_db_connection();
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
<?php
$sql = "SELECT id, complaint, preferred_date, time_slot, status FROM consultations WHERE user_id = ? AND status = 'Pending' ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $ref = '#' . str_pad($row['id'], 2, '0', STR_PAD_LEFT) . '-' . date('mdY', strtotime($row['preferred_date']));
    echo '<tr>';
    echo '<td>' . htmlspecialchars($ref) . '</td>';
    echo '<td>' . htmlspecialchars($row['complaint']) . '</td>';
    echo '<td>' . htmlspecialchars(date('F d, Y', strtotime($row['preferred_date']))) . '</td>';
    echo '<td>' . htmlspecialchars($row['time_slot']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
    echo '<td><button onclick="cancelAppointment(this)">Cancel</button></td>';
    echo '</tr>';
  }
} else {
  echo '<tr><td colspan="6">No pending consultations found.</td></tr>';
}
$stmt->close();
?>
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
<?php
$sql = "SELECT id, complaint, preferred_date, time_slot, status FROM consultations WHERE user_id = ? AND status = 'Completed' ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $ref = '#' . str_pad($row['id'], 2, '0', STR_PAD_LEFT) . '-' . date('mdY', strtotime($row['preferred_date']));
    echo '<tr>';
    echo '<td>' . htmlspecialchars($ref) . '</td>';
    echo '<td>' . htmlspecialchars($row['complaint']) . '</td>';
    echo '<td>' . htmlspecialchars(date('F d, Y', strtotime($row['preferred_date']))) . '</td>';
    echo '<td>' . htmlspecialchars($row['time_slot']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
    echo '</tr>';
  }
} else {
  echo '<tr><td colspan="5">No past consultations found.</td></tr>';
}
$stmt->close();
$conn->close();
?>
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

