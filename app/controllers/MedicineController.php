<?php
require_once __DIR__ . '/../models/MedicineModel.php';

class MedicineController {
    private $model;

    public function __construct() {
        $this->model = new MedicineModel();
    }

    // List all medicines
    public function index() {
        return $this->model->getAllMedicines();
    }

    // Get a single medicine by ID
    public function show($id) {
        return $this->model->getMedicineById($id);
    }

    // Add a new medicine
    public function store($data) {
        return $this->model->addMedicine($data);
    }

    // Update an existing medicine
    public function update($id, $data) {
        return $this->model->updateMedicine($id, $data);
    }

    // Delete a medicine
    public function destroy($id) {
        return $this->model->deleteMedicine($id);
    }
} 