function logout() {
  fetch('actions/logout.php', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        localStorage.clear();
        sessionStorage.clear();
        window.location.href = 'index.html';
      } else {
        alert('Logout failed!');
      }
    })
    .catch(() => {
      alert('Logout error.');
    });
}
window.logout = logout;