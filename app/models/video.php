<?php
require_once __DIR__ . '/../../core/Model.php';

class Video extends Model {
    protected $table = 'videos';

    public function getAllActive($limit = null) {
        $sql = "
            SELECT v.*, c.name as category_name, c.slug as category_slug,
                   c.background_color, c.text_color
            FROM videos v
            INNER JOIN categories c ON v.category_id = c.id
            WHERE v.status = 'active'
            ORDER BY v.created_at DESC
        ";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql);
    }

    public function getFeatured($limit = 3) {
        $sql = "
            SELECT v.*, c.name as category_name, c.slug as category_slug,
                   c.background_color, c.text_color
            FROM videos v
            INNER JOIN categories c ON v.category_id = c.id
            WHERE v.status = 'active' AND v.is_featured = 1
            ORDER BY v.created_at DESC
            LIMIT {$limit}
        ";
        return $this->db->fetchAll($sql);
    }

    public function getBySlug($slug) {
        $sql = "
            SELECT v.*, c.name as category_name, c.slug as category_slug,
                   c.background_color, c.text_color
            FROM videos v
            INNER JOIN categories c ON v.category_id = c.id
            WHERE v.slug = ? AND v.status = 'active'
            LIMIT 1
        ";
        return $this->db->fetch($sql, [$slug]);
    }

    public function getByCategory($categoryId, $limit = null) {
        $sql = "
            SELECT v.*, c.name as category_name, c.slug as category_slug,
                   c.background_color, c.text_color
            FROM videos v
            INNER JOIN categories c ON v.category_id = c.id
            WHERE v.category_id = ? AND v.status = 'active'
            ORDER BY v.created_at DESC
        ";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, [$categoryId]);
    }

    public function search($query, $limit = 5) {
        $searchTerm = "%{$query}%";
        $sql = "
            SELECT v.*, c.name as category_name, c.slug as category_slug,
                   c.background_color, c.text_color
            FROM videos v
            INNER JOIN categories c ON v.category_id = c.id
            WHERE v.status = 'active' 
            AND (v.title LIKE ? OR v.description LIKE ?)
            ORDER BY v.created_at DESC
            LIMIT {$limit}
        ";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm]);
    }

    public function incrementView($id) {
        $sql = "UPDATE videos SET view_count = view_count + 1 WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function getAllForAdmin() {
        $sql = "
            SELECT v.*, c.name as category_name, a.username as created_by_username
            FROM videos v
            INNER JOIN categories c ON v.category_id = c.id
            LEFT JOIN admins a ON v.created_by = a.id
            WHERE v.status != 'deleted'
            ORDER BY v.created_at DESC
        ";
        return $this->db->fetchAll($sql);
    }

    public function softDelete($id) {
        return $this->update($id, ['status' => 'deleted']);
    }
}