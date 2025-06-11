<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/request for new consultation.css" />
  <title>Barangay Clinic Appointment</title>
</head>
<body>
  <div class="container">
    <!-- SIDEBAR -->
    <?php $activePage = 'request-for-new-consultation.php'; include 'sidebar.php'; ?>

    <!-- MAIN CONTENT -->
      <!-- Main Content -->
      <main class="main-content">
        <header>
          <h2>REQUEST FOR NEW CONSULTATION</h2>
        </header>
  
        <div class="consultation-box">
          <p><strong>Punan ang detalye ng request ng konsultasyon sa ibaba.</strong></p>
          <p>
            Para sa isang bagong konsultasyon, mangyaring mag click sa tab na 'General Concern'
            sa ibaba upang tingnan ang isang listahan ng mga pangunahing reklamo.
          </p>
          <p>
            Kung ang iyong mga sintomas ay nagpapahiwatig ng isang kondisyon na nangangailangan
            ng agarang pansin, isang malubhang isyu, o isang potensyal na banta sa iyong buhay,
            maaari naming inirerekomenda na bisitahin mo kaagad ang emergency room sa pinakamalapit na ospital.
          </p>
  
          <p><strong>Please fill out the request for consultation below.</strong></p>
          <p>
            For a new consultation, please click on the 'General Concern' tab below to view a list of major complaints.
          </p>
          <p>
            If your symptoms suggest a condition requiring immediate attention, a serious issue,
            or a potential threat to your life, we strongly recommend that you visit the emergency room at
            the nearest hospital immediately.
          </p>
  
          <button onclick="goToGeneralConcern('general-concern.php')">General Concern</button>
        </div>
      </main>
    </div>
  
    <script src="assets/js/common.js"></script>
    <script>
       function navigateTo(page) {
      window.location.href = page;
    }

    function bookNow() {
      alert("Redirecting to booking page...");
      // Add booking logic here
    }
      function goToGeneralConcern() {
        window.location.href = "general-concern.php"; // Change this to your actual page
      }

      // Highlight active sidebar button
      document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.sidebar nav button');
        const currentPage = window.location.pathname.split('/').pop();
        buttons.forEach(btn => {
          const match = btn.getAttribute('onclick')?.match(/navigateTo\('([^']+)'\)/);
          if (match && match[1] === currentPage) {
            btn.classList.add('active');
          }
        });
      });
    </script>
  
  </body>
  </html>