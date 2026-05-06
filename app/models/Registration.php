<?php

class Registration extends Model
{
    public function create(array $data)
    {
        $stmt = $this->db->prepare('INSERT INTO registrations (student_id, course_id, status, payment_status, payment_reference, applied_at, updated_at) VALUES (:student_id, :course_id, :status, :payment_status, :payment_reference, NOW(), NOW())');
        return $stmt->execute($data);
    }

    public function findByStudentId($studentId)
    {
        $stmt = $this->db->prepare('SELECT r.*, c.title AS course_title, c.price AS course_price FROM registrations r JOIN courses c ON r.course_id = c.id WHERE r.student_id = :student_id ORDER BY r.applied_at DESC LIMIT 1');
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT r.*, c.title AS course_title, c.price AS course_price, s.student_id AS student_code, s.full_name AS student_name FROM registrations r JOIN courses c ON r.course_id = c.id JOIN students s ON r.student_id = s.id WHERE r.id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function listAll($statusFilter = null)
    {
        if ($statusFilter) {
            $stmt = $this->db->prepare('SELECT r.*, s.full_name AS student_name, c.title AS course_title FROM registrations r JOIN students s ON r.student_id = s.id JOIN courses c ON r.course_id = c.id WHERE r.status = :status ORDER BY r.applied_at DESC');
            $stmt->execute([':status' => $statusFilter]);
            return $stmt->fetchAll();
        }

        $stmt = $this->db->query('SELECT r.*, s.full_name AS student_name, c.title AS course_title FROM registrations r JOIN students s ON r.student_id = s.id JOIN courses c ON r.course_id = c.id ORDER BY r.applied_at DESC');
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare('UPDATE registrations SET status = :status, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function updatePaymentStatus($id, $paymentStatus, $paymentReference = null)
    {
        $stmt = $this->db->prepare('UPDATE registrations SET payment_status = :payment_status, payment_reference = :payment_reference, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([':payment_status' => $paymentStatus, ':payment_reference' => $paymentReference, ':id' => $id]);
    }

    public function countByCourse($courseId)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM registrations WHERE course_id = :course_id');
        $stmt->execute([':course_id' => $courseId]);
        return (int)$stmt->fetchColumn();
    }
}
