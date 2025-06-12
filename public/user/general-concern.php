<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
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
    <?php $activePage = 'general-concern.php'; include 'sidebar.php'; ?>

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
      <form class="consultation-form" id="consultationForm" novalidate>
        <div class="form-group">
          <label for="fullname">Buong Pangalan*<br><span>(Full Name)</span></label>
          <input type="text" id="fullname" placeholder="e.g., Juan Dela Cruz" required />
          <div class="error-message" id="fullname-error"></div>
        </div>

        <div class="form-group">
          <label for="complaint">Ano ang karamdaman na gusto mong ipakonsulta?*<br><span>(What is your main complaint?)</span></label>
          <input type="text" id="complaint" placeholder="e.g., Immunization" required />
          <div class="error-message" id="complaint-error"></div>
        </div>

        <div class="form-group">
          <label for="details">Karagdagang detalye*<br><span>(Other details)</span></label>
          <textarea id="details" placeholder="Please provide more information..." required></textarea>
          <div class="error-message" id="details-error"></div>
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
              <div class="error-message" id="date-error"></div>
            </div>
            <div id="time-slots" class="time-slots"></div>
            <div class="error-message" id="slot-error"></div>
          </div>
        </div>

        <button type="submit" class="save-btn" id="submitBtn" disabled>Submit</button>
        <div id="form-status" style="margin-top:10px;"></div>
      </form>
    </main>
  </div>

  <script src="assets/js/common.js"></script>
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
    const submitBtn = document.getElementById("submitBtn");
    const statusDiv = document.getElementById("form-status");

    function validateForm() {
      let valid = true;
      // Clear previous errors
      ["fullname", "complaint", "details", "date"].forEach(id => {
        const el = document.getElementById(id + "-error");
        if (el) el.innerText = "";
      });
      document.getElementById("slot-error").innerText = "";

      if (!document.getElementById("fullname").value.trim()) {
        document.getElementById("fullname-error").innerText = "Full name is required.";
        valid = false;
      }
      if (!document.getElementById("complaint").value.trim()) {
        document.getElementById("complaint-error").innerText = "Main complaint is required.";
        valid = false;
      }
      if (!document.getElementById("details").value.trim()) {
        document.getElementById("details-error").innerText = "Other details are required.";
        valid = false;
      }
      if (!dateInput.value) {
        document.getElementById("date-error").innerText = "Date is required.";
        valid = false;
      }
      if (!selectedSlot) {
        document.getElementById("slot-error").innerText = "Please select a time slot!";
        valid = false;
      }
      submitBtn.disabled = !valid;
      return valid;
    }

    ["fullname", "complaint", "details", "appointment-date"].forEach(id => {
      document.getElementById(id).addEventListener("input", validateForm);
    });
    prioritySelect.addEventListener("change", validateForm);
    dateInput.addEventListener("change", validateForm);
    timeSlotsContainer.addEventListener("click", validateForm);

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!validateForm()) return;
      submitBtn.disabled = true;
      statusDiv.innerHTML = '<span style="color:blue;">Submitting...</span>';

      const fullname = document.getElementById("fullname").value.trim();
      const complaint = document.getElementById("complaint").value.trim();
      const details = document.getElementById("details").value.trim();
      const priority = document.getElementById("priority").value || "None";
      const date = document.getElementById("appointment-date").value;

      // Send data to backend
      fetch("../actions/save-consultation.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          fullname, complaint, details, priority, date, slot: selectedSlot
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          statusDiv.innerHTML = '<span style="color:green;">Request submitted successfully!</span>';
          form.reset();
          selectedSlot = "";
          renderSlots();
        } else {
          statusDiv.innerHTML = '<span style="color:red;">' + (data.message || 'Submission failed.') + '</span>';
        }
        submitBtn.disabled = false;
      })
      .catch(() => {
        statusDiv.innerHTML = '<span style="color:red;">An error occurred. Please try again.</span>';
        submitBtn.disabled = false;
      });
    });
  </script>
</body>
</html>
