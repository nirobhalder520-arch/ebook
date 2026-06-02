<?php
/**
 * Order Model
 * Handles order-related database operations
 */

class Order {
    protected $db;
    protected $table = 'orders';

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Create new order
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (order_number, user_id, total_amount, payment_method, 
                 customer_name, customer_email, customer_phone, shipping_address, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['order_number']);
        $this->db->bind('i', $data['user_id']);
        $this->db->bind('d', $data['total_amount']);
        $this->db->bind('s', $data['payment_method']);
        $this->db->bind('s', $data['customer_name']);
        $this->db->bind('s', $data['customer_email']);
        $this->db->bind('s', $data['customer_phone']);
        $this->db->bind('s', $data['shipping_address']);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Get order by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->single();
    }

    /**
     * Get order by order number
     */
    public function getByOrderNumber($orderNumber) {
        $sql = "SELECT * FROM {$this->table} WHERE order_number = ?";
        $this->db->query($sql);
        $this->db->bind('s', $orderNumber);
        return $this->db->single();
    }

    /**
     * Get user orders
     */
    public function getUserOrders($userId, $limit = ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $userId);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Get all orders (admin)
     */
    public function getAll($limit = ADMIN_ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Update order status
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET order_status = ?, updated_at = NOW() WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('s', $status);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($id, $paymentStatus) {
        $sql = "UPDATE {$this->table} SET payment_status = ?, updated_at = NOW() WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('s', $paymentStatus);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Update transaction ID
     */
    public function updateTransactionId($id, $transactionId) {
        $sql = "UPDATE {$this->table} SET transaction_id = ?, updated_at = NOW() WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('s', $transactionId);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Get order items
     */
    public function getItems($orderId) {
        $sql = "SELECT oi.*, b.title, b.cover_image FROM order_items oi
                LEFT JOIN books b ON oi.book_id = b.id
                WHERE oi.order_id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $orderId);
        return $this->db->resultset();
    }

    /**
     * Add order item
     */
    public function addItem($orderId, $bookId, $price, $quantity = 1) {
        $sql = "INSERT INTO order_items 
                (order_id, book_id, price, quantity, subtotal, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $subtotal = $price * $quantity;
        $this->db->query($sql);
        $this->db->bind('i', $orderId);
        $this->db->bind('i', $bookId);
        $this->db->bind('d', $price);
        $this->db->bind('i', $quantity);
        $this->db->bind('d', $subtotal);
        
        return $this->db->execute();
    }

    /**
     * Get total orders
     */
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['count'];
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_amount) as revenue FROM {$this->table} WHERE payment_status = 'paid'";
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['revenue'] ?? 0;
    }

    /**
     * Get monthly revenue
     */
    public function getMonthlyRevenue($year, $month) {
        $sql = "SELECT SUM(total_amount) as revenue FROM {$this->table} 
                WHERE YEAR(created_at) = ? AND MONTH(created_at) = ? AND payment_status = 'paid'";
        
        $this->db->query($sql);
        $this->db->bind('i', $year);
        $this->db->bind('i', $month);
        $result = $this->db->single();
        return $result['revenue'] ?? 0;
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber() {
        return 'ORD' . date('YmdHis') . rand(1000, 9999);
    }
}
?>
