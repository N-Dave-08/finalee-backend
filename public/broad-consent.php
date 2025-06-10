<?php
require_once __DIR__ . '/../app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/broad-consent.css" />
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
        <button onclick="logout()">LOGOUT</button>
      </nav>
    </aside>

  <div class="content">
    <h1>VIEW BROAD CONSENT</h1>
    <div class="section">
      <strong>Purpose of Consent:</strong>
      You are being asked to provide your consent to participate in and use our online clinic appointment system. This system is designed to facilitate scheduling, communication, and the management of your healthcare needs. By providing broad consent, you are agreeing to allow your personal and health-related information to be used for the purposes of providing medical care, scheduling appointments, and improving our services. Additionally, your de-identified data may be used for research and analysis aimed at enhancing healthcare delivery.
    </div>
    <div class="section">
      <strong>What Participation Involves:</strong>
      By consenting, you agree to provide personal information such as your name, contact details, medical history, and other relevant information necessary for the provision of healthcare services. This data will be stored securely and may be accessed by authorized healthcare providers and administrative staff within our system.
    </div>
    <div class="section">
      <strong>Voluntary Participation:</strong>
      Your use of this system is entirely voluntary. You may choose not to use the system or to withdraw your consent at any time without affecting your access to medical care or services.
    </div>
    <div class="section">
      <strong>Potential Risks:</strong>
      The potential risks of participating in this system include the possibility of data breaches or unauthorized access to your information. We implement industry-standard security measures to minimize these risks and protect your data.
    </div>
    <div class="section">
      <strong>Potential Benefits:</strong>
      Using the online clinic appointment system provides convenient access to scheduling, reduces waiting times, and enhances communication with healthcare providers. Additionally, your participation may contribute to improvements in healthcare delivery.
    </div>
    <div class="section">
      <strong>Confidentiality:</strong>
      We are committed to protecting your privacy. Your personal information will be stored securely and will only be accessible to authorized personnel. Identifiable data will not be shared with third parties without your explicit consent, except as required by law.
    </div>
    <div class="section">
      <strong>Data Sharing and Future Use:</strong>
      By providing consent, you agree that your de-identified data may be used for quality improvement, research, and statistical analysis. This information will help us better understand healthcare needs and improve our services. Any future use of your data will comply with applicable laws and regulations.
    </div>
    <div class="section">
      <strong>Participant Statement:</strong>
      I have read and understand the information provided above. I voluntarily agree to participate in the online clinic appointment system and consent to the use of my data as outlined.
    </div>
  </div>
  <script src="assets/js/common.js"></script>
  <script>
    function navigateTo(page) {
      window.location.href = page;
    }

    function bookNow() {
      alert("Redirecting to booking page...");
      // booking logic here
    }
  </script>
</body>
</html>
