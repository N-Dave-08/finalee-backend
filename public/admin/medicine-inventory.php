<?php
// TODO: Add authentication/authorization check for admin
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Inventory</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/medicine-inventory.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="sidebar-edge-indicator" id="sidebarEdgeIndicator" style="display:none;"></div>
        <main class="main-content">
            <h2>Medicine Inventory</h2>
            <button class="custom-btn" id="addMedicineBtn">Add Medicine</button>
            <table class="custom-table" id="medicineTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Dosage</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Medicine rows will be populated by JS -->
                </tbody>
            </table>
        </main>
    </div>

    <!-- Custom Add/Edit Medicine Modal -->
    <div id="medicineModal" class="custom-modal">
      <div class="custom-modal-content">
        <span class="custom-modal-close" id="closeModal">&times;</span>
        <h3 id="medicineModalLabel">Add/Edit Medicine</h3>
        <form id="medicineForm">
          <input type="hidden" id="medicineId" name="id">
          <label for="medicineName">Name</label>
          <input type="text" id="medicineName" name="name" required>
          <label for="medicineCategory">Category</label>
          <input type="text" id="medicineCategory" name="category" required>
          <label for="medicineDosage">Dosage</label>
          <input type="text" id="medicineDosage" name="dosage" required>
          <label for="medicineQuantity">Quantity</label>
          <input type="number" id="medicineQuantity" name="quantity" min="0" required>
          <label for="medicineExpiry">Expiry Date</label>
          <input type="date" id="medicineExpiry" name="expiry_date" required>
          <button type="submit" class="custom-btn green">Save</button>
        </form>
      </div>
    </div>

    <script src="../assets/js/medicine-inventory.js"></script>
    <script>
      function updateSidebarEdgeIndicator() {
        var sidebar = document.getElementById('sidebar');
        var edge = document.getElementById('sidebarEdgeIndicator');
        if (!sidebar || !edge) return;
        if (window.innerWidth <= 900 && !sidebar.classList.contains('open') && !sidebar.classList.contains('active')) {
          edge.style.display = 'block';
        } else {
          edge.style.display = 'none';
        }
      }
      document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.getElementById('sidebar');
        var edge = document.getElementById('sidebarEdgeIndicator');
        if (edge && sidebar) {
          edge.addEventListener('click', function(e) {
            sidebar.classList.add('open');
            edge.style.display = 'none';
          });
          window.addEventListener('resize', updateSidebarEdgeIndicator);
          updateSidebarEdgeIndicator();
          sidebar.addEventListener('transitionend', updateSidebarEdgeIndicator);
        }
      });
      document.addEventListener('click', function() { setTimeout(updateSidebarEdgeIndicator, 10); });
    </script>
</body>
</html> 