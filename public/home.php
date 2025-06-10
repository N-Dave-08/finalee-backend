<?php
require_once __DIR__ . '/../app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/home.css" />
  <title>Barangay Clinic Appointment</title>
</head>
<body>

  <!-- Chatbot Toggle Button -->
<div class="chatbot-toggle" onclick="toggleChatbot()">💬</div>

<!-- Chatbot Container -->
<div class="chatbot-container" id="chatbot">
  <div class="chatbot-header">Clinic Assistant</div>
  <div class="chatbot-messages" id="chatbot-messages">
    <div class="bot-message">Hi! How can I help you today?</div>
    <div class="suggested-questions">
      <button onclick="quickQuestion('How to book appointment?')">Book Appointment</button>
      <button onclick="quickQuestion('How to reschedule appointment?')">Reschedule</button>
      <button onclick="quickQuestion('What are the clinic hours?')">Clinic Hours</button>
      <button onclick="quickQuestion('documents to bring?')">documents to bring?</button>
      <button onclick="quickQuestion('walk-in')">walk-in</button>
    </div>
  </div>
  <div class="chatbot-input-area">
    <input type="text" id="user-input" placeholder="Type your question..." />
    <button onclick="sendMessage()">Send</button>
  </div>
</div>

  <div class="container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <img src="assets/images/newimus.png" alt="Barangay Logo" class="logo" />
      <h2>BARANGAY CLINIC<br>ONLINE APPOINTMENT SYSTEM</h2>
      <p>Imus, Cavite</p>
      <nav>
        <button onclick="navigateTo('home.php')">HOME</button>
        <button onclick="navigateTo('update-profile.php')">UPDATE PROFILE</button>
        <button onclick="navigateTo('request for new consultation.php')">REQUEST FOR NEW CONSULTATION</button>
        <button onclick="navigateTo('view-consultation.php')">VIEW CONSULTATION RESULT</button>
        <button onclick="navigateTo('appointments.php')">VIEW APPOINTMENTS</button>
        <button onclick="navigateTo('request medical document.php')">REQUEST MEDICAL DOCUMENTS</button>
        <button onclick="navigateTo('change-password.php')">CHANGE PASSWORD</button>
        <button onclick="logout('index.html')">LOGOUT</button>
        <button onclick="navigateTo('broad-consent.php')">VIEW BROAD CONSENT</button>
        <button onclick="navigateTo('privacy-notice.php')">VIEW DATA PRIVACY NOTICE</button>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <section class="welcome">
        <h3>Hello, User!</h3>
        <div class="help-box">
          <p><strong>Ano ang maitutulong ko sa iyo?</strong><br>Ang button ay maayos na nakalagay sa gilid, kaya madali mong maa-access ang kailangan mo sa isang pindot lang.</p>
          <p><strong>What can I help you?</strong><br>The button is conveniently placed on the side, allowing you to easily access what you need with a single click.</p>
        </div>
      </section>

      <section class="appointment-box">
        <h4>Ang iyong susunod na appointment (Your next appointment)</h4>
        <div class="appointment-info">
          <p><strong>Appointment Reference Number:</strong> <span id="refNum">N/A</span></p>
          <p><strong>Time Block:</strong> <span id="timeBlock">N/A</span></p>
          <p><strong>Consultation Date:</strong> <span id="date">N/A</span></p>
          <p><strong>Main Complaint:</strong> <span id="complaint">N/A</span></p>
        </div>
        <button class="book-btn" onclick="navigateTo('general concern.php')">Book Now!</button>
      </section>
    </main>
  </div>

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
      alert(href=goto("general concern.php"));
      // Add booking logic here
    }

  // FAQ List
const faqs = {
  "book appointment": "To book an appointment, click 'Request New Consultation' and select your preferred date and time. or simplfied just cliked the book now in the homepage to instantly redirect to booking section",
  "reschedule appointment": "To reschedule, please cancel your current booking and make a new one.",
  "documents to bring": "Please bring your Barangay ID and previous medical documents if available.",
  "medical certificate": "You can request a medical certificate from the 'Request Medical Documents' section.",
  "clinic hours": "Our clinic operates from 8:00 AM to 5:00 PM, Monday to Friday.",
  "walk-in": "Walk-ins are accepted but scheduled appointments are given priority.",
  "request medical documents": "Click on 'Request Medical Documents' on your dashboard to start your request."
};

function getBotResponse(userInput) {
  userInput = userInput.toLowerCase();
  for (let keyword in faqs) {
    if (userInput.includes(keyword)) {
      return faqs[keyword];
    }
  }
  return "Sorry, I didn't understand. Please try asking differently.";
}

function sendMessage() {
  const userInput = document.getElementById('user-input').value;
  if (userInput.trim() === "") return;

  const messagesContainer = document.getElementById('chatbot-messages');

  // Show user's message
  const userMsg = document.createElement('div');
  userMsg.className = 'user-message';
  userMsg.innerText = userInput;
  messagesContainer.appendChild(userMsg);

  // Get bot response
  const botReply = getBotResponse(userInput);
  const botMsg = document.createElement('div');
  botMsg.className = 'bot-message';
  botMsg.innerText = botReply;
  messagesContainer.appendChild(botMsg);

  // Scroll to bottom
  messagesContainer.scrollTop = messagesContainer.scrollHeight;

  // Clear input
  document.getElementById('user-input').value = "";
}

function quickQuestion(question) {
  document.getElementById('user-input').value = question;
  sendMessage();
}

function toggleChatbot() {
  const chatbot = document.getElementById('chatbot');
  if (chatbot.style.display === "flex") {
    chatbot.style.display = "none";
  } else {
    chatbot.style.display = "flex";
  }
}

  </script>
</body>
</html>