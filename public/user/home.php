<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('user');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$user_id = $_SESSION['user']['id'];
$conn = get_db_connection();

// Fetch the next upcoming appointment for the logged-in user
$sql = "SELECT id, complaint, preferred_date, time_slot, status FROM consultations WHERE user_id = ? AND status = 'Pending' AND preferred_date >= CURDATE() ORDER BY preferred_date ASC, id ASC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Prepare appointment details or fallback to N/A
if ($appointment) {
    $refNum = '#' . str_pad($appointment['id'], 2, '0', STR_PAD_LEFT) . '-' . date('mdY', strtotime($appointment['preferred_date']));
    $timeBlock = $appointment['time_slot'] ? $appointment['time_slot'] : 'N/A';
    $date = date('F d, Y', strtotime($appointment['preferred_date']));
    $complaint = $appointment['complaint'] ? $appointment['complaint'] : 'N/A';
} else {
    $refNum = $timeBlock = $date = $complaint = 'N/A';
}
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
    <?php $activePage = 'home.php'; include 'sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <section class="welcome">
        <h3>Hello, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</h3>
        <div class="help-box">
          <p><strong>Ano ang maitutulong ko sa iyo?</strong><br>Ang button ay maayos na nakalagay sa gilid, kaya madali mong maa-access ang kailangan mo sa isang pindot lang.</p>
          <p><strong>What can I help you?</strong><br>The button is conveniently placed on the side, allowing you to easily access what you need with a single click.</p>
        </div>
      </section>

      <section class="appointment-box">
        <h4>Ang iyong susunod na appointment (Your next appointment)</h4>
        <div class="appointment-info">
          <p><strong>Appointment Reference Number:</strong> <span id="refNum"><?php echo htmlspecialchars($refNum); ?></span></p>
          <p><strong>Time Block:</strong> <span id="timeBlock"><?php echo htmlspecialchars($timeBlock); ?></span></p>
          <p><strong>Consultation Date:</strong> <span id="date"><?php echo htmlspecialchars($date); ?></span></p>
          <p><strong>Main Complaint:</strong> <span id="complaint"><?php echo htmlspecialchars($complaint); ?></span></p>
        </div>
        <button class="book-btn" onclick="navigateTo('general-concern.php')">Book Now!</button>
      </section>
    </main>
  </div>

  <script src="assets/js/common.js"></script>
  <script>
    function navigateTo(page) {
      window.location.href = page;
    }

    function bookNow() {
      alert(href=goto("general-concern.php"));
      // Add booking logic here
    }

    // Highlight active sidebar button
    document.addEventListener('DOMContentLoaded', function() {
      const buttons = document.querySelectorAll('.sidebar nav button');
      const currentPage = window.location.pathname.split('/').pop();
      buttons.forEach(btn => {
        const match = btn.getAttribute('onclick')?.match(/navigateTo\('([^']+)'\)/);
        if (match && match[1] === currentPage) {
          btn.classList.add('active');
        }
      });
    });

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