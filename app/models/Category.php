<?php
/**
 * Category Model
 * Handles book category operations
 */

class Category {
    protected $db;
    protected $table = 'categories';

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Get all categories
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
        
        $this->db->query($sql);
        return $this->db->resultset();
    }

    /**
     * Get featured categories
     */
    public function getFeatured($limit = 8) {
        $sql = "SELECT * FROM {$this->table} WHERE is_featured = 1 ORDER BY name ASC LIMIT ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        return $this->db->resultset();
    }

    /**
     * Get category by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->single();
    }

    /**
     * Get category by slug
     */
    public function getBySlug($slug) {
        $sql = "SELECT * FROM {$this->table} WHERE slug = ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $slug);
        return $this->db->single();
    }

    /**
     * Get subcategories
     */
    public function getSubcategories($parentId) {
        $sql = "SELECT * FROM {$this->table} WHERE parent_id = ? ORDER BY name ASC";
        
        $this->db->query($sql);
        $this->db->bind('i', $parentId);
        return $this->db->resultset();
    }

    /**
     * Create category
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (name, slug, description, image, parent_id, is_featured, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['name']);
        $this->db->bind('s', $data['slug']);
        $this->db->bind('s', $data['description'] ?? null);
        $this->db->bind('s', $data['image'] ?? null);
        $this->db->bind('i', $data['parent_id'] ?? null);
        $this->db->bind('i', $data['is_featured'] ?? 0);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update category
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                name = ?, slug = ?, description = ?, image = ?, 
                parent_id = ?, is_featured = ?, updated_at = NOW()
                WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['name']);
        $this->db->bind('s', $data['slug']);
        $this->db->bind('s', $data['description']);
        $this->db->bind('s', $data['image']);
        $this->db->bind('i', $data['parent_id']);
        $this->db->bind('i', $data['is_featured']);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Delete category
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Get category with book count
     */
    public function getCategoryStats($id) {
        $sql = "SELECT c.*, COUNT(b.id) as book_count 
                FROM {$this->table} c
                LEFT JOIN books b ON c.id = b.category_id AND b.is_active = 1
                WHERE c.id = ?
                GROUP BY c.id";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->single();
    }

    /**
     * Get total categories
     */
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['count'];
    }
}
?>
