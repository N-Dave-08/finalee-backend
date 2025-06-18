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
              </tr>
            </thead>
            <tbody>
<?php
$conn = get_db_connection();
$sql = "SELECT id, full_name, complaint, status, preferred_date FROM appointments ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $ref = '#' . str_pad($row['id'], 2, '0', STR_PAD_LEFT) . '-' . date('mdY', strtotime($row['preferred_date']));
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
    echo '<td>' . htmlspecialchars($ref) . '</td>';
    echo '<td>' . htmlspecialchars($row['complaint']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
    echo '</tr>';
  }
} else {
  echo '<tr><td colspan="4">No appointments found.</td></tr>';
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
  <script>
    function submitDate() {
      const selectedDate = document.getElementById('date').value;
      if (selectedDate) {
        alert('You selected: ' + selectedDate);
       
      } else {
        alert('Please select a date first.');
      }
    }
  </script>
</body>
</html>