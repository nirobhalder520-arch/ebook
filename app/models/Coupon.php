<?php
/**
 * Coupon Model
 * Handles coupon and discount operations
 */

class Coupon {
    protected $db;
    protected $table = 'coupons';

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Get coupon by code
     */
    public function getByCode($code) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE code = ? AND is_active = 1 
                AND (expiry_date IS NULL OR expiry_date >= CURDATE())";
        
        $this->db->query($sql);
        $this->db->bind('s', $code);
        return $this->db->single();
    }

    /**
     * Validate coupon
     */
    public function validate($code, $cartTotal) {
        $coupon = $this->getByCode($code);
        
        if (!$coupon) {
            return ['valid' => false, 'message' => 'Coupon not found or expired'];
        }

        if ($coupon['max_uses'] && $coupon['usage_count'] >= $coupon['max_uses']) {
            return ['valid' => false, 'message' => 'Coupon usage limit exceeded'];
        }

        if ($coupon['min_purchase'] && $cartTotal < $coupon['min_purchase']) {
            return ['valid' => false, 'message' => 'Minimum purchase amount not met'];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    /**
     * Calculate discount
     */
    public function calculateDiscount($coupon, $amount) {
        $discount = 0;

        if ($coupon['discount_type'] === 'percentage') {
            $discount = ($amount * $coupon['discount_value']) / 100;
        } else {
            $discount = $coupon['discount_value'];
        }

        // Apply max discount limit if set
        if ($coupon['max_discount'] && $discount > $coupon['max_discount']) {
            $discount = $coupon['max_discount'];
        }

        return $discount;
    }

    /**
     * Create coupon (admin)
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (code, discount_type, discount_value, max_uses, min_purchase, max_discount, expiry_date, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['code']);
        $this->db->bind('s', $data['discount_type']);
        $this->db->bind('d', $data['discount_value']);
        $this->db->bind('i', $data['max_uses'] ?? null);
        $this->db->bind('d', $data['min_purchase'] ?? 0);
        $this->db->bind('d', $data['max_discount'] ?? null);
        $this->db->bind('s', $data['expiry_date'] ?? null);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Get all coupons (admin)
     */
    public function getAll($limit = ADMIN_ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Update coupon
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                code = ?, discount_type = ?, discount_value = ?, 
                max_uses = ?, min_purchase = ?, max_discount = ?, 
                expiry_date = ?, is_active = ?, updated_at = NOW()
                WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['code']);
        $this->db->bind('s', $data['discount_type']);
        $this->db->bind('d', $data['discount_value']);
        $this->db->bind('i', $data['max_uses']);
        $this->db->bind('d', $data['min_purchase']);
        $this->db->bind('d', $data['max_discount']);
        $this->db->bind('s', $data['expiry_date']);
        $this->db->bind('i', $data['is_active']);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Increment usage count
     */
    public function incrementUsage($couponId) {
        $sql = "UPDATE {$this->table} SET usage_count = usage_count + 1 WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $couponId);
        
        return $this->db->execute();
    }

    /**
     * Delete coupon
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Get total coupons
     */
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['count'];
    }
}
?>
