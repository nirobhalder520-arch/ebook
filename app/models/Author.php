<?php
/**
 * Author Model
 * Handles author operations
 */

class Author {
    protected $db;
    protected $table = 'authors';

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Get all authors
     */
    public function getAll($limit = ADMIN_ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Get author by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->single();
    }

    /**
     * Get author by slug
     */
    public function getBySlug($slug) {
        $sql = "SELECT * FROM {$this->table} WHERE slug = ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $slug);
        return $this->db->single();
    }

    /**
     * Create author
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (name, slug, email, phone, bio, image, website, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['name']);
        $this->db->bind('s', $data['slug']);
        $this->db->bind('s', $data['email'] ?? null);
        $this->db->bind('s', $data['phone'] ?? null);
        $this->db->bind('s', $data['bio'] ?? null);
        $this->db->bind('s', $data['image'] ?? null);
        $this->db->bind('s', $data['website'] ?? null);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update author
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                name = ?, slug = ?, email = ?, phone = ?, 
                bio = ?, image = ?, website = ?, updated_at = NOW()
                WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['name']);
        $this->db->bind('s', $data['slug']);
        $this->db->bind('s', $data['email']);
        $this->db->bind('s', $data['phone']);
        $this->db->bind('s', $data['bio']);
        $this->db->bind('s', $data['image']);
        $this->db->bind('s', $data['website']);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Delete author
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Get author with book count
     */
    public function getAuthorStats($id) {
        $sql = "SELECT a.*, COUNT(b.id) as book_count 
                FROM {$this->table} a
                LEFT JOIN books b ON a.id = b.author_id AND b.is_active = 1
                WHERE a.id = ?
                GROUP BY a.id";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->single();
    }

    /**
     * Search authors
     */
    public function search($query, $limit = 20) {
        $searchTerm = '%' . $query . '%';
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE ? OR email LIKE ?
                ORDER BY name ASC
                LIMIT ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('i', $limit);
        return $this->db->resultset();
    }

    /**
     * Get total authors
     */
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['count'];
    }
}
?>
