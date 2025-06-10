<?php
require_once __DIR__ . '/../app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Request for New Consultation | Barangay Clinic Appointment</title>
  <link rel="stylesheet" href="assets/css/general concern.css" />
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
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
        <button onclick="logout('index.html')">LOGOUT</button>
        <button onclick="navigateTo('broad-consent.php')">VIEW BROAD CONSENT</button>
        <button onclick="navigateTo('privacy-notice.php')">VIEW DATA PRIVACY NOTICE</button>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <button class="close-btn" onclick="navigateTo('home.php')">✕</button>
      <h2>REQUEST FOR NEW CONSULTATION</h2>

      <!-- General Concern -->
      <div class="general-box">
        <div class="title">General Concern</div>
        <div class="concern-list">
          <ul>
            <li>Check up for Elders</li>
            <li>Sick Check-up</li>
            <li>Kids Medication</li>
            <li>Immunization</li>
          </ul>
          <ul>
            <li>Family Planning</li>
            <li>Pap Smear</li>
            <li>Pregnant Check-up</li>
            <li>Prenatal</li>
          </ul>
        </div>
      </div>

      <!-- Consultation Form -->
      <form class="consultation-form" id="consultationForm">
        <div class="form-group">
          <label for="fullname">Buong Pangalan*<br><span>(Full Name)</span></label>
          <input type="text" id="fullname" placeholder="e.g., Juan Dela Cruz" required />
        </div>

        <div class="form-group">
          <label for="complaint">Ano ang karamdaman na gusto mong ipakonsulta?*<br><span>(What is your main complaint?)</span></label>
          <input type="text" id="complaint" placeholder="e.g., Immunization" required />
        </div>

        <div class="form-group">
          <label for="details">Karagdagang detalye*<br><span>(Other details)</span></label>
          <textarea id="details" placeholder="Please provide more information..." required></textarea>
        </div>

        <div class="form-group">
          <label for="priority">Priority Category (Optional)</label>
          <select id="priority">
            <option value="">-- Select if applicable --</option>
            <option value="None">None</option>
            <option value="Elderly">Senior Citizen</option>
            <option value="PWD">Person with Disability (PWD)</option>
            <option value="Pregnant">Pregnant Woman</option>
          </select>
        </div>

        <div class="form-group time-selection">
          <label>Gustong block ng araw at oras para sa konsultasyon*<br><span>(Preferred date and time block for consultation)</span></label>
          <div class="date-time">
            <div>
              <label for="appointment-date">Select Date</label>
              <input type="date" id="appointment-date" required />
            </div>
            <div id="time-slots" class="time-slots"></div>
          </div>
        </div>

        <button type="submit" class="save-btn">Submit</button>
      </form>
    </main>
  </div>

  <script>
    function navigateTo(url) {
      window.location.href = url;
    }

    const bookedTimeSlots = {
      "2025-01-15": ["08:00 – 08:20 AM", "09:00 – 09:20 AM"],
      "2025-01-16": ["09:40 – 10:00 AM"]
    };

    const regularSlots = [
      "08:00 – 08:20 AM", "08:20 – 08:40 AM", "08:40 – 09:00 AM",
      "09:00 – 09:20 AM", "09:20 – 09:40 AM", "09:40 – 10:00 AM"
    ];

    const prioritySlots = [
      "10:00 – 10:20 AM", "10:20 – 10:40 AM", "10:40 – 11:00 AM", "11:00 – 11:20 AM"
    ];

    const dateInput = document.getElementById("appointment-date");
    const timeSlotsContainer = document.getElementById("time-slots");
    const prioritySelect = document.getElementById("priority");

    let selectedSlot = "";

    dateInput.addEventListener("change", renderSlots);
    prioritySelect.addEventListener("change", renderSlots);

    function renderSlots() {
      const selectedDate = dateInput.value;
      if (!selectedDate) return;

      timeSlotsContainer.innerHTML = "";
      selectedSlot = "";

      const booked = bookedTimeSlots[selectedDate] || [];
      const isPriority = prioritySelect.value !== "None" && prioritySelect.value !== "";
      const slots = isPriority ? prioritySlots : regularSlots;

      slots.forEach(slot => {
        const div = document.createElement("div");
        div.classList.add("slot");
        div.textContent = slot;

        if (booked.includes(slot)) {
          div.classList.add("red");
          div.style.pointerEvents = "none";
        } else {
          div.classList.add("green");
          div.addEventListener("click", () => {
            document.querySelectorAll(".slot.green").forEach(s => s.classList.remove("selected"));
            div.classList.add("selected");
            selectedSlot = slot;
          });
        }

        timeSlotsContainer.appendChild(div);
      });
    }

    const form = document.getElementById("consultationForm");
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const fullname = document.getElementById("fullname").value.trim();
      const complaint = document.getElementById("complaint").value.trim();
      const details = document.getElementById("details").value.trim();
      const priority = document.getElementById("priority").value || "None";
      const date = document.getElementById("appointment-date").value;

      if (!selectedSlot) {
        alert("Please select a time slot!");
        return;
      }

      // For demo: Just show all data in alert (Remove in real use)
      alert(
        "Submitting:\n" +
        "Full Name: " + fullname + "\n" +
        "Complaint: " + complaint + "\n" +
        "Details: " + details + "\n" +
        "Priority: " + priority + "\n" +
        "Date: " + date + "\n" +
        "Time Slot: " + selectedSlot
      );

      // TODO: Replace with your actual fetch request to backend here.
    });
  </script>
</body>
</html>
