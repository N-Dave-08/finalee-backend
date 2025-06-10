function logout() {
  alert("Logging out...");
  localStorage.clear(); // Clear stored login info if using localStorage
  window.location.href = "index.html";
}
window.logout = logout;