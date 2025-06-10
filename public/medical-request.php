<?php
require_once __DIR__ . '/../app/helpers/auth.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/med.css" />
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
        <button onclick="location.href='index.html'">LOGOUT</button>
      </nav>
    </aside>

    <div class="main-content">
      <h2 style="text-align: center; background-color: #00B300; color: white; padding: 10px; font-family: Arial, sans-serif;">
        Medical Request
      </h2>
    
      <div style="background-color: #D0F5D8; padding: 20px; border-radius: 10px; font-family: Arial, sans-serif;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
          <div>
            <label for="dateSort">Date Sorting</label>
            <input type="text" id="dateSort" placeholder="DD/MM/YY" />
            <button>Submit</button>
          </div>
          <button style="padding: 5px 10px;">Archive</button>
        </div>
    
        <table border="1" width="100%" style="border-collapse: collapse; text-align: center;">
          <thead style="background-color: #2E8B57; color: white;">
            <tr>
              <th>Name &#x2195;</th>
              <th>Ref #. Date &#x2195;</th>
              <th>Request &#x2195;</th>
              <th>Status &#x2195;</th>
            </tr>
          </thead>
          <tbody style="background-color: #C6FBC6;">
            <tr><td>Vince Lee</td><td>#01-01062025</td><td>Check-up for Elders</td><td>Pending</td></tr>
            <tr><td>Jayson Stark</td><td>#02-01062025</td><td>Sick Check-up</td><td>Completed</td></tr>
            <tr><td>Michael Jackstar</td><td>#03-01062025</td><td>Sick Check-up</td><td>Pick-up</td></tr>
            <tr><td>Nick Felids</td><td>#04-01062025</td><td>Sick Check-up</td><td>Pending</td></tr>
            <tr><td>Camille Sprats</td><td>#01-01072025</td><td>Sick Check-up</td><td>Pending</td></tr>
            <tr><td>Guinevere Tan</td><td>#02-01072025</td><td>Sick Check-up</td><td>Pending</td></tr>
            <tr><td>Nolan Gord</td><td>#03-01072025</td><td>Sick Check-up</td><td>Pending</td></tr>
            <tr><td>Tony Foul</td><td>#01-01082025</td><td>Pap Smear</td><td>Pending</td></tr>
            <tr><td>Chloe Cal</td><td>#02-01082025</td><td>Kids Medication</td><td>Pending</td></tr>
            <tr><td>Kazz Smith</td><td>#03-01082025</td><td>Prenatal</td><td>Pending</td></tr>
            <tr><td>Hey Ali</td><td>#04-01082025</td><td>Immunization</td><td>Pending</td></tr>
            <tr><td>Malu Peeton</td><td>#01-01082025</td><td>Pregnant Check-up</td><td>Pending</td></tr>
            <tr><td>Isma Glass</td><td>#06-01082025</td><td>Immunization</td><td>Pending</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    