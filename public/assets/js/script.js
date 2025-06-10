document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('profileForm');
  if (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const formData = new FormData(form);
      const profile = {};
      formData.forEach((value, key) => {
        profile[key] = value;
      });

      console.log("Profile data submitted:", profile);
      alert("Profile updated successfully!");
      window.location.href = "home.html";
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
      .then(response => response.json())
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
      .catch(() => {
        alert('Error connecting to server.');
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
    fetch('register.php', {
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
    window.location.href = "logout.php";
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
