<?php
/**
 * Karuda Computers - Base Model Class (with Prepared Statements & Parameterized Query Helpers)
 */
namespace App\Core;

abstract class Model {
    protected $db;
    protected $con;
    protected $pdo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->con = $this->db->getConnection();
        $this->pdo = $this->db->getPdo();
    }

    /**
     * Execute Raw SQL Query
     */
    protected function query($sql) {
        return $this->db->query($sql);
    }

    /**
     * Execute Parameterized Prepared Statement Query
     */
    protected function execute($sql, $params = []) {
        return $this->db->execute($sql, $params);
    }

    /**
     * Fetch Single Row using Prepared Statement
     */
    protected function fetchOnePrepared($sql, $params = []) {
        return $this->db->fetchOnePrepared($sql, $params);
    }

    /**
     * Fetch All Rows using Prepared Statement
     */
    protected function fetchAllPrepared($sql, $params = []) {
        return $this->db->fetchAllPrepared($sql, $params);
    }

    protected function escape($str) {
        return $this->db->escape($str);
    }

    protected function fetchAll($result) {
        $data = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    protected function fetchOne($result) {
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
}
