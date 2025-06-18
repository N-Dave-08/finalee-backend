<?php
$activePage = basename($_SERVER['PHP_SELF']);
function isActive($pages, $activePage) {
    if (is_array($pages)) {
        return in_array($activePage, $pages) ? 'active' : '';
    }
    return $pages === $activePage ? 'active' : '';
}
?>
<button id="sidebar-toggle" class="sidebar-hamburger" aria-label="Open sidebar" style="display:none;position:fixed;top:18px;left:18px;z-index:1100;background:none;border:none;cursor:pointer;">
  <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
  <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
  <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
</button>
<aside class="sidebar" id="sidebar">
  <img src="assets/images/newimus.png" alt="Barangay Logo" class="logo" />
  <h2>BARANGAY CLINIC<br>ONLINE APPOINTMENT SYSTEM</h2>
  <p>Imus, Cavite</p>
  <nav>
    <button class="<?=isActive('admin.php', $activePage)?>" onclick="location.href='admin.php'">Dashboard</button>
    <button class="<?=isActive(['appointmentss.php', 'archive-appointmentss.php'], $activePage)?>" onclick="location.href='appointmentss.php'">Appointments</button>
    <button class="<?=isActive('consultation.php', $activePage)?>" onclick="location.href='consultation.php'">Consultation</button>
    <button class="<?=isActive('patients.php', $activePage)?>" onclick="location.href='patients.php'">Patients</button>
    <button class="<?=isActive('medical-request.php', $activePage)?>" onclick="location.href='medical-request.php'">View Medical Request</button>
    <hr />
    <button onclick="logout()">LOGOUT</button>
  </nav>
</aside> 