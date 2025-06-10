<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Archived Appointments</title>
  <link rel="stylesheet" href="assets/css/appointmentss.css" />
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <img src="assets/images/newimus.png" alt="Barangay Logo" class="logo" />
      <h2>BARANGAY CLINIC<br>ONLINE APPOINTMENT SYSTEM</h2>
      <p>Imus, Cavite</p>
      <nav>
        <button onclick="location.href='admin.php'">Dashboard</button>
        <button onclick="location.href='appointmentss.php'">Appointments</button>
        <button onclick="location.href='consultation.php'">Consultation</button>
        <button onclick="location.href='patients.php'">Patients</button>
        <button onclick="location.href='medical-request.php'">View Medical Request</button>
        <hr />
        <button onclick="logout()">LOGOUT</button>
      </nav>
    </aside>
  
    <!-- Main Content -->
    <div class="main-content">
      <h1>Archived Appointments</h1>
      <hr>

      <div class="content-container">
        <h2 class="page-title">Archived Appointments</h2>

        <div class="top-buttons">
          <div class="date-sorting">
            <label for="date">Date Sorting</label>
            <input type="date" id="date">
            <button type="button" onclick="submitDate()">Submit</button>
          </div>

          <div class="action-buttons">
            <button class="add-btn" onclick="location.href='add-appointment.php'">Add Appointment</button>
            <button class="archive-btn" onclick="location.href='appointmentss.php'">Back to Appointments</button>
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
              <tr>
                <td>Jayson Stark</td>
                <td>#02-01062025</td>
                <td>Sick Check-up</td>
                <td>Completed</td>
              </tr>
              <tr>
                <td>Old Patient 1</td>
                <td>#01-01012024</td>
                <td>Check-up for Elders</td>
                <td>Expired</td>
              </tr>
              <tr>
                <td>Old Patient 2</td>
                <td>#02-15022024</td>
                <td>Prenatal</td>
                <td>Expired</td>
              </tr>
              <tr>
                <td>Old Patient 3</td>
                <td>#03-01032024</td>
                <td>Immunization</td>
                <td>Completed</td>
              </tr>
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
