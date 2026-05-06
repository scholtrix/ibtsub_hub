<?php

class Student extends Model
{
    public function create(array $data)
    {
        $stmt = $this->db->prepare('INSERT INTO students (student_id, full_name, email, phone, address, gender, passport_photo, status, created_at, updated_at) VALUES (:student_id, :full_name, :email, :phone, :address, :gender, :passport_photo, :status, NOW(), NOW())');
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findByStudentId($studentId)
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE student_id = :student_id LIMIT 1');
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function update($id, array $data)
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare('UPDATE students SET full_name = :full_name, email = :email, phone = :phone, address = :address, gender = :gender, passport_photo = :passport_photo, status = :status, updated_at = NOW() WHERE id = :id');
        return $stmt->execute($data);
    }

    public function updateProfile($id, array $data)
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare('UPDATE students SET full_name = :full_name, email = :email, phone = :phone, address = :address, gender = :gender, passport_photo = :passport_photo, updated_at = NOW() WHERE id = :id');
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM students WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function listAll($statusFilter = null)
    {
        if ($statusFilter) {
            $stmt = $this->db->prepare('SELECT * FROM students WHERE status = :status ORDER BY created_at DESC');
            $stmt->execute([':status' => $statusFilter]);
            return $stmt->fetchAll();
        }

        $stmt = $this->db->query('SELECT * FROM students ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function generateStudentId($id)
    {
        return 'IBT' . date('Y') . str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public function countAll()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM students');
        return (int)$stmt->fetchColumn();
    }
}
