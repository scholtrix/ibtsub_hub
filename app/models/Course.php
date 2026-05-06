<?php

class Course extends Model
{
    public function getAll($onlyActive = false)
    {
        if ($onlyActive) {
            $stmt = $this->db->prepare('SELECT * FROM courses WHERE is_active = 1 ORDER BY title ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        }

        $stmt = $this->db->query('SELECT * FROM courses ORDER BY title ASC');
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug)
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE slug = :slug LIMIT 1');
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare('INSERT INTO courses (title, slug, description, price, duration, is_active, created_at, updated_at) VALUES (:title, :slug, :description, :price, :duration, :is_active, NOW(), NOW())');
        return $stmt->execute($data);
    }

    public function update($id, array $data)
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare('UPDATE courses SET title = :title, slug = :slug, description = :description, price = :price, duration = :duration, is_active = :is_active, updated_at = NOW() WHERE id = :id');
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function countRegistrations($courseId)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM registrations WHERE course_id = :course_id');
        $stmt->execute([':course_id' => $courseId]);
        return (int)$stmt->fetchColumn();
    }
}
