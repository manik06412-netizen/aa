<?php
namespace App\Models;

use App\Core\Model;

class OrderModel extends Model {
    /**
     * Get Orders by User ID
     */
    public function getOrdersByUserId($userId) {
        $sql = "SELECT * FROM chekout WHERE userid = ? ORDER BY id DESC";
        return $this->fetchAllPrepared($sql, [(string)$userId]);
    }

    /**
     * Get Single Order by Order Reference ID
     */
    public function getOrderByRefId($refId) {
        $sql = "SELECT * FROM chekout WHERE ref_id = ? LIMIT 1";
        return $this->fetchOnePrepared($sql, [(string)$refId]);
    }

    /**
     * Get Order Details for Tracking
     */
    public function trackOrder($orderId) {
        $sql = "SELECT * FROM chekout WHERE ref_id = ? OR id = ? LIMIT 1";
        return $this->fetchOnePrepared($sql, [(string)$orderId, (string)$orderId]);
    }

    /**
     * Save Delivery Address
     */
    public function saveDeliveryAddress($userId, $data) {
        $uStr = (string)$userId;
        $fname = trim($data['name'] ?? ($data['fname'] ?? ''));
        $email = trim($data['email'] ?? '');
        $mobile = trim($data['phone'] ?? ($data['mobile'] ?? ''));
        $flat = trim($data['address'] ?? ($data['flat'] ?? ''));
        $district = trim($data['city'] ?? ($data['district'] ?? ''));
        $state = trim($data['state'] ?? '');
        $pin = trim($data['pincode'] ?? ($data['pin'] ?? ''));
        $country = trim($data['country'] ?? 'India');
        $date = date('d/m/Y');

        $check = $this->fetchOnePrepared("SELECT id FROM address WHERE userid = ? LIMIT 1", [$uStr]);
        if ($check) {
            $sql = "UPDATE address SET fname=?, mobile=?, email=?, flat=?, country=?, state=?, district=?, pin=?, date=? WHERE userid=?";
            return $this->execute($sql, [$fname, $mobile, $email, $flat, $country, $state, $district, $pin, $date, $uStr]);
        } else {
            $sql = "INSERT INTO address (userid, fname, mobile, email, flat, country, state, district, pin, status, date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '1', ?)";
            return $this->execute($sql, [$uStr, $fname, $mobile, $email, $flat, $country, $state, $district, $pin, $date]);
        }
    }

    /**
     * Get Delivery Address
     */
    public function getDeliveryAddress($userId) {
        return $this->fetchOnePrepared("SELECT * FROM address WHERE userid = ? LIMIT 1", [(string)$userId]);
    }
}
