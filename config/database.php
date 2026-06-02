<?php
/**
 * BoiMarket - Database Configuration
 * Secure database connection configuration
 */

// Database credentials from environment or defaults
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'boimarket');
define('DB_PORT', getenv('DB_PORT') ?: 3306);
define('DB_CHARSET', 'utf8mb4');

/**
 * MySQLi Database Connection Class
 * Singleton pattern with prepared statements
 */
class Database {
    private static $instance = null;
    private $connection;
    private $query;
    private $show_errors = true;
    private $query_closed = true;

    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Constructor - Establish connection
     */
    private function __construct() {
        $this->connection = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME,
            DB_PORT
        );

        if (mysqli_connect_error()) {
            $this->error('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

        $this->connection->set_charset(DB_CHARSET);
    }

    /**
     * Execute query with prepared statements
     */
    public function query($sql = '') {
        if (!$this->query_closed) {
            $this->query->close();
        }

        $this->query = $this->connection->prepare($sql);

        if ($this->connection->errno) {
            $this->error('Unable to prepare statement: ' . $sql);
        }

        $this->query_closed = false;
        return $this;
    }

    /**
     * Bind parameters to query
     */
    public function bind($param, $value = null, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = MYSQLI_TYPE_LONG;
                    break;
                case is_float($value):
                    $type = MYSQLI_TYPE_DOUBLE;
                    break;
                case is_string($value):
                    $type = MYSQLI_TYPE_STRING;
                    break;
                default:
                    $type = MYSQLI_TYPE_NULL;
            }
        }

        if (!$this->query->bind_param($type, $value)) {
            $this->error('Unable to bind parameter');
        }

        return $this;
    }

    /**
     * Execute the prepared query
     */
    public function execute() {
        if (!$this->query->execute()) {
            $this->error('Unable to execute statement: ' . $this->query->error);
        }

        return $this;
    }

    /**
     * Get result set
     */
    public function resultset() {
        $this->execute();
        $result = $this->query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get single row
     */
    public function single() {
        $this->execute();
        $result = $this->query->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get row count
     */
    public function rowCount() {
        return $this->query->num_rows;
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }

    /**
     * Error handler
     */
    private function error($error = '') {
        if ($this->show_errors) {
            die('<p style="color: red; font-family: Arial; padding: 20px;">Database Error: ' . htmlspecialchars($error) . '</p>');
        }
    }

    /**
     * Close database connection
     */
    public function closeConnection() {
        if ($this->query_closed === false) {
            $this->query->close();
        }
        return $this->connection->close();
    }
}

// Initialize database connection
$db = Database::getInstance();
?>
