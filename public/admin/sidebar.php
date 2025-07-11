<?php
$activePage = basename($_SERVER['PHP_SELF']);
function isActive($pages, $activePage) {
    if (is_array($pages)) {
        return in_array($activePage, $pages) ? 'active' : '';
    }
    return $pages === $activePage ? 'active' : '';
}
?>
<?php if ($activePage !== 'admin.php' && $activePage !== 'medicine-inventory.php'): ?>
<button id="sidebar-toggle" class="sidebar-hamburger" aria-label="Open sidebar">
  <span></span>
  <span></span>
  <span></span>
</button>
<?php endif; ?>
<aside class="sidebar" id="sidebar">
  <img src="/finalee/public/assets/images/newimus.png" alt="Barangay Logo" class="logo" />
  <h2>BARANGAY CLINIC<br>ONLINE APPOINTMENT SYSTEM</h2>
  <p>Imus, Cavite</p>
  <nav>
    <button class="<?=isActive('admin.php', $activePage)?>" onclick="location.href='admin.php'">Dashboard</button>
    <button class="<?=isActive(['appointmentss.php', 'archive-appointmentss.php'], $activePage)?>" onclick="location.href='appointmentss.php'">Appointments</button>
    <button class="<?=isActive('consultation.php', $activePage)?>" onclick="location.href='consultation.php'">Consultation</button>
    <button class="<?=isActive('patients.php', $activePage)?>" onclick="location.href='patients.php'">Patients</button>
    <button class="<?=isActive('medicine-inventory.php', $activePage)?>" onclick="location.href='/finalee/admin/medicine-inventory.php'">Medicine Inventory</button>
    <button class="<?=isActive('medical-request.php', $activePage)?>" onclick="location.href='medical-request.php'">View Medical Request</button>
    <hr />
    <button onclick="logout()">LOGOUT</button>
  </nav>
</aside> 