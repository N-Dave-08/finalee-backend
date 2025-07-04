<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/request med.css" />
  <title>Barangay Clinic Appointment</title>
</head>
<body>
  <!-- X Button -->
  <button class="close-btn" onclick="navigateTo('home.php')">×</button>

  <div class="container">
    <?php $activePage = 'request-medical-document.php'; include 'sidebar.php'; ?>

    <main class="content">
      <h2>REQUEST MEDICAL DOCUMENTS</h2>
      <div class="info-box">
        <p><strong>What to do?</strong><br>
        To request medical documents, fill out the online form or visit the clinic/hospital and provide your details along with the documents you need.</p>
        <p><strong>Ano ang gagawin?</strong><br>
        Para humiling ng mga medikal na dokumento, punan ang online form o pumunta sa klinika/ospital at ibigay ang inyong mga detalye at ang mga dokumentong kailangan mo.</p>
      </div>

      <form id="requestForm" method="POST" action="/finalee/public/actions/submit_medical_request.php">
        <div class="form-group">
          <label for="fullName">Full name:</label>
          <input type="text" id="fullName" name="fullName" required>
      
          <label for="contactNumber">Contact Number:</label>
          <input type="text" id="contactNumber" name="contactNumber" required>
        </div>
      
        <div class="form-group">
          <label for="documentType">Document to be requested:</label>
          <select id="documentType" name="documentType" required>
            <option value="">Select</option>
            <option value="Kids Medication">Kids Medication</option>
            <option value="Sick Check-up">Sick Check-up</option>
            <option value="Immunization">Immunization</option>
            <option value="Prenatal">Prenatal</option>
            <option value="Family Planning">Family Planning</option>
            <option value="Pregnant Check-up">Pregnant Check-up</option>
            <option value="Pap Smear">Pap Smear</option>
          </select>
        </div>
      
        <!-- Improved date selection with a calendar -->
        <div class="form-group date-group">
          <label for="birthDate">Date of Birth:</label>
          <input type="date" id="birthDate" name="birthDate" required>
        </div>
      
        <button type="submit" class="save-btn">Save</button>
      </form>
      
      <script>
        function navigateTo(page) {
          window.location.href = page;
        }
      </script>
      
      <script src="assets/js/common.js"></script>
    </main>
  </div>
</body>
</html>
