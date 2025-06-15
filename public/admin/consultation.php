<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/appointmentss.css" />
</head>
<body>
  <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>
  
    <!-- Main Content -->
    <div class="main-content">
      <h1>Appointment</h1>
      <hr>

      <div class="content-container">
        <h2 class="page-title">Appointment</h2>

        <div class="top-buttons">
          <div class="date-sorting">
            <label for="date">Date Sorting</label>
            <input type="date" id="date">
            <button type="button" onclick="submitDate()">Submit</button>
          </div>

          <div class="action-buttons">
            <button class="add-btn" onclick="location.href='add-appointment.php'">Add Appointment</button>
            <button class="archive-btn" onclick="location.href='archive-appointmentss.php'">Archive</button>
          </div>
        </div>

        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Ref #. Date</th>
                <th>Complaint</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
<?php
$conn = get_db_connection();
$sql = "SELECT id, full_name, complaint, status, preferred_date FROM consultations WHERE status != 'Completed' ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $ref = '#' . str_pad($row['id'], 2, '0', STR_PAD_LEFT) . '-' . date('mdY', strtotime($row['preferred_date']));
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
    echo '<td>' . htmlspecialchars($ref) . '</td>';
    echo '<td>' . htmlspecialchars($row['complaint']) . '</td>';
    echo '<td class="status-cell">' . htmlspecialchars($row['status']) . '</td>';
    echo '<td>';
    if ($row['status'] === 'Pending') {
      echo '<button class="complete-btn" data-id="' . $row['id'] . '">Mark as Completed</button>';
    }
    echo '</td>';
    echo '</tr>';
  }
} else {
  echo '<tr><td colspan="4">No consultations found.</td></tr>';
}
$conn->close();
?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <script src="assets/js/common.js"></script>
  <style>
    .modal-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }
    .modal-box {
      background: #fff;
      padding: 24px 32px;
      border-radius: 10px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.2);
      text-align: center;
      min-width: 300px;
    }
    .modal-box button {
      margin: 0 10px;
      padding: 8px 18px;
      border-radius: 6px;
      border: none;
      font-size: 15px;
      cursor: pointer;
    }
    .modal-confirm {
      background: #43a047;
      color: #fff;
    }
    .modal-cancel {
      background: #ccc;
      color: #222;
    }
  </style>
  <div id="confirmModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
      <div style="margin-bottom:18px;">Are you sure you want to mark this consultation as <b>Completed</b>?</div>
      <button class="modal-confirm" id="modalConfirmBtn">Yes</button>
      <button class="modal-cancel" id="modalCancelBtn">Cancel</button>
    </div>
  </div>
  <script>
    function submitDate() {
      const selectedDate = document.getElementById('date').value;
      if (selectedDate) {
        alert('You selected: ' + selectedDate);
        
      } else {
        alert('Please select a date first.');
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      let pendingBtn = null;
      const modal = document.getElementById('confirmModal');
      const confirmBtn = document.getElementById('modalConfirmBtn');
      const cancelBtn = document.getElementById('modalCancelBtn');
      document.querySelectorAll('.complete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          pendingBtn = this;
          modal.style.display = 'flex';
        });
      });
      confirmBtn.addEventListener('click', function() {
        if (!pendingBtn) return;
        const id = pendingBtn.dataset.id;
        fetch('actions/update-consultation-status.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id, status: 'Completed' })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const row = pendingBtn.closest('tr');
            row.querySelector('.status-cell').innerText = 'Completed';
            pendingBtn.remove();
          } else {
            alert('Failed to update status');
          }
          modal.style.display = 'none';
          pendingBtn = null;
        });
      });
      cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        pendingBtn = null;
      });
    });
  </script>
</body>
</html>