<?php
namespace App\Models;

use App\Core\Model;

class CartModel extends Model {
    /**
     * Get Cart Items for Current User
     */
    public function getCartItems($userId) {
        $sql = "SELECT c.*, d.dish_name, d.img, d.category, p.pp, p.oprice, p.discount 
                FROM card c 
                LEFT JOIN dishes d ON c.p_id = d.rs_id 
                LEFT JOIN price p ON c.pric_id = p.id 
                WHERE c.userid = ? AND c.status = '0'";
        $items = $this->fetchAllPrepared($sql, [(string)$userId]);

        foreach ($items as &$item) {
            $item['resolved_img'] = ProductModel::resolveImage($item['img'] ?? '');
        }

        return $items;
    }

    /**
     * Get Total Cart Count
     */
    public function getCartCount($userId) {
        if (empty($userId)) return 0;
        $row = $this->fetchOnePrepared("SELECT COUNT(*) as cnt FROM card WHERE userid = ? AND status = '0'", [(string)$userId]);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Add Item to Cart
     */
    public function addToCart($userId, $productId, $priceId, $qty = 1) {
        $uStr = (string)$userId;
        $pStr = (string)$productId;
        $prStr = (string)$priceId;
        $qInt = (int)$qty;

        $checkSql = "SELECT * FROM card WHERE userid = ? AND p_id = ? AND pric_id = ? AND status = '0' LIMIT 1";
        $existing = $this->fetchOnePrepared($checkSql, [$uStr, $pStr, $prStr]);

        if ($existing) {
            $newQty = (int)$existing['qty'] + $qInt;
            return $this->execute("UPDATE card SET qty = ? WHERE id = ?", [$newQty, $existing['id']]);
        } else {
            // Get price amount
            $priceRow = $this->fetchOnePrepared("SELECT pp, gst FROM price WHERE id = ? LIMIT 1", [$prStr]);
            $amt = $priceRow ? $priceRow['pp'] : '0';
            $gst = $priceRow ? $priceRow['gst'] : '0';
            $date = date('d/m/Y');

            $insertSql = "INSERT INTO card (userid, p_id, pric_id, qty, amt, gst, tot, date, status) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, '0')";
            return $this->execute($insertSql, [$uStr, $pStr, $prStr, $qInt, $amt, $gst, $amt, $date]);
        }
    }

    /**
     * Remove Item from Cart
     */
    public function removeItem($cartId, $userId) {
        return $this->execute("DELETE FROM card WHERE id = ? AND userid = ?", [(string)$cartId, (string)$userId]);
    }
}
