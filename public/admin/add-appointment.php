<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$conn = get_db_connection();
$patients = $conn->query("SELECT DISTINCT full_name FROM consultations ORDER BY full_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Appointment</title>
  <link rel="stylesheet" href="/finalee/public/assets/css/appointmentss.css" />
  <style>
    .error-message {
      color: #e74c3c;
      font-size: 14px;
      margin-top: 5px;
      display: block;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .time-slots {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 10px;
      margin-top: 10px;
    }
    
    .slot {
      padding: 10px;
      border: 2px solid #4CAF50;
      border-radius: 8px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      background-color: #e8f5e8;
    }
    
    .slot.green {
      background-color: #e8f5e8;
      color: #2e7d32;
    }
    
    .slot.green:hover {
      background-color: #c8e6c9;
      transform: translateY(-2px);
    }
    
    .slot.selected {
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
    }
    
    .slot.unavailable {
      background-color: #f5f5f5;
      color: #999;
      border-color: #ddd;
      cursor: not-allowed;
      position: relative;
    }
    
    .unavailable-text {
      font-size: 12px;
      display: block;
      margin-top: 5px;
    }
    
    .submit-btn:disabled {
      background-color: #cccccc;
      cursor: not-allowed;
    }
    
    #form-status {
      font-weight: bold;
      padding: 10px;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
      <h1>Appointment</h1>
      <hr>

      <div class="content-container">
        <h2 class="page-title">Add New Appointment</h2>

        <div class="form-container" style="background-color: #b7f7b2; padding: 20px; border-radius: 10px;">
          
          <form id="appointmentForm" novalidate>
            <div style="position: relative; background-color: #eaffea; padding: 20px; border-radius: 15px;">

                <!-- X Button to exit -->
              <button type="button" onclick="window.location.href='appointmentss.php';" style="
                  position: absolute;
                  top: 10px;
                  right: 10px;
                  background: transparent;
                  border: none;
                  font-size: 28px;
                  color: #4CAF50;
                  cursor: pointer;
                ">
                  &times;
                </button>
              
                <!-- Patient Name -->
              <div class="form-group">
                <label for="full_name" style="font-weight: bold;">Select Patient*</label><br><br>
                  <select id="full_name" name="full_name" required style="
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 20px;
                    border: none;
                    font-weight: bold;
                    width: 250px;
                  ">
                    <option value="">-- Select Patient --</option>
                    <?php if ($patients && $patients->num_rows > 0): ?>
                      <?php while ($p = $patients->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($p['full_name']) ?>">
                          <?= htmlspecialchars($p['full_name']) ?>
                        </option>
                      <?php endwhile; ?>
                    <?php endif; ?>
                  </select>
                <div class="error-message" id="full_name-error"></div>
                </div>
              
                <!-- Document Section -->
              <div class="form-group">
                <label for="complaint" style="font-weight: bold;">Complaint/Document*</label><br><br>
                <select id="complaint" name="complaint" required style="
                  width: 250px;
                    height: 45px;
                    padding: 10px;
                    border-radius: 10px;
                    border: 2px solid #4CAF50;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                    appearance: none;
                    background-image: url('data:image/svg+xml;utf8,<svg fill=\'%234CAF50\' height=\'24\' viewBox=\'0 0 24 24\' width=\'24\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M7 10l5 5 5-5z\'/></svg>');
                    background-repeat: no-repeat;
                    background-position: right 10px center;
                    background-size: 24px;
                  ">
                  <option value="">-- Select Complaint --</option>
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
            
              <!-- Priority Section -->
              <div class="form-group">
                <label for="priority" style="font-weight: bold;">Priority Category (Optional)</label><br><br>
                <select id="priority" name="priority" style="
                  width: 250px;
                  height: 45px;
                  padding: 10px;
                  border-radius: 10px;
                  border: 2px solid #4CAF50;
                  background-color: #eaffea;
                  color: #333;
                  font-size: 16px;
                ">
                  <option value="">-- Select if applicable --</option>
                  <option value="None">None</option>
                  <option value="Elderly">Senior Citizen</option>
                  <option value="PWD">Person with Disability (PWD)</option>
                  <option value="Pregnant">Pregnant Woman</option>
                  </select>
                </div>
              
                <!-- Date Section -->
              <div class="form-group">
                <label for="appointment-date" style="font-weight: bold;">Date* <span style="font-size: 12px; color: #666;">(Monday to Friday only)</span></label><br><br>
                <input type="date" id="appointment-date" name="date" required min="<?php echo date('Y-m-d'); ?>" style="
                    padding: 10px;
                    border: 2px solid #4CAF50;
                    border-radius: 10px;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                    width: 200px;
                  ">
                <div class="error-message" id="date-error"></div>
              </div>
              
              <!-- Time Slot Section -->
              <div class="form-group">
                <label style="font-weight: bold;">Time Slot*</label><br><br>
                <div id="time-slots" class="time-slots"></div>
                <div class="error-message" id="slot-error"></div>
                <div id="last-updated" style="font-size: 12px; color: #666; margin-top: 10px; text-align: center;"></div>
               </div>
              
                <!-- Remarks Section -->
              <div class="form-group">
                <label for="remarks" style="font-weight: bold;">Remarks (Optional)</label><br><br>
                  <textarea id="remarks" name="remarks" rows="4" style="
                    width: 250px;
                    padding: 10px;
                    border-radius: 10px;
                    border: 2px solid #4CAF50;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                  "></textarea>
                </div>
              
              <!-- Submit Button -->
                <div>
                <button type="submit" class="submit-btn" id="submitBtn" disabled style="
                    background-color: #FFEB3B;
                    color: black;
                    font-weight: bold;
                    border: none;
                    border-radius: 30px;
                    padding: 10px 30px;
                    font-size: 18px;
                    cursor: pointer;
                  ">
                  CONFIRM APPOINTMENT
                  </button>
                </div>
              <div id="form-status"></div>
                </div>
                </form>
        </div>
      </div>
    </div>
              </div>
              
  <script src="/finalee/public/assets/js/common.js"></script>
              <script>
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
    const form = document.getElementById("appointmentForm");
    const submitBtn = document.getElementById("submitBtn");
    const statusDiv = document.getElementById("form-status");

    let selectedSlot = "";
    let triedSubmit = false;
    let latestBookedSlots = [];
    let isRendering = false;

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
      if (!selectedDate || isRendering) return;
      
      isRendering = true;

      // Check if selected date is a weekend
      if (checkWeekendDate()) {
        isRendering = false;
        return;
      }

      timeSlotsContainer.innerHTML = "";
      selectedSlot = "";

      // Fetch booked slots from backend
      const { booked, pending_counts } = await fetchBookedSlotsAndPendingCounts(selectedDate);
      latestBookedSlots = booked;

      // Use regularSlots or prioritySlots as needed
      const slots = prioritySelect.value && prioritySelect.value !== "None" ? prioritySlots : regularSlots;

      // Get current time for real-time availability
      const now = new Date();
      const selectedDateTime = new Date(selectedDate);
      const isToday = now.toDateString() === selectedDateTime.toDateString();
      const currentTime = now.getHours() * 60 + now.getMinutes();

      slots.forEach(slot => {
        const div = document.createElement("div");
        const isBooked = booked.includes(slot);
        
        // Check if slot is in the past (for today only)
        let isPastTime = false;
        if (isToday) {
          const slotStartTime = getSlotStartTime(slot);
          isPastTime = currentTime >= (slotStartTime - 5);
        }
        
        if (isBooked || isPastTime) {
          // Unavailable slot
          div.classList.add("slot", "unavailable");
          const reason = isPastTime ? "Past Time" : "Booked";
          div.innerHTML = `<span class="slot-time">${slot}</span><span class="unavailable-text">❌ ${reason}</span>`;
          div.style.cursor = "not-allowed";
        } else {
          // Available slot
          div.classList.add("slot", "green");
          div.textContent = slot;
          div.addEventListener("click", () => {
            document.querySelectorAll(".slot.green").forEach(s => s.classList.remove("selected"));
            div.classList.add("selected");
            selectedSlot = slot;
            validateForm();
          });
        }
        
        timeSlotsContainer.appendChild(div);
      });

      // Update last updated time
      const lastUpdatedDiv = document.getElementById("last-updated");
      if (lastUpdatedDiv) {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        lastUpdatedDiv.textContent = `Last updated: ${timeString}`;
      }
      
      isRendering = false;
    }

    // Helper function to get slot start time in minutes
    function getSlotStartTime(slot) {
      const startTimeMatch = slot.match(/^(\d{1,2}):(\d{2})/);
      if (!startTimeMatch) return 0;
      
      let hours = parseInt(startTimeMatch[1]);
      const minutes = parseInt(startTimeMatch[2]);
      
      const isMorningSlotEndingInPM = hours <= 11 && slot.includes('PM');
      
      if (isMorningSlotEndingInPM) {
        hours = hours;
      } else if (slot.includes('PM') && hours !== 12) {
        hours += 12;
      } else if (slot.includes('AM') && hours === 12) {
        hours = 0;
      }
      
      return hours * 60 + minutes;
    }

    // Function to check and handle weekend dates
    function checkWeekendDate() {
      const selectedDate = dateInput.value;
      
      if (!selectedDate) return false;
      
      const selectedDateTime = new Date(selectedDate);
      const dayOfWeek = selectedDateTime.getDay();
      
      if (dayOfWeek === 0 || dayOfWeek === 6) {
        let errorMessage = "";
        if (dayOfWeek === 0) {
          errorMessage = "Sunday clinic is not available. Please select Monday to Friday only.";
        } else if (dayOfWeek === 6) {
          errorMessage = "Saturday clinic is not available. Please select Monday to Friday only.";
        }
        
        const errorElement = document.getElementById("date-error");
        errorElement.innerHTML = `<span style="color: #e74c3c; font-weight: bold; background: #ffeaa7; padding: 5px 8px; border-radius: 4px; display: inline-block;">⚠️ ${errorMessage}</span>`;
        
        timeSlotsContainer.innerHTML = "";
        selectedSlot = "";
        validateForm();
        return true;
      } else {
        const errorElement = document.getElementById("date-error");
        if (errorElement.innerHTML.includes("clinic is not available")) {
          errorElement.innerHTML = "";
        }
        return false;
      }
    }

    // Validation functions
    function isValidFullName(name) {
      return name && name.trim() !== "";
    }

    function isValidComplaint(complaint) {
      return complaint && complaint !== "";
    }

    function isValidDate(date) {
      const today = new Date();
      const selected = new Date(date);
      today.setHours(0,0,0,0);
      
      if (selected < today) return false;
      
      const dayOfWeek = selected.getDay();
      if (dayOfWeek === 0 || dayOfWeek === 6) return false;
      
      return true;
    }

    function validateForm() {
      let valid = true;

      // Patient Name
      const fullName = document.getElementById("full_name").value.trim();
      if (triedSubmit || fullName) {
        if (fullName && !isValidFullName(fullName)) {
          document.getElementById("full_name-error").innerText = "Please select a valid patient.";
          valid = false;
        } else {
          document.getElementById("full_name-error").innerText = "";
        }
      } else {
        document.getElementById("full_name-error").innerText = "";
      }
      if (!fullName) valid = false;

      // Complaint
      const complaint = document.getElementById("complaint").value.trim();
      if (triedSubmit || complaint) {
        if (complaint && !isValidComplaint(complaint)) {
          document.getElementById("complaint-error").innerText = "Please select a valid complaint.";
          valid = false;
        } else {
          document.getElementById("complaint-error").innerText = "";
        }
      } else {
        document.getElementById("complaint-error").innerText = "";
      }
      if (!complaint) valid = false;

      // Date
      const date = document.getElementById("appointment-date").value;
      if (triedSubmit || date) {
        if (date && !isValidDate(date)) {
          const selectedDate = new Date(date);
          const dayOfWeek = selectedDate.getDay();
          if (dayOfWeek === 0) {
            document.getElementById("date-error").innerHTML = `<span style="color: #e74c3c; font-weight: bold; background: #ffeaa7; padding: 5px 8px; border-radius: 4px; display: inline-block;">⚠️ Sunday clinic is not available. Please select Monday to Friday only.</span>`;
          } else if (dayOfWeek === 6) {
            document.getElementById("date-error").innerHTML = `<span style="color: #e74c3c; font-weight: bold; background: #ffeaa7; padding: 5px 8px; border-radius: 4px; display: inline-block;">⚠️ Saturday clinic is not available. Please select Monday to Friday only.</span>`;
          } else {
            document.getElementById("date-error").innerText = "Please select a valid date (today or future).";
          }
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
          document.getElementById("slot-error").innerText = "Please select a time slot.";
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

    // Event listeners
    dateInput.addEventListener("change", function() {
      checkWeekendDate();
      renderSlots();
    });
    prioritySelect.addEventListener("change", renderSlots);

    // Form submission
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      triedSubmit = true;
      if (!validateForm()) {
        const firstError = document.querySelector('.error-message:not(:empty)');
        if (firstError) {
          const inputId = firstError.id.replace('-error', '');
          const input = document.getElementById(inputId);
          if (input) input.focus();
        }
        return;
      }
      
      submitBtn.disabled = true;
      statusDiv.innerHTML = '<span style="color:blue;">Creating appointment...</span>';

      const fullName = document.getElementById("full_name").value.trim();
      const complaint = document.getElementById("complaint").value.trim();
      const priority = document.getElementById("priority").value || "None";
      const date = document.getElementById("appointment-date").value;
      const remarks = document.getElementById("remarks").value.trim();

      // Send data to backend
      fetch("/finalee/public/actions/save-appointment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          fullName, complaint, priority, date, slot: selectedSlot, remarks
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          statusDiv.innerHTML = '<span style="color:green;">Appointment created successfully!</span>';
          setTimeout(() => {
            window.location.href = 'appointmentss.php';
          }, 2000);
        } else {
          statusDiv.innerHTML = '<span style="color:red;">' + (data.message || 'Failed to create appointment.') + '</span>';
          submitBtn.disabled = false;
        }
      })
      .catch(() => {
        statusDiv.innerHTML = '<span style="color:red;">An error occurred. Please try again.</span>';
        submitBtn.disabled = false;
      });
    });

    // After first submit, validate on input/change
    ["full_name", "complaint"].forEach(id => {
      document.getElementById(id).addEventListener("change", validateForm);
    });
    timeSlotsContainer.addEventListener("click", validateForm);

    // Auto-refresh slots every minute for real-time updates (only if today is selected)
    setInterval(() => {
      const selectedDate = dateInput.value;
      if (selectedDate) {
        const now = new Date();
        const selectedDateTime = new Date(selectedDate);
        const isToday = now.toDateString() === selectedDateTime.toDateString();
        
        if (isToday) {
          renderSlots();
        }
      }
    }, 60000);
              </script>
</body>
</html>
