<?php
require_once __DIR__ . '/../app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Profile</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <!-- Update Profile Section -->
  <div id="updateProfileSection">
    <div class="top-bar">
      <button class="close-btn" onclick="window.location.href='home.php'">X</button>
    </div>

    <div class="content">
      <h1>Update Profile</h1>
      <form id="profileForm">
        <div class="input-group">
          <label for="lastName">Last Name:</label>
          <input type="text" id="lastName" name="lastName" required />
        </div>
        <div class="input-group">
          <label for="firstName">First Name:</label>
          <input type="text" id="firstName" name="firstName" required />
        </div>
        <div class="input-group">
          <label for="middleName">Middle Name:</label>
          <input type="text" id="middleName" name="middleName" />
        </div>
        <div class="input-group">
          <label for="dob">Date of Birth:</label>
          <input type="date" id="dob" name="dob" required />
        </div>
        <div class="input-group">
          <label for="placeOfBirth">Place of Birth:</label>
          <input type="text" id="placeOfBirth" name="placeOfBirth" />
        </div>
        <div class="input-group">
          <label for="gender">Gender:</label>
          <select id="gender" name="gender" required>
            <option value="">Select</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>
        <div class="input-group">
          <label for="email">Email Address:</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="input-group">
          <label for="contactNumber">Contact Number:</label>
          <input type="tel" id="contactNumber" name="contactNumber" />
        </div>

        <h2>Contact of Emergency</h2>
        <div class="input-group">
          <label for="emergencyLastName">Last Name:</label>
          <input type="text" id="emergencyLastName" name="emergencyLastName" required />
        </div>
        <div class="input-group">
          <label for="emergencyFirstName">First Name:</label>
          <input type="text" id="emergencyFirstName" name="emergencyFirstName" required />
        </div>
        <div class="input-group">
          <label for="emergencyMiddleName">Middle Name:</label>
          <input type="text" id="emergencyMiddleName" name="emergencyMiddleName" />
        </div>
        <div class="input-group">
          <label for="relationship">Relationship:</label>
          <input type="text" id="relationship" name="relationship" required />
        </div>
        <div class="input-group">
          <label for="emergencyContactNumber">Contact Number:</label>
          <input type="tel" id="emergencyContactNumber" name="emergencyContactNumber" />
        </div>
        <div class="input-group">
          <label for="emergencyEmail">Email Address:</label>
          <input type="email" id="emergencyEmail" name="emergencyEmail" />
        </div>

        <button type="submit" class="save-button">Save</button>
      </form>
    </div>
  </div>

  <script src="assets/js/script.js"></script>
</body>
</html>
