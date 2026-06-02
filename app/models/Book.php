<?php
/**
 * Book Model
 * Handles book-related database operations
 */

class Book {
    protected $db;
    protected $table = 'books';

    public function __construct() {
        require_once CONFIG_PATH . '/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Get all books with pagination
     */
    public function getAll($limit = ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name, p.name as publisher_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                LEFT JOIN publishers p ON b.publisher_id = p.id
                WHERE b.is_active = 1
                ORDER BY b.created_at DESC
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Get book by ID
     */
    public function getById($id) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name, p.name as publisher_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                LEFT JOIN publishers p ON b.publisher_id = p.id
                WHERE b.id = ? AND b.is_active = 1";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->single();
    }

    /**
     * Get book by slug
     */
    public function getBySlug($slug) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name, p.name as publisher_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                LEFT JOIN publishers p ON b.publisher_id = p.id
                WHERE b.slug = ? AND b.is_active = 1";
        
        $this->db->query($sql);
        $this->db->bind('s', $slug);
        return $this->db->single();
    }

    /**
     * Get featured books
     */
    public function getFeatured($limit = 6) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.is_featured = 1 AND b.is_active = 1
                ORDER BY b.created_at DESC
                LIMIT ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        return $this->db->resultset();
    }

    /**
     * Get bestselling books
     */
    public function getBestsellers($limit = 6) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.is_active = 1
                ORDER BY b.sales_count DESC
                LIMIT ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        return $this->db->resultset();
    }

    /**
     * Get new arrivals
     */
    public function getNewArrivals($limit = 6) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.is_active = 1
                ORDER BY b.created_at DESC
                LIMIT ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $limit);
        return $this->db->resultset();
    }

    /**
     * Get books by category
     */
    public function getByCategory($categoryId, $limit = ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.category_id = ? AND b.is_active = 1
                ORDER BY b.created_at DESC
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $categoryId);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Get books by author
     */
    public function getByAuthor($authorId, $limit = ITEMS_PER_PAGE, $offset = 0) {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.author_id = ? AND b.is_active = 1
                ORDER BY b.created_at DESC
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $authorId);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Search books
     */
    public function search($query, $limit = ITEMS_PER_PAGE, $offset = 0) {
        $searchTerm = '%' . $query . '%';
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name
                FROM {$this->table} b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE (b.title LIKE ? OR b.subtitle LIKE ? OR a.name LIKE ? OR c.name LIKE ?)
                AND b.is_active = 1
                ORDER BY CASE 
                    WHEN b.title LIKE ? THEN 1
                    WHEN b.subtitle LIKE ? THEN 2
                    ELSE 3
                END ASC
                LIMIT ? OFFSET ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        return $this->db->resultset();
    }

    /**
     * Add book (admin)
     */
    public function add($data) {
        $sql = "INSERT INTO {$this->table} 
                (title, subtitle, slug, author_id, publisher_id, category_id, price, 
                 discount_price, description, isbn, language, total_pages, 
                 published_date, cover_image, pdf_file, preview_pdf, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['title']);
        $this->db->bind('s', $data['subtitle'] ?? null);
        $this->db->bind('s', $data['slug']);
        $this->db->bind('i', $data['author_id']);
        $this->db->bind('i', $data['publisher_id']);
        $this->db->bind('i', $data['category_id']);
        $this->db->bind('d', $data['price']);
        $this->db->bind('d', $data['discount_price'] ?? null);
        $this->db->bind('s', $data['description']);
        $this->db->bind('s', $data['isbn'] ?? null);
        $this->db->bind('s', $data['language'] ?? 'Bangla');
        $this->db->bind('i', $data['total_pages'] ?? null);
        $this->db->bind('s', $data['published_date'] ?? null);
        $this->db->bind('s', $data['cover_image'] ?? null);
        $this->db->bind('s', $data['pdf_file'] ?? null);
        $this->db->bind('s', $data['preview_pdf'] ?? null);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update book
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                title = ?, subtitle = ?, slug = ?, author_id = ?, publisher_id = ?, 
                category_id = ?, price = ?, discount_price = ?, description = ?, 
                isbn = ?, language = ?, total_pages = ?, published_date = ?, 
                updated_at = NOW()
                WHERE id = ?";
        
        $this->db->query($sql);
        $this->db->bind('s', $data['title']);
        $this->db->bind('s', $data['subtitle']);
        $this->db->bind('s', $data['slug']);
        $this->db->bind('i', $data['author_id']);
        $this->db->bind('i', $data['publisher_id']);
        $this->db->bind('i', $data['category_id']);
        $this->db->bind('d', $data['price']);
        $this->db->bind('d', $data['discount_price']);
        $this->db->bind('s', $data['description']);
        $this->db->bind('s', $data['isbn']);
        $this->db->bind('s', $data['language']);
        $this->db->bind('i', $data['total_pages']);
        $this->db->bind('s', $data['published_date']);
        $this->db->bind('i', $id);
        
        return $this->db->execute();
    }

    /**
     * Delete book (soft delete)
     */
    public function delete($id) {
        $sql = "UPDATE {$this->table} SET is_active = 0, updated_at = NOW() WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Get total books count
     */
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE is_active = 1";
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['count'];
    }

    /**
     * Increment sales count
     */
    public function incrementSales($id) {
        $sql = "UPDATE {$this->table} SET sales_count = sales_count + 1 WHERE id = ?";
        $this->db->query($sql);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }

    /**
     * Update rating
     */
    public function updateRating($id) {
        $sql = "UPDATE {$this->table} b SET 
                rating = (SELECT AVG(rating) FROM reviews WHERE book_id = ? AND status = 'approved'),
                total_reviews = (SELECT COUNT(*) FROM reviews WHERE book_id = ? AND status = 'approved')
                WHERE b.id = ?";
        
        $this->db->query($sql);
        $this->db->bind('i', $id);
        $this->db->bind('i', $id);
        $this->db->bind('i', $id);
        return $this->db->execute();
    }
}
?>
