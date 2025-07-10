document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#medicineTable tbody');
    const addBtn = document.getElementById('addMedicineBtn');
    const modal = document.getElementById('medicineModal');
    const closeModalBtn = document.getElementById('closeModal');
    const form = document.getElementById('medicineForm');
    const modalTitle = document.getElementById('medicineModalLabel');
    const medicineIdInput = document.getElementById('medicineId');

    // Modal open/close logic
    function openModal() {
        modal.style.display = 'block';
    }
    function closeModal() {
        modal.style.display = 'none';
    }
    closeModalBtn.onclick = closeModal;
    window.onclick = function(event) {
        if (event.target === modal) closeModal();
    };

    // Fetch and display medicines
    function loadMedicines() {
        fetch('../actions/medicine/get-medicines.php')
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = '';
                data.forEach(med => {
                    const statusClass = getStatusClass(med.quantity);
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${med.id}</td>
                        <td>${med.name}</td>
                        <td>${med.category}</td>
                        <td>${med.dosage}</td>
                        <td>${med.quantity}</td>
                        <td>${med.expiry_date}</td>
                        <td class="${statusClass}">${getStatus(med.quantity)}</td>
                        <td>
                            <button class="custom-btn edit-btn" data-id="${med.id}">Edit</button>
                            <button class="custom-btn delete-btn" data-id="${med.id}" style="background:#e74c3c;">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });
    }

    // Status logic
    function getStatus(quantity) {
        quantity = parseInt(quantity);
        if (quantity === 0) return 'Out of Stock';
        if (quantity <= 10) return 'Low Stock';
        return 'In Stock';
    }
    function getStatusClass(quantity) {
        quantity = parseInt(quantity);
        if (quantity === 0) return 'status-out-of-stock';
        if (quantity <= 10) return 'status-low-stock';
        return 'status-in-stock';
    }

    // Open modal for add
    addBtn.addEventListener('click', function () {
        form.reset();
        medicineIdInput.value = '';
        modalTitle.textContent = 'Add Medicine';
        openModal();
    });

    // Open modal for edit
    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-btn')) {
            const id = e.target.getAttribute('data-id');
            fetch(`../actions/medicine/get-medicine.php?id=${id}`)
                .then(res => res.json())
                .then(med => {
                    medicineIdInput.value = med.id;
                    form.name.value = med.name;
                    form.category.value = med.category;
                    form.dosage.value = med.dosage;
                    form.quantity.value = med.quantity;
                    form.expiry_date.value = med.expiry_date;
                    modalTitle.textContent = 'Edit Medicine';
                    openModal();
                });
        }
    });

    // Delete medicine
    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-btn')) {
            if (!confirm('Are you sure you want to delete this medicine?')) return;
            const id = e.target.getAttribute('data-id');
            fetch('../actions/medicine/delete-medicine.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${encodeURIComponent(id)}`
            })
            .then(res => res.json())
            .then(() => loadMedicines());
        }
    });

    // Add/Edit submit
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        formData.delete('status'); // Ensure status is not sent
        const isEdit = !!medicineIdInput.value;
        const url = isEdit ? '../actions/medicine/update-medicine.php' : '../actions/medicine/add-medicine.php';
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(() => {
            closeModal();
            loadMedicines();
        });
    });

    // Initial load
    loadMedicines();
}); 