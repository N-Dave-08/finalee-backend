<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
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
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
      <h2 style="text-align: center; background-color: #00B300; color: white; padding: 10px; font-family: Arial, sans-serif;">
        Medical Request
      </h2>
    
      <?php if (isset($_GET['success'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 10px;">Request submitted successfully!</div>
      <?php elseif (isset($_GET['error'])): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 10px;">There was an error submitting your request. Please try again.</div>
      <?php endif; ?>
    
      <form method="POST" action="../actions/submit_medical_request.php" style="margin-bottom: 20px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px #eee;">
        <h3>Request Medical Document</h3>
        <label>Full Name:</label>
        <input type="text" name="full_name" required style="margin-bottom: 8px; width: 100%;"><br>
        <label>Contact Number:</label>
        <input type="text" name="contact_number" required style="margin-bottom: 8px; width: 100%;"><br>
        <label>Document Type:</label>
        <select name="document_type" required style="margin-bottom: 8px; width: 100%;">
          <option value="">Select</option>
          <option value="Medical Certificate">Medical Certificate</option>
          <option value="Check-up for Elders">Check-up for Elders</option>
          <option value="Sick Check-up">Sick Check-up</option>
          <option value="Pap Smear">Pap Smear</option>
          <option value="Kids Medication">Kids Medication</option>
          <option value="Prenatal">Prenatal</option>
          <option value="Immunization">Immunization</option>
          <option value="Pregnant Check-up">Pregnant Check-up</option>
        </select><br>
        <label>Date of Birth:</label>
        <input type="date" name="date_of_birth" required style="margin-bottom: 8px; width: 100%;"><br>
        <button type="submit" style="background: #00B300; color: #fff; padding: 8px 16px; border: none; border-radius: 4px;">Save</button>
      </form>
    
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
    
    <script src="assets/js/common.js"></script>
</body>
</html>
    