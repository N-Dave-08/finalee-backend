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
  <style>
    /* Modal Styles */
    #cancelModal {
      position: fixed;
      top: 0; left: 0; width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.4);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      opacity: 0;
      transition: opacity 0.3s;
    }
    #cancelModal.show { display: flex; opacity: 1; }
    #cancelModal .modal-content {
      background: #fff;
      padding: 32px 40px 24px 40px;
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.18);
      max-width: 90vw;
      min-width: 320px;
      text-align: center;
      position: relative;
      animation: popIn 0.3s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes popIn {
      0% { transform: scale(0.8); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
    #cancelModal h3 { margin-top: 0; color: #e74c3c; }
    #cancelModal .modal-actions { margin-top: 24px; display: flex; justify-content: center; gap: 16px; }
    #cancelModal button {
      padding: 10px 24px;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.2s;
    }
    #confirmCancelBtn { background: #e74c3c; color: #fff; }
    #confirmCancelBtn:hover { background: #c0392b; }
    #closeCancelBtn { background: #eee; color: #333; }
    #closeCancelBtn:hover { background: #ccc; }
    /* Toast Styles */
    #toast {
      position: fixed;
      top: 32px;
      right: 32px;
      min-width: 220px;
      background: #fff;
      color: #333;
      border-radius: 8px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.13);
      padding: 18px 32px 18px 56px;
      font-size: 1rem;
      z-index: 10000;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.4s, transform 0.4s;
      transform: translateY(-30px);
      display: flex;
      align-items: center;
      gap: 12px;
    }
    #toast.show {
      opacity: 1;
      pointer-events: auto;
      transform: translateY(0);
    }
    #toast.success::before {
      content: '';
      display: inline-block;
      width: 28px; height: 28px;
      background: url('data:image/svg+xml;utf8,<svg fill="none" viewBox="0 0 24 24" stroke="%232ecc71" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke="%232ecc71" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 12l2 2l4-4"/></svg>') no-repeat center/contain;
      margin-right: 8px;
    }
    #toast.error::before {
      content: '';
      display: inline-block;
      width: 28px; height: 28px;
      background: url('data:image/svg+xml;utf8,<svg fill="none" viewBox="0 0 24 24" stroke="%23e74c3c" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke="%23e74c3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6m0-6l6 6"/></svg>') no-repeat center/contain;
      margin-right: 8px;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php $activePage = 'view-consultation.php'; include 'sidebar.php'; ?>

    <div class="main-content">
      <div class="page-header-flex">
        <button id="sidebar-toggle" class="sidebar-hamburger" aria-label="Open sidebar">
          <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
          <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
          <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
        </button>
        <h2 style="margin:0;">VIEW CONSULTATION RESULT</h2>
      </div>
    <div class="description-box">
      <p><strong>Pending Request Consultation</strong> refers to requests or consultations that are still awaiting action, decision, or resolution, while <strong>Past Request Consultation</strong> refers to those that have already been completed or resolved.</p>
      <p>Ang <strong>Pending Request Consultation</strong> ay tumutukoy sa mga kahilingan o konsultasyon na naghihintay pa ng aksyon, desisyon, o resolusyon, habang ang <strong>Past Request Consultation</strong> ay tumutukoy sa mga kahilingan o konsultasyon na natapos na o nararesolba na.</p>
    </div>

    <div class="tab-buttons">
      <button class="active" onclick="showTab('pending')">Pending</button>
      <button onclick="showTab('past')">Past Request</button>
    </div>

    <div id="pending" class="tab-content active">
      <div class="table-responsive">
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
    </div>

    <div id="past" class="tab-content">
      <div class="table-responsive">
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
$sql = "SELECT id, complaint, preferred_date, time_slot, status FROM consultations WHERE user_id = ? AND (status = 'Completed' OR status = 'Canceled' OR status = 'Scheduled') ORDER BY created_at DESC";
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

    // Modal for confirmation
    function showCancelModal(consultationId) {
      let modal = document.getElementById('cancelModal');
      if (!modal) {
        modal = document.createElement('div');
        modal.id = 'cancelModal';
        modal.innerHTML = `
          <div class="modal-content">
            <h3>Cancel Consultation</h3>
            <p>Are you sure you want to cancel this appointment?</p>
            <div class="modal-actions">
              <button id="confirmCancelBtn">Yes, Cancel</button>
              <button id="closeCancelBtn">No</button>
            </div>
          </div>
        `;
        document.body.appendChild(modal);
      }
      modal.classList.add('show');
      document.getElementById('confirmCancelBtn').onclick = function() {
        cancelConsultation(consultationId);
        modal.classList.remove('show');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
      };
      document.getElementById('closeCancelBtn').onclick = function() {
        modal.classList.remove('show');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
      };
      modal.style.display = 'flex';
      setTimeout(() => { modal.classList.add('show'); }, 10);
    }

    // Toast message
    function showToast(message, type = 'success') {
      let toast = document.getElementById('toast');
      if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        document.body.appendChild(toast);
      }
      toast.className = type + ' show';
      toast.textContent = message;
      setTimeout(() => {
        toast.classList.remove('show');
      }, 2200);
    }

    function cancelConsultation(consultationId) {
      fetch('/finalee/public/actions/cancel-consultation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: consultationId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast('Consultation canceled successfully.', 'success');
          setTimeout(() => { window.location.reload(); }, 1200);
        } else {
          showToast(data.message || 'Failed to cancel consultation.', 'error');
        }
      })
      .catch(() => {
        showToast('An error occurred. Please try again.', 'error');
      });
    }

    function cancelAppointment(button) {
      const row = button.closest('tr');
      const consultRef = row.querySelector('td').innerText;
      // Extract consultation id from reference number (format: #ID-MMDDYYYY)
      const idMatch = consultRef.match(/^#(\d+)-/);
      if (idMatch) {
        showCancelModal(idMatch[1]);
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

