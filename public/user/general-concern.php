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
    let triedSubmit = false;

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

    function isValidFullName(name) {
      return /^[A-Za-z ]{3,}$/.test(name) && name.trim().split(' ').length >= 2;
    }

    function isValidComplaint(complaint) {
      return complaint.trim().length >= 3;
    }

    function isValidDetails(details) {
      return details.trim().length >= 10;
    }

    function isValidDate(date) {
      const today = new Date();
      const selected = new Date(date);
      today.setHours(0,0,0,0);
      return selected >= today;
    }

    function validateForm() {
      let valid = true;

      // Full Name
      const fullname = document.getElementById("fullname").value.trim();
      if (triedSubmit || fullname) {
        if (fullname && !isValidFullName(fullname)) {
          document.getElementById("fullname-error").innerText = "Please enter your full name (at least two words, letters only).";
          valid = false;
        } else {
          document.getElementById("fullname-error").innerText = "";
        }
      } else {
        document.getElementById("fullname-error").innerText = "";
      }
      if (!fullname) valid = false;

      // Complaint
      const complaint = document.getElementById("complaint").value.trim();
      if (triedSubmit || complaint) {
        if (complaint && !isValidComplaint(complaint)) {
          document.getElementById("complaint-error").innerText = "Please enter a valid complaint (at least 3 characters).";
          valid = false;
        } else {
          document.getElementById("complaint-error").innerText = "";
        }
      } else {
        document.getElementById("complaint-error").innerText = "";
      }
      if (!complaint) valid = false;

      // Details
      const details = document.getElementById("details").value.trim();
      if (triedSubmit || details) {
        if (details && !isValidDetails(details)) {
          document.getElementById("details-error").innerText = "Please provide more details (at least 10 characters).";
          valid = false;
        } else {
          document.getElementById("details-error").innerText = "";
        }
      } else {
        document.getElementById("details-error").innerText = "";
      }
      if (!details) valid = false;

      // Date
      const date = document.getElementById("appointment-date").value;
      if (triedSubmit || date) {
        if (date && !isValidDate(date)) {
          document.getElementById("date-error").innerText = "Please select a valid date (today or future).";
          valid = false;
        } else {
          document.getElementById("date-error").innerText = "";
        }
      } else {
        document.getElementById("date-error").innerText = "";
      }
      if (!date) valid = false;

      // Time Slot
      if (triedSubmit || selectedSlot) {
        if (!selectedSlot) {
          document.getElementById("slot-error").innerText = "";
          valid = false;
        } else {
          document.getElementById("slot-error").innerText = "";
        }
      } else {
        document.getElementById("slot-error").innerText = "";
      }
      if (!selectedSlot) valid = false;

      submitBtn.disabled = !valid;
      return valid;
    }

    // On submit, set triedSubmit = true and validate
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      triedSubmit = true;
      if (!validateForm()) {
        // Focus first error
        const firstError = document.querySelector('.error-message:not(:empty)');
        if (firstError) {
          const inputId = firstError.id.replace('-error', '');
          const input = document.getElementById(inputId);
          if (input) input.focus();
        }
        return;
      }
      submitBtn.disabled = true;
      statusDiv.innerHTML = '<span style="color:blue;">Submitting...</span>';

      const fullname = document.getElementById("fullname").value.trim();
      const complaint = document.getElementById("complaint").value.trim();
      const details = document.getElementById("details").value.trim();
      const priority = document.getElementById("priority").value || "None";
      const date = document.getElementById("appointment-date").value;

      // Send data to backend
      fetch("/finalee/public/actions/save-consultation.php", {
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
          triedSubmit = false;
          selectedSlot = "";
          document.querySelectorAll(".slot.selected").forEach(s => s.classList.remove("selected"));
          renderSlots();
          validateForm(); // Ensure button is disabled after reset
          timeSlotsContainer.innerHTML = ""; // Clear the time slot UI
        } else {
          statusDiv.innerHTML = '<span style="color:red;">' + (data.message || 'Submission failed.') + '</span>';
        }
      })
      .catch(() => {
        statusDiv.innerHTML = '<span style="color:red;">An error occurred. Please try again.</span>';
        submitBtn.disabled = false;
      });
    });

    // After first submit, validate on input/change
    ["fullname", "complaint", "details", "appointment-date"].forEach(id => {
      document.getElementById(id).addEventListener("input", validateForm);
    });
    prioritySelect.addEventListener("change", validateForm);
    dateInput.addEventListener("change", validateForm);
    timeSlotsContainer.addEventListener("click", validateForm);
  </script>
</body>
</html>
