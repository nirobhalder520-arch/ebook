<?php
/**
 * Cart Service
 * Handles shopping cart operations
 */

class CartService {
    protected $sessionKey = 'shopping_cart';
    protected $couponModel;

    public function __construct() {
        $this->couponModel = new Coupon();
        // Initialize cart in session if not exists
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [];
        }
    }

    /**
     * Add item to cart
     */
    public function addItem($bookId, $price, $quantity = 1) {
        $cart = $_SESSION[$this->sessionKey];

        // Check if item already exists
        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity'] += $quantity;
        } else {
            $cart[$bookId] = [
                'book_id' => $bookId,
                'price' => $price,
                'quantity' => $quantity
            ];
        }

        $_SESSION[$this->sessionKey] = $cart;
        return ['success' => true, 'message' => 'Item added to cart'];
    }

    /**
     * Remove item from cart
     */
    public function removeItem($bookId) {
        $cart = $_SESSION[$this->sessionKey];

        if (isset($cart[$bookId])) {
            unset($cart[$bookId]);
            $_SESSION[$this->sessionKey] = $cart;
            return ['success' => true, 'message' => 'Item removed from cart'];
        }

        return ['success' => false, 'message' => 'Item not found in cart'];
    }

    /**
     * Update item quantity
     */
    public function updateQuantity($bookId, $quantity) {
        $cart = $_SESSION[$this->sessionKey];

        if ($quantity <= 0) {
            return $this->removeItem($bookId);
        }

        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity'] = $quantity;
            $_SESSION[$this->sessionKey] = $cart;
            return ['success' => true, 'message' => 'Quantity updated'];
        }

        return ['success' => false, 'message' => 'Item not found in cart'];
    }

    /**
     * Get cart items
     */
    public function getItems() {
        return $_SESSION[$this->sessionKey] ?? [];
    }

    /**
     * Get cart count
     */
    public function getCount() {
        $count = 0;
        foreach ($_SESSION[$this->sessionKey] ?? [] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Get cart subtotal
     */
    public function getSubtotal() {
        $subtotal = 0;
        foreach ($_SESSION[$this->sessionKey] ?? [] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    /**
     * Apply coupon
     */
    public function applyCoupon($code) {
        $subtotal = $this->getSubtotal();
        $validation = $this->couponModel->validate($code, $subtotal);

        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }

        $coupon = $validation['coupon'];
        $discount = $this->couponModel->calculateDiscount($coupon, $subtotal);

        $_SESSION['coupon'] = [
            'code' => $code,
            'id' => $coupon['id'],
            'discount' => $discount,
            'discount_type' => $coupon['discount_type'],
            'discount_value' => $coupon['discount_value']
        ];

        return [
            'success' => true,
            'message' => 'Coupon applied successfully',
            'discount' => $discount
        ];
    }

    /**
     * Remove coupon
     */
    public function removeCoupon() {
        unset($_SESSION['coupon']);
        return ['success' => true, 'message' => 'Coupon removed'];
    }

    /**
     * Get applied coupon
     */
    public function getCoupon() {
        return $_SESSION['coupon'] ?? null;
    }

    /**
     * Calculate total
     */
    public function getTotal() {
        $subtotal = $this->getSubtotal();
        $discount = 0;

        if (isset($_SESSION['coupon'])) {
            $discount = $_SESSION['coupon']['discount'];
        }

        $tax = ($subtotal - $discount) * (TAX_RATE / 100);
        $total = $subtotal - $discount + $tax;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total
        ];
    }

    /**
     * Clear cart
     */
    public function clear() {
        $_SESSION[$this->sessionKey] = [];
        unset($_SESSION['coupon']);
        return ['success' => true, 'message' => 'Cart cleared'];
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty() {
        return empty($_SESSION[$this->sessionKey]);
    }

    /**
     * Get cart details
     */
    public function getDetails() {
        $items = $this->getItems();
        $total = $this->getTotal();

        return [
            'items' => $items,
            'item_count' => $this->getCount(),
            'subtotal' => $total['subtotal'],
            'discount' => $total['discount'],
            'tax' => $total['tax'],
            'total' => $total['total'],
            'coupon' => $this->getCoupon()
        ];
    }
}
?>
