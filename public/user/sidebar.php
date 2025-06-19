<?php
$activePage = basename($_SERVER['PHP_SELF']);
function isActive($page, $activePage) {
    return $page === $activePage ? 'active' : '';
}
?>
<button id="sidebar-toggle" class="sidebar-hamburger" aria-label="Open sidebar" style="display:none;position:fixed;top:18px;left:18px;z-index:1100;background:none;border:none;cursor:pointer;">
  <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
  <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
  <span style="display:block;width:28px;height:4px;background:#333;margin:6px 0;border-radius:2px;"></span>
</button>
<aside class="sidebar" id="sidebar">
  <?php if ($activePage !== 'update-profile.php' && $activePage !== 'change-password.php'): ?>
    <img src="assets/images/newimus.png" alt="Barangay Logo" class="logo" />
    <h2>BARANGAY CLINIC ONLINE APPOINTMENT SYSTEM</h2>
    <p>Imus, Cavite</p>
    <nav>
      <button class="<?=isActive('home.php', $activePage)?>" onclick="navigateTo('home.php')">HOME</button>
      <button class="<?=isActive('update-profile.php', $activePage)?>" onclick="navigateTo('update-profile.php')">UPDATE PROFILE</button>
      <button class="<?=isActive('request-for-new-consultation.php', $activePage) || isActive('general-concern.php', $activePage) ? 'active' : ''?>" onclick="navigateTo('request-for-new-consultation.php')">REQUEST FOR NEW CONSULTATION</button>
      <button class="<?=isActive('view-consultation.php', $activePage)?>" onclick="navigateTo('view-consultation.php')">VIEW CONSULTATION RESULT</button>
      <button class="<?=isActive('appointments.php', $activePage)?>" onclick="navigateTo('appointments.php')">VIEW APPOINTMENTS</button>
      <button class="<?=isActive('request-medical-document.php', $activePage)?>" onclick="navigateTo('request-medical-document.php')">REQUEST MEDICAL DOCUMENTS</button>
      <button class="<?=isActive('change-password.php', $activePage)?>" onclick="navigateTo('change-password.php')">CHANGE PASSWORD</button>
      <button class="<?=isActive('broad-consent.php', $activePage)?>" onclick="navigateTo('broad-consent.php')">VIEW BROAD CONSENT</button>
      <button class="<?=isActive('privacy-notice.php', $activePage)?>" onclick="navigateTo('privacy-notice.php')">VIEW DATA PRIVACY NOTICE</button>
      <button onclick="logout()">LOGOUT</button>
    </nav>
  <?php endif; ?>
</aside> 