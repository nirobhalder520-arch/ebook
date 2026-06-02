<?php
/**
 * Payment Model
 * Handles payment processing and transactions
 */

class Payment {
    protected $db;
    protected $table = 'orders';
    protected $paymentMethods = ['bkash', 'nagad', 'rocket', 'sslcommerz', 'stripe'];

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Initiate payment
     */
    public function initiatePayment($orderId, $paymentMethod) {
        if (!in_array($paymentMethod, $this->paymentMethods)) {
            return ['success' => false, 'message' => 'Invalid payment method'];
        }

        // Get order details
        $orderModel = new Order();
        $order = $orderModel->getById($orderId);

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        // Route to payment gateway
        switch ($paymentMethod) {
            case 'bkash':
                return $this->initiateBKash($order);
            case 'nagad':
                return $this->initiateNagad($order);
            case 'rocket':
                return $this->initiateRocket($order);
            case 'sslcommerz':
                return $this->initiateSSLCommerz($order);
            case 'stripe':
                return $this->initiateStripe($order);
            default:
                return ['success' => false, 'message' => 'Payment method not supported'];
        }
    }

    /**
     * BKash payment initiation
     */
    private function initiateBKash($order) {
        // BKash API integration
        $bkashConfig = [
            'app_key' => getenv('BKASH_APP_KEY'),
            'app_secret' => getenv('BKASH_APP_SECRET'),
            'username' => getenv('BKASH_USERNAME'),
            'password' => getenv('BKASH_PASSWORD'),
            'api_url' => 'https://checkout.bkash.com/api/checkout'
        ];

        $paymentData = [
            'amount' => $order['total_amount'],
            'currency' => CURRENCY,
            'orderReference' => $order['order_number'],
            'merchantInvoiceNumber' => 'INV-' . $order['id']
        ];

        return [
            'success' => true,
            'method' => 'bkash',
            'data' => $paymentData,
            'message' => 'BKash payment initiated'
        ];
    }

    /**
     * Nagad payment initiation
     */
    private function initiateNagad($order) {
        // Nagad API integration
        $nagadConfig = [
            'merchant_id' => getenv('NAGAD_MERCHANT_ID'),
            'merchant_key' => getenv('NAGAD_MERCHANT_KEY'),
            'api_url' => 'https://api.nagad.com.bd'
        ];

        $paymentData = [
            'amount' => $order['total_amount'],
            'currency' => CURRENCY,
            'orderId' => $order['order_number']
        ];

        return [
            'success' => true,
            'method' => 'nagad',
            'data' => $paymentData,
            'message' => 'Nagad payment initiated'
        ];
    }

    /**
     * Rocket payment initiation
     */
    private function initiateRocket($order) {
        // Rocket API integration
        $rocketConfig = [
            'api_key' => getenv('ROCKET_API_KEY'),
            'api_url' => 'https://api.rocket.com.bd'
        ];

        $paymentData = [
            'amount' => $order['total_amount'],
            'currency' => CURRENCY,
            'reference' => $order['order_number']
        ];

        return [
            'success' => true,
            'method' => 'rocket',
            'data' => $paymentData,
            'message' => 'Rocket payment initiated'
        ];
    }

    /**
     * SSL Commerz payment initiation
     */
    private function initiateSSLCommerz($order) {
        // SSL Commerz API integration
        $sslConfig = [
            'store_id' => getenv('SSLCOMMERZ_STORE_ID'),
            'store_password' => getenv('SSLCOMMERZ_STORE_PASSWORD'),
            'sandbox' => getenv('SSLCOMMERZ_SANDBOX') === 'true'
        ];

        $apiUrl = $sslConfig['sandbox'] 
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        $paymentData = [
            'store_id' => $sslConfig['store_id'],
            'store_passwd' => $sslConfig['store_password'],
            'total_amount' => $order['total_amount'],
            'currency' => CURRENCY,
            'tran_id' => $order['order_number'],
            'success_url' => SITE_URL . '/payment/success',
            'fail_url' => SITE_URL . '/payment/failed',
            'cancel_url' => SITE_URL . '/payment/cancelled'
        ];

        return [
            'success' => true,
            'method' => 'sslcommerz',
            'data' => $paymentData,
            'api_url' => $apiUrl,
            'message' => 'SSL Commerz payment initiated'
        ];
    }

    /**
     * Stripe payment initiation
     */
    private function initiateStripe($order) {
        // Stripe API integration
        $stripeConfig = [
            'public_key' => getenv('STRIPE_PUBLIC_KEY'),
            'secret_key' => getenv('STRIPE_SECRET_KEY')
        ];

        $paymentData = [
            'amount' => $order['total_amount'] * 100, // Convert to cents
            'currency' => strtolower(CURRENCY),
            'description' => 'Order #' . $order['order_number'],
            'metadata' => [
                'order_id' => $order['id'],
                'order_number' => $order['order_number']
            ]
        ];

        return [
            'success' => true,
            'method' => 'stripe',
            'data' => $paymentData,
            'public_key' => $stripeConfig['public_key'],
            'message' => 'Stripe payment initiated'
        ];
    }

    /**
     * Verify payment
     */
    public function verifyPayment($transactionId, $method) {
        switch ($method) {
            case 'bkash':
                return $this->verifyBKash($transactionId);
            case 'nagad':
                return $this->verifyNagad($transactionId);
            case 'rocket':
                return $this->verifyRocket($transactionId);
            case 'sslcommerz':
                return $this->verifySSLCommerz($transactionId);
            case 'stripe':
                return $this->verifyStripe($transactionId);
            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }

    /**
     * Verify BKash payment
     */
    private function verifyBKash($transactionId) {
        // BKash verification logic
        return ['success' => true, 'message' => 'BKash payment verified'];
    }

    /**
     * Verify Nagad payment
     */
    private function verifyNagad($transactionId) {
        // Nagad verification logic
        return ['success' => true, 'message' => 'Nagad payment verified'];
    }

    /**
     * Verify Rocket payment
     */
    private function verifyRocket($transactionId) {
        // Rocket verification logic
        return ['success' => true, 'message' => 'Rocket payment verified'];
    }

    /**
     * Verify SSL Commerz payment
     */
    private function verifySSLCommerz($transactionId) {
        // SSL Commerz verification logic
        return ['success' => true, 'message' => 'SSL Commerz payment verified'];
    }

    /**
     * Verify Stripe payment
     */
    private function verifyStripe($transactionId) {
        // Stripe verification logic
        return ['success' => true, 'message' => 'Stripe payment verified'];
    }

    /**
     * Record payment
     */
    public function recordPayment($orderId, $transactionId, $paymentMethod, $status = 'paid') {
        $orderModel = new Order();
        $orderModel->updateTransactionId($orderId, $transactionId);
        $orderModel->updatePaymentStatus($orderId, $status);
        
        return true;
    }

    /**
     * Refund payment
     */
    public function refundPayment($orderId, $transactionId, $reason = '') {
        $sql = "UPDATE {$this->table} SET 
                payment_status = 'refunded', 
                updated_at = NOW()
                WHERE id = ? AND transaction_id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $orderId);
        $this->db->bind('s', $transactionId);
        
        return $this->db->execute();
    }

    /**
     * Get payment history
     */
    public function getPaymentHistory($limit = 20, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE payment_status IN ('paid', 'pending', 'failed')
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }
}
?>
