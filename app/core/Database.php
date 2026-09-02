<?php
/**
 * Karuda Computers - Database Connection Wrapper (Singleton with PDO Prepared Statements & MySQLi)
 */
namespace App\Core;

class Database {
    private static $instance = null;
    private $mysqli;
    private $pdo;

    private function __construct() {
        @mysqli_report(MYSQLI_REPORT_OFF);
        try {
            // MySQLi Connection
            $this->mysqli = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (!$this->mysqli) {
                die("<div style='font-family:sans-serif;padding:30px;background:#fff3f3;border:1px solid #ffcaca;border-radius:8px;margin:50px auto;max-width:600px;'>
                    <h2 style='color:#d9534f;'>Database Connection Error</h2>
                    <p><strong>Error Details:</strong> " . htmlspecialchars(mysqli_connect_error()) . "</p>
                    <p>Please check your database credentials (Host, User, Password, Database Name) in <code>app/config/config.php</code>.</p>
                </div>");
            }
            mysqli_set_charset($this->mysqli, 'utf8mb4');
        } catch (\Throwable $e) {
            die("<div style='font-family:sans-serif;padding:30px;background:#fff3f3;border:1px solid #ffcaca;border-radius:8px;margin:50px auto;max-width:600px;'>
                <h2 style='color:#d9534f;'>Database Connection Exception</h2>
                <p><strong>Error Details:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                <p>Please check your database credentials in <code>app/config/config.php</code>.</p>
            </div>");
        }

        // PDO Connection
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new \PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\Throwable $e) {
            // Optional PDO
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->mysqli;
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function query($sql) {
        return mysqli_query($this->mysqli, $sql);
    }

    /**
     * Execute Prepared Query with Parameters
     */
    public function execute($sql, $params = []) {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } else {
            // Fallback to MySQLi prepared statements
            $stmt = $this->mysqli->prepare($sql);
            if ($stmt && !empty($params)) {
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
            }
            if ($stmt) {
                $stmt->execute();
                return $stmt->get_result();
            }
            return false;
        }
    }

    public function fetchAllPrepared($sql, $params = []) {
        $stmt = $this->execute($sql, $params);
        if ($this->pdo && $stmt instanceof \PDOStatement) {
            return $stmt->fetchAll();
        } elseif ($stmt instanceof \mysqli_result) {
            $data = [];
            while ($row = $stmt->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
        return [];
    }

    public function fetchOnePrepared($sql, $params = []) {
        $stmt = $this->execute($sql, $params);
        if ($this->pdo && $stmt instanceof \PDOStatement) {
            $row = $stmt->fetch();
            return $row ?: null;
        } elseif ($stmt instanceof \mysqli_result) {
            return $stmt->fetch_assoc() ?: null;
        }
        return null;
    }

    public function escape($str) {
        return mysqli_real_escape_string($this->mysqli, (string)$str);
    }

    public function insertId() {
        if ($this->pdo) {
            return $this->pdo->lastInsertId();
        }
        return mysqli_insert_id($this->mysqli);
    }
}
