<?php
/**
 * Wishlist Model
 * Handles user wishlist operations
 */

class Wishlist {
    protected $db;
    protected $table = 'wishlists';

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Add book to wishlist
     */
    public function add($userId, $bookId) {
        $sql = "INSERT INTO {$this->table} (user_id, book_id, created_at) VALUES (?, ?, NOW())";
        
        $this->db->query($sql);
        $this->db->bind('i', $userId);
        $this->db->bind('i', $bookId);
        
        return $this->db->execute();
    }

    /**
     * Remove book from wishlist
     */
    public function remove($userId, $bookId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND book_id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $userId);
        $this->db->bind('i', $bookId);
        
        return $this->db->execute();
    }

    /**
     * Get user wishlist
     */
    public function getByUser($userId, $limit = ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name FROM {$this->table} w
                LEFT JOIN books b ON w.book_id = b.id
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE w.user_id = ? AND b.is_active = 1
                ORDER BY w.created_at DESC
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $userId);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Check if book is in wishlist
     */
    public function exists($userId, $bookId) {
        $sql = "SELECT id FROM {$this->table} WHERE user_id = ? AND book_id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $userId);
        $this->db->bind('i', $bookId);
        
        return $this->db->rowCount() > 0;
    }

    /**
     * Get wishlist count
     */
    public function getCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $userId);
        $result = $this->db->single();
        return $result['count'];
    }

    /**
     * Clear wishlist
     */
    public function clear($userId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $userId);
        
        return $this->db->execute();
    }
}
?>
