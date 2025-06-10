<?php
require_once __DIR__ . '/../app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/request for new consultation.css" />
  <title>Barangay Clinic Appointment</title>
</head>
<body>
  <div class="container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <img src="assets/images/newimus.png" alt="Barangay Logo" class="logo" />
      <h2>BARANGAY CLINIC ONLINE APPOINTMENT SYSTEM</h2>
      <p>Imus, Cavite</p>
      <nav>
        <button onclick="navigateTo('home.php')">HOME</button>
        <button onclick="navigateTo('update-profile.php')">UPDATE PROFILE</button>
        <button onclick="navigateTo('request for new consultation.php')">REQUEST FOR NEW CONSULTATION</button>
        <button onclick="navigateTo('view-consultation.php')">VIEW CONSULTATION RESULT</button>
        <button onclick="navigateTo('appointments.php')">VIEW APPOINTMENTS</button>
        <button onclick="navigateTo('request medical document.php')">REQUEST MEDICAL DOCUMENTS</button>
        <button onclick="navigateTo('change-password.php')">CHANGE PASSWORD</button>
        <button onclick="navigateTo('broad-consent.php')">VIEW BROAD CONSENT</button>
        <button onclick="navigateTo('privacy-notice.php')">VIEW DATA PRIVACY NOTICE</button>
        <button onclick="logout('index.html')">LOGOUT</button>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
      <!-- Main Content -->
      <main class="main-content">
        <header>
          <h2>REQUEST FOR NEW CONSULTATION</h2>
        </header>
  
        <div class="consultation-box">
          <p><strong>Punan ang detalye ng request ng konsultasyon sa ibaba.</strong></p>
          <p>
            Para sa isang bagong konsultasyon, mangyaring mag click sa tab na 'General Concern'
            sa ibaba upang tingnan ang isang listahan ng mga pangunahing reklamo.
          </p>
          <p>
            Kung ang iyong mga sintomas ay nagpapahiwatig ng isang kondisyon na nangangailangan
            ng agarang pansin, isang malubhang isyu, o isang potensyal na banta sa iyong buhay,
            maaari naming inirerekomenda na bisitahin mo kaagad ang emergency room sa pinakamalapit na ospital.
          </p>
  
          <p><strong>Please fill out the request for consultation below.</strong></p>
          <p>
            For a new consultation, please click on the 'General Concern' tab below to view a list of major complaints.
          </p>
          <p>
            If your symptoms suggest a condition requiring immediate attention, a serious issue,
            or a potential threat to your life, we strongly recommend that you visit the emergency room at
            the nearest hospital immediately.
          </p>
  
          <button onclick="goToGeneralConcern('general concern.php')">General Concern</button>
        </div>
      </main>
    </div>
  
    <script src="assets/js/common.js"></script>
    <script>
       function navigateTo(page) {
      window.location.href = page;
    }

   function logout() {
  alert("Logging out...");
  localStorage.clear(); // Clear stored login info if using localStorage
  window.location.href = "index.html";
}


    function bookNow() {
      alert("Redirecting to booking page...");
      // Add booking logic here
    }
      function goToGeneralConcern() {
        window.location.href = "general concern.php"; // Change this to your actual page
      }
    </script>
  
  </body>
  </html>