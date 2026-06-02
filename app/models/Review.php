<?php
/**
 * Review Model
 * Handles book review operations
 */

class Review {
    protected $db;
    protected $table = 'reviews';

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Add review
     */
    public function add($data) {
        $sql = "INSERT INTO {$this->table} 
                (book_id, user_id, rating, title, review, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $this->db->query($sql);
        $this->db->bind('i', $data['book_id']);
        $this->db->bind('i', $data['user_id']);
        $this->db->bind('i', $data['rating']);
        $this->db->bind('s', $data['title']);
        $this->db->bind('s', $data['review']);
        
        if ($this->db->execute()) {
            // Update book rating
            $bookModel = new Book();
            $bookModel->updateRating($data['book_id']);
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Get reviews by book
     */
    public function getByBook($bookId, $limit = 10, $offset = 0) {
        $sql = "SELECT r.*, u.full_name, u.avatar FROM {$this->table} r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.book_id = ? AND r.status = 'approved'
                ORDER BY r.created_at DESC
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $bookId);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Get pending reviews (admin)
     */
    public function getPending($limit = ADMIN_ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT r.*, u.full_name, b.title FROM {$this->table} r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN books b ON r.book_id = b.id
                WHERE r.status = 'pending'
                ORDER BY r.created_at DESC
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Get user review for book
     */
    public function getUserReview($bookId, $userId) {
        $sql = "SELECT * FROM {$this->table} WHERE book_id = ? AND user_id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $bookId);
        $this->db->bind('i', $userId);
        return $this->db->single();
    }

    /**
     * Update review
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                rating = ?, title = ?, review = ?, updated_at = NOW()
                WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $data['rating']);
        $this->db->bind('s', $data['title']);
        $this->db->bind('s', $data['review']);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Approve review (admin)
     */
    public function approve($id) {
        $sql = "UPDATE {$this->table} SET status = 'approved', updated_at = NOW() WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Reject review (admin)
     */
    public function reject($id) {
        $sql = "UPDATE {$this->table} SET status = 'rejected', updated_at = NOW() WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Delete review
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Get average rating for book
     */
    public function getAverageRating($bookId) {
        $sql = "SELECT AVG(rating) as average FROM {$this->table} 
                WHERE book_id = ? AND status = 'approved'";
        
        $this->db->query($sql);
        $this->db->bind('i', $bookId);
        $result = $this->db->single();
        return round($result['average'] ?? 0, 2);
    }

    /**
     * Get total reviews
     */
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'approved'";
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['count'];
    }
}
?>
