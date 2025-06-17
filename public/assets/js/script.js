document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('profileForm');
  // Fetch and populate profile data
  fetch('actions/update-profile-api.php', { credentials: 'include' })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const user = data.data.user || {};
        const emergency = data.data.emergency || {};
        document.getElementById('lastName').value = user.last_name || '';
        document.getElementById('firstName').value = user.first_name || '';
        document.getElementById('middleName').value = user.middle_name || '';
        document.getElementById('dob').value = user.date_of_birth || '';
        document.getElementById('placeOfBirth').value = user.place_of_birth || '';
        document.getElementById('gender').value = user.gender || '';
        document.getElementById('email').value = user.email || '';
        document.getElementById('contactNumber').value = user.contact_num || '';
        document.getElementById('emergencyLastName').value = emergency.last_name || '';
        document.getElementById('emergencyFirstName').value = emergency.first_name || '';
        document.getElementById('emergencyMiddleName').value = emergency.middle_name || '';
        document.getElementById('relationship').value = emergency.relationship || '';
        document.getElementById('emergencyContactNumber').value = emergency.contact_num || '';
        document.getElementById('emergencyEmail').value = emergency.email || '';
      }
    });
  if (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      const user = {
        last_name: document.getElementById('lastName').value,
        first_name: document.getElementById('firstName').value,
        middle_name: document.getElementById('middleName').value,
        date_of_birth: document.getElementById('dob').value,
        place_of_birth: document.getElementById('placeOfBirth').value,
        gender: document.getElementById('gender').value,
        email: document.getElementById('email').value,
        contact_num: document.getElementById('contactNumber').value
      };
      const emergency = {
        last_name: document.getElementById('emergencyLastName').value,
        first_name: document.getElementById('emergencyFirstName').value,
        middle_name: document.getElementById('emergencyMiddleName').value,
        relationship: document.getElementById('relationship').value,
        contact_num: document.getElementById('emergencyContactNumber').value,
        email: document.getElementById('emergencyEmail').value
      };
      fetch('actions/update-profile-api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ user, emergency })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert('Profile updated successfully!');
            window.location.href = 'home.php';
          } else {
            alert('Update failed: ' + (data.message || 'Unknown error'));
          }
        });
    });
  }

  // ==== Login/Register/Forgot Section ====
  const card = document.getElementById('card');
  const loginButton = document.querySelector('#loginForm button');

  const registerLink = document.querySelector('#loginForm .link[onclick="flipToRegister()"]');
  const forgotLink = document.querySelector('#loginForm .link[onclick="flipToForgot()"]');

  if (loginButton) {
    loginButton.onclick = () => {
      const inputs = document.querySelectorAll('#loginForm input');
      const username = inputs[0].value;
      const password = inputs[1].value;

      // Send POST request to login.php for both user and admin (MVC)
      fetch('login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
      })
      .then(async response => {
        if (!response.ok) {
          const text = await response.text();
          throw new Error(`HTTP ${response.status} ${response.statusText}: ${text}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          if (data.role === 'admin') {
            alert('Admin login successful!');
            window.location.href = 'admin.php';
          } else if (data.role === 'user') {
            alert('User login successful!');
            window.location.href = 'home.php';
          } else {
            alert('Unknown role.');
          }
        } else {
          alert(data.message || 'Login failed');
        }
      })
      .catch(error => {
        alert(`Login failed: ${error.message}`);
        console.error('Login error:', error);
      });
    };
  }

  // ==== Register Function ====
  function handleRegister() {
    const username = document.getElementById('regUsername').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const password = document.getElementById('regPassword').value;

    if (!username || !email || !password) {
      alert("Please fill in all fields.");
      return;
    }

    // Send POST request to register.php
    fetch('actions/register.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&email=${encodeURIComponent(email)}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Registration successful!');
        flipToLogin();
      } else {
        alert(data.message || 'Registration failed');
      }
    })
    .catch(() => {
      alert('Error connecting to server.');
    });
  }

  function logout() {
    window.location.href = "actions/logout.php";
  }

  function flipToRegister() {
    card.classList.add('flip');
  }

  function flipToLogin() {
    card.classList.remove('flip');
  }

  function flipToForgot() {
    document.getElementById('forgotOverlay').style.display = 'flex';
  }

  function closeForgot() {
    document.getElementById('forgotOverlay').style.display = 'none';
  }

  function navigateTo(page) {
    window.location.href = page;
  }

  // Expose globally
  window.logout = logout;
  window.flipToRegister = flipToRegister;
  window.flipToLogin = flipToLogin;
  window.flipToForgot = flipToForgot;
  window.closeForgot = closeForgot;
  window.navigateTo = navigateTo;
  window.handleRegister = handleRegister;
});
