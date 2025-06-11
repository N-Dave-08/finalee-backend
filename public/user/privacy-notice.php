<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/privacy-notice.css" />
  <title>Barangay Clinic Appointment</title>
</head>
<body>
  <div class="container">
    <?php $activePage = 'privacy-notice.php'; include 'sidebar.php'; ?>

    <div class="content">
      <h1>VIEW DATA PRIVACY NOTICE</h1>
      <p class="paragraph">
        Your privacy is important to us. This notice explains how we collect, use, share, and protect your personal information. Please read it carefully.
      </p>
      <p class="paragraph">
        We may collect personal information like your name, email, phone number, address, and payment details, as well as non-personal data such as browser type, device details, IP address, and cookies. This information helps us provide and improve our services, process transactions, communicate with you, and meet legal obligations.
      </p>
      <p class="paragraph">
        We do not sell your personal information but may share it with service providers, legal authorities, or during business transfers like mergers. You have rights to access, correct, delete, or opt out of marketing communications regarding your data. Contact us at (046) 5733608 to exercise these rights.
      </p>
      <p class="paragraph">
        We protect your data with security measures and retain it only as long as necessary. Our website uses cookies to enhance your experience, and you can adjust cookie settings in your browser. We are not responsible for the privacy practices of third-party websites linked from our site.
      </p>
      <p class="paragraph">
        This notice may be updated periodically, with changes posted here and the "Effective Date" adjusted. For questions or concerns, contact us at med.IIde1th@yahoo.com.<br>
        Thank you for trusting us with your information.
      </p>
    </div>
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
  </script>
</body>
</html>
