<?php
require_once __DIR__ . '/../helpers/db.php';

class MedicineModel {
    private $conn;

    public function __construct() {
        $this->conn = get_db_connection();
    }

    public function getAllMedicines() {
        $stmt = $this->conn->prepare("SELECT * FROM medicines");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMedicineById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM medicines WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addMedicine($data) {
        $stmt = $this->conn->prepare("INSERT INTO medicines (name, category, dosage, quantity, expiry_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssis",
            $data['name'],
            $data['category'],
            $data['dosage'],
            $data['quantity'],
            $data['expiry_date']
        );
        return $stmt->execute();
    }

    public function updateMedicine($id, $data) {
        $stmt = $this->conn->prepare("UPDATE medicines SET name=?, category=?, dosage=?, quantity=?, expiry_date=? WHERE id=?");
        $stmt->bind_param(
            "sssisi",
            $data['name'],
            $data['category'],
            $data['dosage'],
            $data['quantity'],
            $data['expiry_date'],
            $id
        );
        return $stmt->execute();
    }

    public function deleteMedicine($id) {
        $stmt = $this->conn->prepare("DELETE FROM medicines WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
} 