<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$success = false;
$error = '';
$conn = get_db_connection();
$patients = $conn->query("SELECT DISTINCT full_name FROM consultations ORDER BY full_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $complaint = trim($_POST['document'] ?? '');
    $preferred_date = trim($_POST['date'] ?? '');
    $status = 'Pending';
    // $remarks = trim($_POST['remarks'] ?? ''); //

    if ($full_name && $complaint && $preferred_date) {
        $insert = $conn->prepare("INSERT INTO appointments (full_name, complaint, preferred_date, status, created_at) VALUES (?, ?, ?, ?, NOW())");
        $insert->bind_param('ssss', $full_name, $complaint, $preferred_date, $status);
        if ($insert->execute()) {
            $success = true;
            header('Location: appointmentss.php');
            exit;
        } else {
            $error = 'Database error: ' . $insert->error;
        }
        $insert->close();
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Appointment</title>
  <link rel="stylesheet" href="assets/css/appointmentss.css" />
</head>
<body>
  <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
      <h1>Appointment</h1>
      <hr>

      <div class="content-container">
        <h2 class="page-title">Appointment</h2>

        <div class="form-container" style="background-color: #b7f7b2; padding: 20px; border-radius: 10px;">
          
            <form method="POST">
            <div style="position: relative; background-color: #eaffea; padding: 20px; border-radius: 15px;">

                <?php if ($error): ?>
                  <div style="color: red; margin-bottom: 10px; font-weight: bold;">Error: <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <!-- X Button to exit -->
                <button onclick="window.location.href='appointmentss.php';" style="
                  position: absolute;
                  top: 10px;
                  right: 10px;
                  background: transparent;
                  border: none;
                  font-size: 28px;
                  color: #4CAF50;
                  cursor: pointer;
                ">
                  &times;
                </button>
              
                <!-- Patient Name -->
                <div style="margin-bottom: 20px;">
                  <label for="full_name" style="font-weight: bold;">Select Patient</label><br><br>
                  <select id="full_name" name="full_name" required style="
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 20px;
                    border: none;
                    font-weight: bold;
                    width: 250px;
                  ">
                    <option value="">-- Select Patient --</option>
                    <?php if ($patients && $patients->num_rows > 0): ?>
                      <?php while ($p = $patients->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($p['full_name']) ?>">
                          <?= htmlspecialchars($p['full_name']) ?>
                        </option>
                      <?php endwhile; ?>
                    <?php endif; ?>
                  </select>
                </div>
              
                <!-- Document Section -->
                <div style="width: 250px; margin-bottom: 20px;">
                  <label for="document" style="font-weight: bold;">Document to be requested</label><br><br>
                  <select id="document" name="document" required style="
                    width: 100%;
                    height: 45px;
                    padding: 10px;
                    border-radius: 10px;
                    border: 2px solid #4CAF50;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                    appearance: none;
                    background-image: url('data:image/svg+xml;utf8,<svg fill=\'%234CAF50\' height=\'24\' viewBox=\'0 0 24 24\' width=\'24\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M7 10l5 5 5-5z\'/></svg>');
                    background-repeat: no-repeat;
                    background-position: right 10px center;
                    background-size: 24px;
                  ">
                    <option disabled selected value="">-- Select Document --</option>
                    <option>Kids Medication</option>
                    <option>Sick Check-up</option>
                    <option>Immunization</option>
                    <option>Prenatal</option>
                    <option>Family Planning</option>
                    <option>Pregnant Check-up</option>
                    <option>Pap Smear</option>
                  </select>
                </div>
              
                <!-- Date Section -->
                <div style="margin-bottom: 20px;">
                  <label for="date" style="font-weight: bold;">Date</label><br><br>
                  <input type="date" id="date" name="date" required style="
                    padding: 10px;
                    border: 2px solid #4CAF50;
                    border-radius: 10px;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                    width: 200px;
                  ">
                </div>
              
                <!-- Remarks Section -->
                <div style="margin-bottom: 20px;">
                  <label for="remarks" style="font-weight: bold;">Remarks</label><br><br>
                  <textarea id="remarks" name="remarks" rows="4" style="
                    width: 250px;
                    padding: 10px;
                    border-radius: 10px;
                    border: 2px solid #4CAF50;
                    background-color: #eaffea;
                    color: #333;
                    font-size: 16px;
                  "></textarea>
                </div>
              
                <!-- Confirm Button -->
                <div>
                  <button type="submit" style="
                    background-color: #FFEB3B;
                    color: black;
                    font-weight: bold;
                    border: none;
                    border-radius: 30px;
                    padding: 10px 30px;
                    font-size: 18px;
                    cursor: pointer;
                  ">
                    CONFIRM!
                  </button>
                </div>
                </div>
                </form>
              
              </div>
              
              <script>
                // No JS needed for form submit, handled by PHP
              </script>
              
</body>
</html>
<script src="assets/js/common.js"></script>
