<?php
// includes/classes/Fee.php

class Fee {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- Fee Structures ---
    
    public function getStructures() {
        $sql = "SELECT fs.*, c.course_name, c.course_code 
                FROM fee_structures fs
                JOIN courses c ON fs.course_id = c.id
                ORDER BY fs.academic_year DESC, fs.semester DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function structureExists($course_id, $semester, $academic_year) {
        $sql = "SELECT id FROM fee_structures WHERE course_id = ? AND semester = ? AND academic_year = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $course_id,
            $semester,
            $academic_year
        ]);
        return (bool) $stmt->fetch();
    }

    public function createStructure($data) {
        $sql = "INSERT INTO fee_structures (course_id, semester, academic_year, amount) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['course_id'],
            $data['semester'],
            $data['academic_year'],
            $data['amount']
        ]);
    }

    public function deleteStructure($id) {
        $stmt = $this->pdo->prepare("DELETE FROM fee_structures WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getStructuresForCourse($course_id, $semester, $academic_year) {
        $sql = "SELECT * FROM fee_structures WHERE course_id = ? AND semester = ? AND academic_year = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$course_id, $semester, $academic_year]);
        return $stmt->fetchAll();
    }


    // --- Fee Payments ---

    public function getPayments() {
        $sql = "SELECT fp.*, s.first_name, s.last_name, fs.amount as due_amount, c.course_name, fs.semester, fs.academic_year
                FROM fee_payments fp
                JOIN students s ON fp.student_id = s.id
                JOIN fee_structures fs ON fp.fee_structure_id = fs.id
                JOIN courses c ON fs.course_id = c.id
                ORDER BY fp.payment_date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function recordPayment($data) {
        $sql = "INSERT INTO fee_payments (student_id, fee_structure_id, amount_paid, receipt_number, payment_method) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['student_id'],
            $data['fee_structure_id'],
            $data['amount_paid'],
            $data['receipt_number'],
            $data['payment_method']
        ]);
    }

    public function getStudentPayments($student_id) {
        $sql = "SELECT fp.*, fs.semester, fs.academic_year, c.course_name
                FROM fee_payments fp
                JOIN fee_structures fs ON fp.fee_structure_id = fs.id
                JOIN courses c ON fs.course_id = c.id
                WHERE fp.student_id = ?
                ORDER BY fp.payment_date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }
}
