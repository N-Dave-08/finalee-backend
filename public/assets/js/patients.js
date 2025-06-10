const patients = [
    "Chloe Cal", "Kazz Smith", "Vince Lee", "Jayson Stark", "Michael Jackstar",
    "Nick Felids", "Camille Sprats", "Guinevere Tan", "Nolan Gord", "Tony Foul",
    "Aurora Cold", "Calvin Clan", "Louis Vitorn", "Layla Young", "Chou Goudz"
  ];
  
  const patientList = document.getElementById("patientList");
  
  function displayPatients(filter = "") {
    patientList.innerHTML = "";
    patients
      .filter(name => name.toLowerCase().includes(filter.toLowerCase()))
      .forEach(name => {
        const row = document.createElement("div");
        row.className = "patient-row";
        row.innerHTML = `
          <div class="patient-name">${name}</div>
          <button class="btn profile-btn">Profile</button>
          <button class="btn archive-btn">Archive</button>
        `;
        patientList.appendChild(row);
      });
  }
  
  document.getElementById("searchInput").addEventListener("input", e => {
    displayPatients(e.target.value);
  });
  
  displayPatients();
  