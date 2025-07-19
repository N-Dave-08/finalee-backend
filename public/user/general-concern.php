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
          <label>Buong Pangalan*<br><span>(Full Name)</span></label>
          <?php
            // Session is already started by auth.php or a global include
            $user_fullname = '';
            if (isset($_SESSION['user']['id'])) {
              require_once dirname(__DIR__, 2) . '/app/models/UserModel.php';
              $userModel = new UserModel();
              $user = $userModel->findUserById($_SESSION['user']['id']);
              if ($user) {
                $user_fullname = htmlspecialchars(trim($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']));
              }
            }
          ?>
          <div id="fullname-display" style="font-weight:bold; padding:6px 0;">
            <?php echo $user_fullname ?: 'Unknown User'; ?>
          </div>
          <input type="hidden" id="fullname" value="<?php echo $user_fullname; ?>" />
        </div>

        <div class="form-group">
          <label for="complaint">Ano ang karamdaman na gusto mong ipakonsulta?*<br><span>(What is your main complaint?)</span></label>
          <select id="complaint" required>
            <option value="">-- Select a concern --</option>
            <option value="Check up for Elders">Check up for Elders</option>
            <option value="Sick Check-up">Sick Check-up</option>
            <option value="Kids Medication">Kids Medication</option>
            <option value="Immunization">Immunization</option>
            <option value="Family Planning">Family Planning</option>
            <option value="Pap Smear">Pap Smear</option>
            <option value="Pregnant Check-up">Pregnant Check-up</option>
            <option value="Prenatal">Prenatal</option>
          </select>
          <div class="error-message" id="complaint-error"></div>
        </div>

        <div class="form-group">
          <label for="details">Karagdagang detalye<br><span>(Other details)</span></label>
          <textarea id="details" placeholder="Please provide more information..."></textarea>
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
              <input type="date" id="appointment-date" required min="<?php echo date('Y-m-d'); ?>"/>
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

    const regularSlots = [
      "08:00 – 08:20 AM", "08:20 – 08:40 AM", "08:40 – 09:00 AM",
      "09:00 – 09:20 AM", "09:20 – 09:40 AM", "09:40 – 10:00 AM",
      "10:00 – 10:20 AM", "10:20 – 10:40 AM", "10:40 – 11:00 AM",
      "11:00 – 11:20 AM", "11:20 – 11:40 AM", "11:40 – 12:00 PM",
      // Lunch break: 12:00 PM – 01:00 PM (no slots)
      "01:00 – 01:20 PM", "01:20 – 01:40 PM", "01:40 – 02:00 PM",
      "02:00 – 02:20 PM", "02:20 – 02:40 PM", "02:40 – 03:00 PM",
      "03:00 – 03:20 PM", "03:20 – 03:40 PM", "03:40 – 04:00 PM",
      "04:00 – 04:20 PM", "04:20 – 04:40 PM", "04:40 – 05:00 PM"
    ];

    const prioritySlots = [
      "08:00 – 08:20 AM", "08:20 – 08:40 AM", "08:40 – 09:00 AM",
      "09:00 – 09:20 AM", "09:20 – 09:40 AM", "09:40 – 10:00 AM",
      "10:00 – 10:20 AM", "10:20 – 10:40 AM", "10:40 – 11:00 AM",
      "11:00 – 11:20 AM", "11:20 – 11:40 AM", "11:40 – 12:00 PM",
      // Lunch break: 12:00 PM – 01:00 PM (no slots)
      "01:00 – 01:20 PM", "01:20 – 01:40 PM", "01:40 – 02:00 PM",
      "02:00 – 02:20 PM", "02:20 – 02:40 PM", "02:40 – 03:00 PM",
      "03:00 – 03:20 PM", "03:20 – 03:40 PM", "03:40 – 04:00 PM",
      "04:00 – 04:20 PM", "04:20 – 04:40 PM", "04:40 – 05:00 PM"
    ];

    const dateInput = document.getElementById("appointment-date");
    const timeSlotsContainer = document.getElementById("time-slots");
    const prioritySelect = document.getElementById("priority");

    let selectedSlot = "";
    let triedSubmit = false;
    let latestBookedSlots = [];
    let latestPendingCounts = {};
    let highDemandWarningDiv = null;

    async function fetchBookedSlotsAndPendingCounts(date) {
      if (!date) return { booked: [], pending_counts: {} };
      try {
        const res = await fetch(`/finalee/public/actions/get-booked-slots.php?date=${encodeURIComponent(date)}`);
        const data = await res.json();
        if (data.success && Array.isArray(data.booked) && typeof data.pending_counts === 'object') {
          return { booked: data.booked, pending_counts: data.pending_counts };
        }
      } catch (e) {}
      return { booked: [], pending_counts: {} };
    }

    async function renderSlots() {
      const selectedDate = dateInput.value;
      if (!selectedDate) return;

      timeSlotsContainer.innerHTML = "";
      selectedSlot = "";
      if (highDemandWarningDiv) highDemandWarningDiv.remove();

      // Fetch booked slots and pending counts from backend
      const { booked, pending_counts } = await fetchBookedSlotsAndPendingCounts(selectedDate);
      latestBookedSlots = booked;
      latestPendingCounts = pending_counts;

      // Use regularSlots or prioritySlots as needed
      const slots = prioritySelect.value && prioritySelect.value !== "None" ? prioritySlots : regularSlots;

      slots.forEach(slot => {
        if (!booked.includes(slot)) {
          const div = document.createElement("div");
          div.classList.add("slot", "green");
          div.textContent = slot;
          // Mark high demand slots
          if ((pending_counts[slot] || 0) >= 1) {
            div.classList.add("high-demand");
            div.title = "This slot is in high demand. Others have also requested this time.";
            const badge = document.createElement("span");
            badge.textContent = "⚠️";
            badge.style.marginLeft = "8px";
            badge.title = "High demand";
            div.appendChild(badge);
          }
          div.addEventListener("click", () => {
            document.querySelectorAll(".slot.green").forEach(s => s.classList.remove("selected"));
            div.classList.add("selected");
            selectedSlot = slot;
            // Show warning if high demand
            if ((pending_counts[slot] || 0) >= 1) {
              if (!highDemandWarningDiv) {
                highDemandWarningDiv = document.createElement("div");
                highDemandWarningDiv.style.color = "#c0392b";
                highDemandWarningDiv.style.marginTop = "8px";
                highDemandWarningDiv.style.fontWeight = "bold";
                highDemandWarningDiv.textContent = "This slot is in high demand. Others have also requested this time. You may not get this slot if another user is prioritized.";
                timeSlotsContainer.parentNode.appendChild(highDemandWarningDiv);
              } else {
                highDemandWarningDiv.textContent = "This slot is in high demand. Others have also requested this time. You may not get this slot if another user is prioritized.";
                highDemandWarningDiv.style.display = "block";
              }
            } else if (highDemandWarningDiv) {
              highDemandWarningDiv.style.display = "none";
            }
            validateForm();
          });
          timeSlotsContainer.appendChild(div);
        }
      });
    }

    // Attach event listeners after defining renderSlots
    dateInput.addEventListener("change", renderSlots);
    prioritySelect.addEventListener("change", renderSlots);

    const form = document.getElementById("consultationForm");
    const submitBtn = document.getElementById("submitBtn");
    const statusDiv = document.getElementById("form-status");

    function isValidFullName(name) {
      return /^[A-Za-z ]{3,}$/.test(name) && name.trim().split(' ').length >= 2;
    }

    function isValidComplaint(complaint) {
      return complaint && complaint !== "";
    }

    function isValidDetails(details) {
      // Details are now optional, always return true
      return true;
    }

    function isValidDate(date) {
      const today = new Date();
      const selected = new Date(date);
      today.setHours(0,0,0,0);
      return selected >= today;
    }

    function validateForm() {
      let valid = true;

      // Full Name (no longer user input, just check if present)
      const fullname = document.getElementById("fullname").value.trim();
      if (!fullname) valid = false;
      document.getElementById("fullname-error")?.remove(); // Remove error message if present

      // Complaint
      const complaint = document.getElementById("complaint").value.trim();
      if (triedSubmit || complaint) {
        if (complaint && !isValidComplaint(complaint)) {
          document.getElementById("complaint-error").innerText = "Please select a valid concern.";
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
      // No validation needed for details since it's optional
      document.getElementById("details-error").innerText = "";

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
    ["complaint", "details", "appointment-date"].forEach(id => {
      document.getElementById(id).addEventListener("input", validateForm);
    });
    prioritySelect.addEventListener("change", validateForm);
    dateInput.addEventListener("change", validateForm);
    timeSlotsContainer.addEventListener("click", validateForm);
  </script>
</body>
</html>
