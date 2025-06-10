function goToGeneralConcern() {
    window.location.href = "general-concern.html"; // change this to your actual destination page
  }
  
  function navigateTo(page) {
    window.location.href = page;
  }

  function logout() {
  alert("Logging out...");
  localStorage.clear(); // Clear stored login info if using localStorage
  window.location.href = "index.html";
}


  function bookNow() {
    alert("Redirecting to booking page...");
    // Add booking logic here
  }
    function goToGeneralConcern() {
      window.location.href = "general concern.html"; // Change this to your actual page
    }