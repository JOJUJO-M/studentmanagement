<?php
// includes/classes/Student.php

class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM students ORDER BY last_name, first_name");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO students (first_name, last_name, email, phone, date_of_birth, enrollment_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['first_name'], 
            $data['last_name'], 
            $data['email'], 
            $data['phone'], 
            $data['date_of_birth'], 
            $data['enrollment_date'], 
            $data['status']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, enrollment_date = ?, status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['first_name'], 
            $data['last_name'], 
            $data['email'], 
            $data['phone'], 
            $data['date_of_birth'], 
            $data['enrollment_date'], 
            $data['status'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM students WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get a student's total balance
    public function getBalance($student_id) {
        // Total fees assigned to student
        $sql_fees = "SELECT SUM(fs.amount) as total_due 
                     FROM enrollments e
                     JOIN fee_structures fs ON e.course_id = fs.course_id AND e.semester = fs.semester AND e.academic_year = fs.academic_year
                     WHERE e.student_id = ?";
        $stmt1 = $this->pdo->prepare($sql_fees);
        $stmt1->execute([$student_id]);
        $total_due = $stmt1->fetchColumn() ?: 0;

        // Total payments made by student
        $sql_paid = "SELECT SUM(amount_paid) as total_paid FROM fee_payments WHERE student_id = ?";
        $stmt2 = $this->pdo->prepare($sql_paid);
        $stmt2->execute([$student_id]);
        $total_paid = $stmt2->fetchColumn() ?: 0;

        return $total_due - $total_paid;
    }
}
