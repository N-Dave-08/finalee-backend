function logout() {
  fetch('/finalee/public/actions/logout.php', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        localStorage.clear();
        sessionStorage.clear();
        window.location.href = '/finalee';
      } else {
        alert('Logout failed!');
      }
    })
    .catch(() => {
      alert('Logout error.');
    });
}
window.logout = logout;

function setupSidebarHamburger() {
  const sidebar = document.getElementById('sidebar');
  const hamburger = document.getElementById('sidebar-toggle');
  if (!sidebar || !hamburger) return;

  function showHamburger() {
    if (window.innerWidth <= 768) {
      hamburger.style.display = 'block';
      sidebar.style.transform = sidebar.classList.contains('open') ? 'translateX(0)' : 'translateX(-110%)';
      sidebar.style.transition = 'transform 0.3s';
      sidebar.style.position = 'fixed';
      sidebar.style.left = '0';
      sidebar.style.top = '0';
      sidebar.style.height = '100vh';
      sidebar.style.zIndex = '1050';
    } else {
      hamburger.style.display = 'none';
      sidebar.style.transform = 'none';
      sidebar.style.position = '';
      sidebar.style.height = '';
      sidebar.style.zIndex = '';
    }
  }

  hamburger.onclick = function(e) {
    e.stopPropagation();
    sidebar.classList.toggle('open');
    if (sidebar.classList.contains('open')) {
      sidebar.style.transform = 'translateX(0)';
      // Add overlay
      if (!document.getElementById('sidebar-overlay')) {
        const overlay = document.createElement('div');
        overlay.id = 'sidebar-overlay';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100vw';
        overlay.style.height = '100vh';
        overlay.style.background = 'rgba(0,0,0,0.2)';
        overlay.style.zIndex = '1049';
        overlay.onclick = function() {
          sidebar.classList.remove('open');
          sidebar.style.transform = 'translateX(-110%)';
          overlay.remove();
        };
        document.body.appendChild(overlay);
      }
    } else {
      sidebar.style.transform = 'translateX(-110%)';
      const overlay = document.getElementById('sidebar-overlay');
      if (overlay) overlay.remove();
    }
  };

  window.addEventListener('resize', showHamburger);
  showHamburger();
}

document.addEventListener('DOMContentLoaded', function() {
  var hamburger = document.getElementById('sidebar-toggle');
  var sidebar = document.getElementById('sidebar');
  if (hamburger && sidebar) {
    hamburger.style.display = '';
    hamburger.addEventListener('click', function(e) {
      e.stopPropagation();
      sidebar.classList.toggle('active');
    });
    document.addEventListener('click', function(e) {
      if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
        if (!sidebar.contains(e.target) && e.target !== hamburger) {
          sidebar.classList.remove('active');
        }
      }
    });
  }
});

document.addEventListener('DOMContentLoaded', setupSidebarHamburger);