<?php
// includes/classes/Enrollment.php

class Enrollment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $sql = "SELECT e.*, s.first_name, s.last_name, c.course_name, c.course_code 
                FROM enrollments e
                JOIN students s ON e.student_id = s.id
                JOIN courses c ON e.course_id = c.id
                ORDER BY e.enrollment_date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getByStudent($student_id) {
        $sql = "SELECT e.*, c.course_name, c.course_code 
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE e.student_id = ?
                ORDER BY e.academic_year DESC, e.semester DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }

    public function create($student_id, $course_id, $semester, $academic_year) {
        // Check if already enrolled
        $stmt = $this->pdo->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ? AND semester = ? AND academic_year = ?");
        $stmt->execute([$student_id, $course_id, $semester, $academic_year]);
        if ($stmt->fetch()) {
            return false; // Already enrolled
        }

        $sql = "INSERT INTO enrollments (student_id, course_id, semester, academic_year) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$student_id, $course_id, $semester, $academic_year]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM enrollments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
