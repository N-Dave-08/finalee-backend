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
        // Set contact number, stripping leading '63' if present
        let contactNum = user.contact_num || '';
        if (contactNum.startsWith('63') && contactNum.length === 12) {
          contactNum = contactNum.slice(2);
        }
        document.getElementById('contactNumber').value = contactNum;
        document.getElementById('emergencyLastName').value = emergency.last_name || '';
        document.getElementById('emergencyFirstName').value = emergency.first_name || '';
        document.getElementById('emergencyMiddleName').value = emergency.middle_name || '';
        document.getElementById('relationship').value = emergency.relationship || '';
        // Set emergency contact number, stripping leading '63' if present
        let emergencyContactNum = emergency.contact_num || '';
        if (emergencyContactNum.startsWith('63') && emergencyContactNum.length === 12) {
          emergencyContactNum = emergencyContactNum.slice(2);
        }
        document.getElementById('emergencyContactNumber').value = emergencyContactNum;
        document.getElementById('emergencyEmail').value = emergency.email || '';
      }
    });
  if (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      let contactNum = document.getElementById('contactNumber').value.replace(/^63/, '');
      if (/^\d{10}$/.test(contactNum)) {
        contactNum = '63' + contactNum;
      }

      let emergencyContactNum = document.getElementById('emergencyContactNumber').value.replace(/^63/, '');
      if (/^\d{10}$/.test(emergencyContactNum)) {
        emergencyContactNum = '63' + emergencyContactNum;
      }

      // Debug log for contact numbers
      // console.log('Contact Number to submit:', contactNum);
      // console.log('Emergency Contact Number to submit:', emergencyContactNum);

      const user = {
        last_name: document.getElementById('lastName').value,
        first_name: document.getElementById('firstName').value,
        middle_name: document.getElementById('middleName').value,
        date_of_birth: document.getElementById('dob').value,
        place_of_birth: document.getElementById('placeOfBirth').value,
        gender: document.getElementById('gender').value,
        email: document.getElementById('email').value,
        contact_num: contactNum
      };
      const emergency = {
        last_name: document.getElementById('emergencyLastName').value,
        first_name: document.getElementById('emergencyFirstName').value,
        middle_name: document.getElementById('emergencyMiddleName').value,
        relationship: document.getElementById('relationship').value,
        contact_num: emergencyContactNum,
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
      fetch('actions/login.php', {
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
    const lastName = document.getElementById('regLastName').value.trim();
    const firstName = document.getElementById('regFirstName').value.trim();
    const middleName = document.getElementById('regMiddleName').value.trim();
    let contactNumber = document.getElementById('regContactNumber').value.trim();
    if (/^\d{10}$/.test(contactNumber)) {
      contactNumber = '63' + contactNumber;
    }
    const username = document.getElementById('regUsername').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const password = document.getElementById('regPassword').value;

    if (!lastName || !firstName || !username || !email || !password || !contactNumber) {
      alert("Please fill in all fields.");
      return;
    }

    // Send POST request to register.php
    fetch('actions/register.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&email=${encodeURIComponent(email)}&firstName=${encodeURIComponent(firstName)}&middleName=${encodeURIComponent(middleName)}&lastName=${encodeURIComponent(lastName)}&contactNumber=${encodeURIComponent(contactNumber)}`
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
    window.location.href = "/finalee/public/actions/logout.php";
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

  function handleForgotPassword() {
    const email = document.getElementById('forgotEmail').value.trim();
    const msgDiv = document.getElementById('forgotMsg');
    const forgotBtn = document.getElementById('forgotBtn');
    const forgotLoading = document.getElementById('forgotLoading');
    msgDiv.textContent = '';
    if (!email) {
      msgDiv.textContent = 'Please enter your email.';
      msgDiv.style.color = 'red';
      return;
    }
    forgotBtn.disabled = true;
    forgotLoading.style.display = '';
    fetch('actions/forgot-password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `email=${encodeURIComponent(email)}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        msgDiv.textContent = data.message || 'Reset link has been sent.';
        msgDiv.style.color = 'green';
      } else {
        msgDiv.textContent = data.message || 'Failed to send reset link.';
        msgDiv.style.color = 'red';
      }
      forgotBtn.disabled = false;
      forgotLoading.style.display = 'none';
    })
    .catch(() => {
      msgDiv.textContent = 'An error occurred. Please try again later.';
      msgDiv.style.color = 'red';
      forgotBtn.disabled = false;
      forgotLoading.style.display = 'none';
    });
  }

  // Expose globally
  window.logout = logout;
  window.flipToRegister = flipToRegister;
  window.flipToLogin = flipToLogin;
  window.flipToForgot = flipToForgot;
  window.closeForgot = closeForgot;
  window.navigateTo = navigateTo;
  window.handleRegister = handleRegister;
  window.handleForgotPassword = handleForgotPassword;
});
