<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Appointment</title>
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
      <h1>Appointment</h1>
      <hr>

      <div class="content-container">
        <h2 class="page-title">Appointment</h2>

        <div class="form-container" style="background-color: #b7f7b2; padding: 20px; border-radius: 10px;">
          
            <div style="position: relative; background-color: #eaffea; padding: 20px; border-radius: 15px;">

                <!-- X Button to exit -->
                <button onclick="window.location.href='appointmentss.php';" style="
                  position: absolute;
                  top: 10px;
                  right: 10px;
                  background: transparent;
                  border: none;
                  font-size: 28px;
                  color: #4CAF50;
                  cursor: pointer;
                ">
                  &times;
                </button>
              
                <!-- Patient Name -->
                <div style="margin-bottom: 20px;">
                  <button style="
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 20px;
                    border: none;
                    font-weight: bold;
                    cursor: default;
                  ">Chloe Tan</button>
                </div>
              
                <!-- Document Section -->
                <div style="width: 250px; margin-bottom: 20px;">
                  <label for="document" style="font-weight: bold;">Document to be requested</label><br><br>
                  <select id="document" style="
                    width: 100%;
                    height: 45px;
                    padding: 10px;
                    border-radius: 10px;
                    border: 2px solid #4CAF50;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                    appearance: none;
                    background-image: url('data:image/svg+xml;utf8,<svg fill=\'%234CAF50\' height=\'24\' viewBox=\'0 0 24 24\' width=\'24\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M7 10l5 5 5-5z\'/></svg>');
                    background-repeat: no-repeat;
                    background-position: right 10px center;
                    background-size: 24px;
                  ">
                    <option disabled selected>-- Select Document --</option>
                    <option>Kids Medication</option>
                    <option>Sick Check-up</option>
                    <option>Immunization</option>
                    <option>Prenatal</option>
                    <option>Family Planning</option>
                    <option>Pregnant Check-up</option>
                    <option>Pap Smear</option>
                  </select>
                </div>
              
                <!-- Date Section -->
                <div style="margin-bottom: 20px;">
                  <label for="date" style="font-weight: bold;">Date</label><br><br>
                  <input type="date" id="date" style="
                    padding: 10px;
                    border: 2px solid #4CAF50;
                    border-radius: 10px;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                    width: 200px;
                  ">
                </div>
              
                <!-- Remarks Section -->
                <div style="margin-bottom: 20px;">
                  <label for="remarks" style="font-weight: bold;">Remarks</label><br><br>
                  <textarea id="remarks" rows="4" style="
                    width: 250px;
                    padding: 10px;
                    border-radius: 10px;
                    border: 2px solid #4CAF50;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                  "></textarea>
                </div>
              
                <!-- Confirm Button -->
                <div>
                  <button onclick="submitAppointment()" style="
                    background-color: #FFEB3B;
                    color: black;
                    font-weight: bold;
                    border: none;
                    border-radius: 30px;
                    padding: 10px 30px;
                    font-size: 18px;
                    cursor: pointer;
                  ">
                    CONFIRM!
                  </button>
                </div>
              
              </div>
              
              <script>
                function submitAppointment() {
                  // After confirming, redirect back to appointments page
                  alert("Appointment successfully added!"); 
                  window.location.href = "appointmentss.php";
                }
              </script>
              
</body>
</html>
<script src="assets/js/common.js"></script>
