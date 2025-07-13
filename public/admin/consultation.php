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
  <link rel="stylesheet" href="/finalee/public/assets/css/appointmentss.css" />
</head>
<body>
  <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>
    <div class="sidebar-edge-indicator" id="sidebarEdgeIndicator" style="display:none;"></div>
  
    <!-- Main Content -->
    <div class="main-content">
      <h1>Consultations</h1>
      <hr>

      <div class="content-container">
        <h2 class="page-title">Consultations</h2>

        <div class="top-buttons">
          <div class="date-and-actions">
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
$sql = "SELECT id, full_name, complaint, status, preferred_date, time_slot FROM consultations WHERE status != 'Completed' ORDER BY created_at DESC";
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
      echo '<button class="set-appointment-btn enhanced-btn" 
        data-id="' . $row['id'] . '" 
        data-name="' . htmlspecialchars($row['full_name']) . '" 
        data-complaint="' . htmlspecialchars($row['complaint']) . '"
        data-preferred_date="' . htmlspecialchars($row['preferred_date']) . '"
        data-time_slot="' . htmlspecialchars($row['time_slot']) . '"><span style="margin-right:6px;">📅</span>Set Appointment</button> ';
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

  <script src="/finalee/public/assets/js/common.js"></script>
  <style>
    .modal-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0,0,0,0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 2000;
    }
    .modal-box {
      background: #fff;
      padding: 28px 20px 20px 20px;
      border-radius: 18px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.18);
      width: 100%;
      max-width: 420px;
      box-sizing: border-box;
      position: relative;
      animation: modalPop .25s cubic-bezier(.4,2,.6,1) 1;
      margin: auto;
    }
    @keyframes modalPop {
      0% { transform: scale(0.95); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
    .modal-title {
      font-size: 1.4rem;
      font-weight: 700;
      margin-bottom: 18px;
      color: #222;
      text-align: center;
    }
    .modal-close {
      position: absolute;
      top: 12px;
      right: 16px;
      background: none;
      border: none;
      font-size: 1.7rem;
      color: #888;
      cursor: pointer;
      transition: color 0.2s;
    }
    .modal-close:hover {
      color: #c0392b;
    }
    .modal-box label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
      color: #222;
    }
    .modal-box input[type="text"],
    .modal-box input[type="date"],
    .modal-box select {
      width: 100%;
      padding: 9px 11px;
      margin-bottom: 18px;
      border: 1px solid #bdbdbd;
      border-radius: 7px;
      font-size: 15px;
      background: #f8f8f8;
      transition: border 0.2s;
    }
    .modal-box input[type="text"]:focus,
    .modal-box input[type="date"]:focus,
    .modal-box select:focus {
      border: 1.5px solid #43a047;
      outline: none;
    }
    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 10px;
    }
    .modal-box button[type="submit"] {
      background: #43a047;
      color: #fff;
      border: none;
      border-radius: 7px;
      padding: 10px 24px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s;
    }
    .modal-box button[type="submit"]:hover {
      background: #388e3c;
    }
    .modal-box button[type="button"] {
      background: #e0e0e0;
      color: #333;
      border: none;
      border-radius: 7px;
      padding: 10px 20px;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .modal-box button[type="button"]:hover {
      background: #bdbdbd;
    }
    @media (max-width: 500px) {
      .modal-box {
        width: 96vw;
        max-width: 98vw;
        padding: 12px 2vw 12px 2vw;
      }
    }
    .enhanced-btn {
      background: #43a047;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 8px 18px;
      font-size: 15px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s, box-shadow 0.2s;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .enhanced-btn:hover {
      background: #388e3c;
      box-shadow: 0 4px 16px rgba(67,160,71,0.15);
    }
  </style>
  <div id="confirmModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
      <div style="margin-bottom:18px;">Are you sure you want to mark this consultation as <b>Completed</b>?</div>
      <button class="modal-confirm" id="modalConfirmBtn">Yes</button>
      <button class="modal-cancel" id="modalCancelBtn">Cancel</button>
    </div>
  </div>
  <div id="setAppointmentModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
      <button type="button" class="modal-close" onclick="document.getElementById('setAppointmentModal').style.display='none'">&times;</button>
      <div class="modal-title">Set Appointment</div>
      <form id="setAppointmentForm" method="POST" action="admin/set_appointment.php">
        <input type="hidden" name="consultation_id" id="consultation_id">
        <div>
          <label>Full Name:</label>
          <input type="text" id="modal_full_name" disabled>
        </div>
        <div>
          <label>Complaint:</label>
          <input type="text" id="modal_complaint" disabled>
        </div>
        <div>
          <label>Preferred Date (User):</label>
          <small id="modal_preferred_date" style="display:block; margin-bottom:8px; color:#555; font-size:13px;"></small>
        </div>
        <div>
          <label>Preferred Time Slot (User):</label>
          <small id="modal_user_time_slot" style="display:block; margin-bottom:18px; color:#555; font-size:13px;"></small>
        </div>
        <div>
          <label>Appointment Date:</label>
          <input type="date" name="preferred_date" required>
        </div>
        <div>
          <label>Priority:</label>
          <select name="priority" id="modal_priority">
            <option value="">-- Select if applicable --</option>
            <option value="None">None</option>
            <option value="Elderly">Senior Citizen</option>
            <option value="PWD">Person with Disability (PWD)</option>
            <option value="Pregnant">Pregnant Woman</option>
          </select>
        </div>
        <div>
          <label>Time Slot:</label>
          <select name="time_slot" id="modal_time_slot" required>
            <!-- Options will be populated by JS -->
          </select>
        </div>
        <div class="modal-actions">
          <button type="submit">Set Appointment</button>
          <button type="button" onclick="document.getElementById('setAppointmentModal').style.display='none'">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  <div id="toast" style="display:none; position:fixed; bottom:30px; left:50%; transform:translateX(-50%); background:#333; color:#fff; padding:12px 24px; border-radius:6px; z-index:3000; font-size:16px; min-width:200px; text-align:center;"></div>
  <script>
    function submitDate() {
      const selectedDate = document.getElementById('date').value;
      if (selectedDate) {
        alert('You selected: ' + selectedDate);
        
      } else {
        alert('Please select a date first.');
      }
    }

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
      document.querySelectorAll('.set-appointment-btn').forEach(btn => {
        btn.onclick = function() {
          document.getElementById('consultation_id').value = this.dataset.id;
          document.getElementById('modal_full_name').value = this.dataset.name;
          document.getElementById('modal_complaint').value = this.dataset.complaint;
          document.getElementById('modal_preferred_date').textContent = this.dataset.preferred_date || '';
          document.getElementById('modal_user_time_slot').textContent = this.dataset.time_slot || '';
          document.getElementById('setAppointmentModal').style.display = 'block';
          document.getElementById('modal_priority').value = '';
          updateTimeSlotOptions();
        }
      });

      const regularSlots = [
        "08:00 – 08:20 AM", "08:20 – 08:40 AM", "08:40 – 09:00 AM",
        "09:00 – 09:20 AM", "09:20 – 09:40 AM", "09:40 – 10:00 AM",
        "10:00 – 10:20 AM", "10:20 – 10:40 AM", "10:40 – 11:00 AM",
        "11:00 – 11:20 AM", "11:20 – 11:40 AM", "11:40 – 12:00 PM",
        // Lunch break: 12:00 PM – 01:00 PM (no slots)
        "01:00 – 01:20 PM", "01:20 – 01:40 PM", "01:40 – 02:00 PM",
        "02:00 – 02:20 PM", "02:20 – 02:40 PM", "02:40 – 03:00 PM",
        "03:00 – 03:20 PM", "03:20 – 03:40 PM", "03:40 – 04:00 PM",
        "04:00 – 04:20 PM", "04:20 – 04:40 PM", "04:40 – 05:00 PM"
      ];
      const prioritySlots = [
        "08:00 – 08:20 AM", "08:20 – 08:40 AM", "08:40 – 09:00 AM",
        "09:00 – 09:20 AM", "09:20 – 09:40 AM", "09:40 – 10:00 AM",
        "10:00 – 10:20 AM", "10:20 – 10:40 AM", "10:40 – 11:00 AM",
        "11:00 – 11:20 AM", "11:20 – 11:40 AM", "11:40 – 12:00 PM",
        // Lunch break: 12:00 PM – 01:00 PM (no slots)
        "01:00 – 01:20 PM", "01:20 – 01:40 PM", "01:40 – 02:00 PM",
        "02:00 – 02:20 PM", "02:20 – 02:40 PM", "02:40 – 03:00 PM",
        "03:00 – 03:20 PM", "03:20 – 03:40 PM", "03:40 – 04:00 PM",
        "04:00 – 04:20 PM", "04:20 – 04:40 PM", "04:40 – 05:00 PM"
      ];
      function updateTimeSlotOptions() {
        const priority = document.getElementById('modal_priority').value;
        const select = document.getElementById('modal_time_slot');
        select.innerHTML = '';
        const slots = (priority && priority !== 'None') ? prioritySlots : regularSlots;
        slots.forEach(slot => {
          const opt = document.createElement('option');
          opt.value = slot;
          opt.textContent = slot;
          select.appendChild(opt);
        });
      }
      document.getElementById('modal_priority').addEventListener('change', updateTimeSlotOptions);

      document.getElementById('setAppointmentForm').onsubmit = function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        fetch('admin/set_appointment.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          showToast(data.message, data.success ? 'success' : 'error');
          if (data.success) {
            document.getElementById('setAppointmentModal').style.display = 'none';
            setTimeout(() => window.location.reload(), 1200);
          }
        })
        .catch(() => {
          showToast('An error occurred.', 'error');
        });
      };
      function showToast(msg, type) {
        const toast = document.getElementById('toast');
        toast.textContent = msg;
        toast.style.background = type === 'success' ? '#43a047' : '#c0392b';
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 2000);
      }

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