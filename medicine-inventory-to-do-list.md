# Medicine Inventory CRUD - TODO List

1. **Database**
   - [ ] Design and create the `medicines` table in the database with columns: id, name, category, dosage, quantity, expiry_date, status.

2. **Backend**
   - [ ] Create `MedicineModel.php` in `app/models/` for medicine CRUD operations.
   - [ ] Create `MedicineController.php` in `app/controllers/` to handle medicine CRUD logic.
   - [ ] Create API endpoints in `public/actions/medicine/` for get, add, edit, and delete medicine operations.

3. **Frontend**
   - [ ] Create a new page `public/admin/medicine-inventory.php` for the medicine inventory table UI.
   - [ ] Add a navigation link to "Medicine Inventory" in the admin sidebar/menu.
   - [ ] Create `public/assets/js/medicine-inventory.js` for AJAX and dynamic table handling.
   - [ ] Implement Add/Edit modal forms for medicine in the inventory page.
   - [ ] Implement status logic (low stock, in stock, out of stock) based on quantity in the table display.

4. **Security**
   - [ ] Restrict access to the medicine inventory page and endpoints to admin users only.