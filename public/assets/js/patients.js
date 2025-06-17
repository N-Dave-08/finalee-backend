let patients = [];
let archivedPatients = [];
const patientList = document.getElementById("patientList");
const searchInput = document.getElementById("searchInput");
const archivedPatientList = document.getElementById("archivedPatientList");
const toggleArchivedBtn = document.getElementById("toggleArchivedBtn");
let showingArchived = false;
const loading = document.getElementById("patientLoading");

function displayPatients(filter = "") {
  patientList.innerHTML = "";
  patients
    .filter(
      p =>
        (p.first_name + " " + p.last_name)
          .toLowerCase()
          .includes(filter.toLowerCase())
    )
    .forEach(p => {
      const hasFullName = p.first_name && p.last_name && (p.first_name.trim() !== '' || p.last_name.trim() !== '');
      const fullName = hasFullName ? `${p.first_name} ${p.last_name}` : '<span style="color:#888">User has not yet updated his/her full name</span>';
      const row = document.createElement("div");
      row.className = "patient-row";
      row.innerHTML = `
        <div class="patient-name">
          ${fullName}<br>
          <span class="patient-username" style="font-size:0.95em;color:#333;">@${p.username}</span>
        </div>
        <button class="btn profile-btn" data-id="${p.id}">Profile</button>
        <button class="btn archive-btn" data-id="${p.id}">Archive</button>
      `;
      patientList.appendChild(row);
    });

  // Add event listeners for profile buttons
  document.querySelectorAll('.profile-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const patient = patients.find(p => p.id == id);
      showProfileModal(patient);
    });
  });

  // Add event listeners for archive buttons
  document.querySelectorAll('.archive-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const patient = patients.find(p => p.id == id);
      if (confirm(`Are you sure you want to archive @${patient.username}?`)) {
        fetch('actions/archive-patient.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id=${encodeURIComponent(id)}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            // Remove patient from list and re-render
            patients = patients.filter(p => p.id != id);
            displayPatients(searchInput.value);
            alert('Patient archived successfully.');
          } else {
            alert('Failed to archive patient.');
          }
        })
        .catch(() => alert('Failed to archive patient.'));
      }
    });
  });
}

function showProfileModal(patient) {
  const modal = document.getElementById('profileModal');
  const details = document.getElementById('modalDetails');
  let emergency = '';
  if (patient.emergency_contact) {
    const ec = patient.emergency_contact;
    emergency = `
      <hr style="margin:18px 0;">
      <h3 style="margin-bottom:8px; color:#00cc00;">Emergency Contact</h3>
      <p><strong>Name:</strong> ${ec.first_name || ''} ${ec.last_name || ''}</p>
      <p><strong>Relationship:</strong> ${ec.relationship || ''}</p>
      <p><strong>Contact:</strong> ${ec.contact_num || ''}</p>
      <p><strong>Email:</strong> ${ec.email || ''}</p>
    `;
  } else {
    emergency = '<hr style="margin:18px 0;"><h3 style="color:#00cc00;">Emergency Contact</h3><p style="color:#888;">No emergency contact info.</p>';
  }
  const hasFullName = patient.first_name && patient.last_name && (patient.first_name.trim() !== '' || patient.last_name.trim() !== '');
  const fullName = hasFullName ? `${patient.first_name} ${patient.last_name}` : '<span style="color:#888">User has not yet updated his/her full name</span>';
  details.innerHTML = `
    <p><strong>Username:</strong> @${patient.username}</p>
    <p><strong>Name:</strong> ${fullName}</p>
    <p><strong>Gender:</strong> ${patient.gender}</p>
    <p><strong>Email:</strong> ${patient.email}</p>
    <p><strong>Contact:</strong> ${patient.contact_num}</p>
    ${emergency}
  `;
  modal.style.display = 'block';
}

document.getElementById('closeModalBtn').onclick = function() {
  document.getElementById('profileModal').style.display = 'none';
};

window.onclick = function(event) {
  const modal = document.getElementById('profileModal');
  if (event.target == modal) {
    modal.style.display = 'none';
  }
};

searchInput.addEventListener("input", e => {
  displayPatients(e.target.value);
});

function displayArchivedPatients(filter = "") {
  archivedPatientList.innerHTML = "";
  archivedPatients
    .filter(
      p =>
        (p.first_name + " " + p.last_name)
          .toLowerCase()
          .includes(filter.toLowerCase())
    )
    .forEach(p => {
      const hasFullName = p.first_name && p.last_name && (p.first_name.trim() !== '' || p.last_name.trim() !== '');
      const fullName = hasFullName ? `${p.first_name} ${p.last_name}` : '<span style="color:#888">User has not yet updated his/her full name</span>';
      const row = document.createElement("div");
      row.className = "patient-row";
      row.innerHTML = `
        <div class="patient-name">
          ${fullName}<br>
          <span class="patient-username" style="font-size:0.95em;color:#333;">@${p.username}</span>
        </div>
        <button class="btn unarchive-btn" data-id="${p.id}">Unarchive</button>
      `;
      archivedPatientList.appendChild(row);
    });

  // Add event listeners for unarchive buttons
  document.querySelectorAll('.unarchive-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const patient = archivedPatients.find(p => p.id == id);
      if (confirm(`Are you sure you want to unarchive @${patient.username}?`)) {
        fetch('actions/unarchive-patient.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id=${encodeURIComponent(id)}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            archivedPatients = archivedPatients.filter(p => p.id != id);
            displayArchivedPatients(searchInput.value);
            alert('Patient unarchived successfully.');
          } else {
            alert('Failed to unarchive patient.');
          }
        })
        .catch(() => alert('Failed to unarchive patient.'));
      }
    });
  });
}

toggleArchivedBtn.addEventListener('click', function() {
  showingArchived = !showingArchived;
  if (showingArchived) {
    // Show archived patients
    patientList.style.display = 'none';
    archivedPatientList.style.display = '';
    toggleArchivedBtn.textContent = 'Show Active';
    // Fetch archived patients only once or refresh if needed
    fetch('actions/archived-patients-api.php')
      .then(res => res.json())
      .then(data => {
        archivedPatients = data;
        displayArchivedPatients(searchInput.value);
      })
      .catch(() => {
        archivedPatientList.innerHTML = '<div style="color:red">Failed to load archived patients.</div>';
      });
  } else {
    // Show active patients
    patientList.style.display = '';
    archivedPatientList.style.display = 'none';
    toggleArchivedBtn.textContent = 'Show Archived';
    displayPatients(searchInput.value);
  }
});

// Fetch patients from API
loading.style.display = "";
patientList.innerHTML = "";
fetch("actions/patients-api.php")
  .then(res => res.json())
  .then(data => {
    patients = data;
    displayPatients();
    loading.style.display = "none";
  })
  .catch(err => {
    patientList.innerHTML = '<div style="color:red">Failed to load patients.</div>';
    loading.style.display = "none";
  });
  