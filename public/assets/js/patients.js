let patients = [];

const patientList = document.getElementById("patientList");
const searchInput = document.getElementById("searchInput");

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
      const row = document.createElement("div");
      row.className = "patient-row";
      row.innerHTML = `
        <div class="patient-name">${p.first_name} ${p.last_name}</div>
        <button class="btn profile-btn" data-id="${p.id}">Profile</button>
        <button class="btn archive-btn">Archive</button>
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
}

function showProfileModal(patient) {
  const modal = document.getElementById('profileModal');
  const details = document.getElementById('modalDetails');
  details.innerHTML = `
    <p><strong>Name:</strong> ${patient.first_name} ${patient.last_name}</p>
    <p><strong>Gender:</strong> ${patient.gender}</p>
    <p><strong>Email:</strong> ${patient.email}</p>
    <p><strong>Contact:</strong> ${patient.contact_num}</p>
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

// Fetch patients from API
fetch("/actions/patients-api.php")
  .then(res => res.json())
  .then(data => {
    patients = data;
    displayPatients();
  })
  .catch(err => {
    patientList.innerHTML = '<div style="color:red">Failed to load patients.</div>';
  });
  