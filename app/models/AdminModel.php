<?php
namespace App\Models;

use App\Core\Model;

class AdminModel extends Model {
    /**
     * Authenticate Main Admin
     */
    public function authenticateMainAdmin($username, $password) {
        $uEsc = $this->escape(trim($username));
        $pHash = md5(trim($password));
        $sql = "SELECT * FROM admin WHERE BINARY username = '$uEsc' AND password = '$pHash' LIMIT 1";
        return $this->fetchOne($this->query($sql));
    }

    /**
     * Authenticate UK Admin (avadmin)
     */
    public function authenticateUkAdmin($email, $password) {
        $eEsc = $this->escape(trim($email));
        $pHash = md5(trim($password));
        $sql = "SELECT * FROM tbl_user WHERE email = '$eEsc' AND password = '$pHash' AND status = 'Active' LIMIT 1";
        return $this->fetchOne($this->query($sql));
    }

    /**
     * Get Main Admin Dashboard Statistics
     */
    public function getMainAdminStats() {
        $users = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM user"));
        $vendors = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM vendor"));
        $btypes = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM btype"));
        $categories = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM res_category"));
        $products = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM dishes"));
        $orders = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM chekout"));

        return [
            'users' => $users ? (int)$users['c'] : 0,
            'vendors' => $vendors ? (int)$vendors['c'] : 0,
            'btypes' => $btypes ? (int)$btypes['c'] : 0,
            'categories' => $categories ? (int)$categories['c'] : 0,
            'products' => $products ? (int)$products['c'] : 0,
            'orders' => $orders ? (int)$orders['c'] : 0,
        ];
    }

    /**
     * Get UK Admin Dashboard Statistics
     */
    public function getUkAdminStats() {
        $categories = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM res_category"));
        $customers = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM user"));
        $subscribers = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM tbl_subscriber"));
        $products = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM dishes"));
        $orders = $this->fetchOne($this->query("SELECT COUNT(*) as c FROM chekout"));

        return [
            'categories' => $categories ? (int)$categories['c'] : 0,
            'customers' => $customers ? (int)$customers['c'] : 0,
            'subscribers' => $subscribers ? (int)$subscribers['c'] : 0,
            'products' => $products ? (int)$products['c'] : 0,
            'orders' => $orders ? (int)$orders['c'] : 0,
        ];
    }
}
