<?php
require_once dirname(__DIR__, 2) . '/app/helpers/auth.php';
require_role('admin');
require_once dirname(__DIR__, 2) . '/app/helpers/db.php';

$conn = get_db_connection();
$sql = "SELECT mdr.*, u.first_name, u.last_name FROM medical_documents_requests mdr LEFT JOIN user u ON mdr.user_id = u.id ORDER BY mdr.date_requested DESC";
$result = $conn->query($sql);
$requests = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/med.css" />
</head>
<body>
  <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
      <h2 style="text-align: center; background-color: #00B300; color: white; padding: 10px; font-family: Arial, sans-serif;">
        Medical Request
      </h2>
    
      <div id="toast-container" style="position:fixed;top:30px;right:30px;z-index:9999;"></div>
      <div style="background-color: #D0F5D8; padding: 20px; border-radius: 10px; font-family: Arial, sans-serif;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
          <div>
            <label for="dateSort">Date Sorting</label>
            <input type="text" id="dateSort" placeholder="DD/MM/YY" />
            <button>Submit</button>
          </div>
          <button style="padding: 5px 10px;">Archive</button>
        </div>
    
        <table border="1" width="100%" style="border-collapse: collapse; text-align: center;">
          <thead style="background-color: #2E8B57; color: white;">
            <tr>
              <th>Name &#x2195;</th>
              <th>Ref #. Date &#x2195;</th>
              <th>Request &#x2195;</th>
              <th>Status &#x2195;</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody style="background-color: #C6FBC6;">
            <?php foreach ($requests as $row): ?>
              <tr data-request-id="<?= $row['id'] ?>">
                <td><?= htmlspecialchars(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? $row['full_name'])) ?></td>
                <td><?= htmlspecialchars($row['reference_number']) ?></td>
                <td><?= htmlspecialchars($row['document_type']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                  <?php if (empty($row['file_path'])): ?>
                    <form class="upload-form" action="actions/upload_medical_doc.php" method="POST" enctype="multipart/form-data" style="display:inline;">
                      <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                      <input type="file" name="medical_doc" required style="display:inline;">
                      <button type="submit">Upload</button>
                    </form>
                  <?php else: ?>
                    <a href="../uploads/medical_docs/<?= htmlspecialchars($row['file_path']) ?>" target="_blank">View/Download</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <script src="assets/js/common.js"></script>
    <script>
    // Toast function
    function showToast(message, success = true) {
      const container = document.getElementById('toast-container');
      const div = document.createElement('div');
      div.textContent = message;
      div.style.background = success ? '#4caf50' : '#f44336';
      div.style.color = '#fff';
      div.style.padding = '16px 24px';
      div.style.borderRadius = '8px';
      div.style.marginBottom = '10px';
      div.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
      container.appendChild(div);
      setTimeout(() => div.remove(), 3000);
    }
    // AJAX upload
    document.querySelectorAll('.upload-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
          console.log(data); // For debugging
          if (data.success) {
            showToast('Request submitted successfully!', true);
            // Optionally, update the row to show the download link
            const row = form.closest('tr');
            row.querySelector('td:last-child').innerHTML = `<a href="../uploads/medical_docs/${data.file_path}" target="_blank">View/Download</a>`;
          } else {
            showToast(data.message || 'There was an error submitting your request.', false);
          }
        })
        .catch(() => {
          showToast('There was an error submitting your request.', false);
        });
      });
    });
    </script>
</body>
</html>
    