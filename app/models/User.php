<?php
/**
 * User Model
 * Handles user-related database operations
 */

class User {
    protected $db;
    protected $table = 'users';

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Register new user
     */
    public function register($data) {
        $sql = "INSERT INTO {$this->table} (full_name, email, phone, password, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['full_name']);
        $this->db->bind('s', $data['email']);
        $this->db->bind('s', $data['phone']);
        $this->db->bind('s', password_hash($data['password'], PASSWORD_BCRYPT));
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $this->db->query($sql);
        $this->db->bind('s', $email);
        return $this->db->single();
    }

    /**
     * Find user by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->single();
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Update user
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET full_name = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['full_name']);
        $this->db->bind('s', $data['email']);
        $this->db->bind('s', $data['phone']);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Change password
     */
    public function changePassword($id, $newPassword) {
        $sql = "UPDATE {$this->table} SET password = ?, updated_at = NOW() WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('s', password_hash($newPassword, PASSWORD_BCRYPT));
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Get all users (admin)
     */
    public function getAll($limit = ADMIN_ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Get total users count
     */
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['count'];
    }

    /**
     * Update last login
     */
    public function updateLastLogin($id) {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Check email exists
     */
    public function emailExists($email) {
        $sql = "SELECT id FROM {$this->table} WHERE email = ?";
        $this->db->query($sql);
        $this->db->bind('s', $email);
        return $this->db->rowCount() > 0;
    }
}
?>
