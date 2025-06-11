<?php
$activePage = basename($_SERVER['PHP_SELF']);
function isActive($page, $activePage) {
    return $page === $activePage ? 'active' : '';
}
?>
<aside class="sidebar">
  <img src="assets/images/newimus.png" alt="Barangay Logo" class="logo" />
  <h2>BARANGAY CLINIC<br>ONLINE APPOINTMENT SYSTEM</h2>
  <p>Imus, Cavite</p>
  <nav>
    <button class="<?=isActive('admin.php', $activePage)?>" onclick="location.href='admin.php'">Dashboard</button>
    <button class="<?=isActive('appointmentss.php', $activePage)?>" onclick="location.href='appointmentss.php'">Appointments</button>
    <button class="<?=isActive('consultation.php', $activePage)?>" onclick="location.href='consultation.php'">Consultation</button>
    <button class="<?=isActive('patients.php', $activePage)?>" onclick="location.href='patients.php'">Patients</button>
    <button class="<?=isActive('medical-request.php', $activePage)?>" onclick="location.href='medical-request.php'">View Medical Request</button>
    <hr />
    <button onclick="location.href='index.html'">LOGOUT</button>
  </nav>
</aside> 