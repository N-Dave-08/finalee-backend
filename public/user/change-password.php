<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Change Password</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 40px;
      background-color: #f4f4f4;
      position: relative;
    }

    .exit-icon {
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 24px;
      text-decoration: none;
      color: black;
      cursor: pointer;
    }

    .content {
      max-width: 500px;
      margin: 80px auto 0 auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .content h1 {
      font-size: 24px;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .password-field {
      display: flex;
      align-items: center;
    }

    .password-field input[type="password"],
    .password-field input[type="text"] {
      flex-grow: 1;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .toggle-password {
      margin-left: 10px;
      cursor: pointer;
      font-size: 12px;
      color: #333;
    }

    .submit-btn {
      background-color: #40916c;
      color: white;
      border: none;
      padding: 10px 20px;
      width: 100%;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .submit-btn:hover {
      background-color: #2d6a4f;
    }

    /* Modal Style */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 20px 30px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .modal-content h2 {
      margin: 0;
      font-size: 20px;
      color: #40916c;
    }
  </style>
</head>
<body>

  <div class="container">
    <?php $activePage = 'change-password.php'; include 'sidebar.php'; ?>

    <a href="home.php" class="exit-icon">✖</a>

    <div class="content">
      <h1>CHANGE PASSWORD</h1>
      <form id="changePasswordForm">
        <div class="form-group">
          <label for="currentPassword">Current Password</label>
          <div class="password-field">
            <input type="password" id="currentPassword" name="currentPassword" required>
            <span class="toggle-password" onclick="togglePassword('currentPassword')">Show</span>
          </div>
        </div>
        <div class="form-group">
          <label for="newPassword">New Password</label>
          <div class="password-field">
            <input type="password" id="newPassword" name="newPassword" required>
            <span class="toggle-password" onclick="togglePassword('newPassword')">Show</span>
          </div>
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirm New Password</label>
          <div class="password-field">
            <input type="password" id="confirmPassword" name="confirmPassword" required>
            <span class="toggle-password" onclick="togglePassword('confirmPassword')">Show</span>
          </div>
        </div>
        <button type="submit" class="submit-btn">Submit</button>
      </form>
    </div>

    <!-- Modal -->
    <div id="successModal" class="modal">
      <div class="modal-content">
        <h2>Password Successfully Changed!</h2>
      </div>
    </div>
  </div>

  <script src="assets/js/common.js"></script>
  <script>
    function togglePassword(fieldId) {
      const input = document.getElementById(fieldId);
      const toggle = input.nextElementSibling;
      if (input.type === 'password') {
        input.type = 'text';
        toggle.textContent = 'Hide';
      } else {
        input.type = 'password';
        toggle.textContent = 'Show';
      }
    }

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const current = document.getElementById('currentPassword').value;
      const newPass = document.getElementById('newPassword').value;
      const confirm = document.getElementById('confirmPassword').value;

      fetch('/finalee/public/actions/change-password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `current_password=${encodeURIComponent(current)}&new_password=${encodeURIComponent(newPass)}&confirm_password=${encodeURIComponent(confirm)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('successModal').style.display = 'flex';
          setTimeout(function() {
            window.location.href = "home.php";
          }, 2000);
        } else {
          alert(data.message || 'Failed to change password.');
        }
      })
      .catch(() => {
        alert('An error occurred. Please try again.');
      });
    });
  </script>

</body>
</html>
