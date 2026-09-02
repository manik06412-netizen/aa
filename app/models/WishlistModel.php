<?php
namespace App\Models;

use App\Core\Model;

class WishlistModel extends Model {
    /**
     * Get Wishlist Count for User
     */
    public function getWishlistCount($userId) {
        if (empty($userId)) return 0;
        $row = $this->fetchOnePrepared("SELECT COUNT(*) as cnt FROM watch_list WHERE userid = ?", [(string)$userId]);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Toggle Product in Wishlist
     */
    public function toggleWishlist($userId, $productId, $img = '', $name = '') {
        $uStr = (string)$userId;
        $pStr = (string)$productId;

        $check = $this->fetchOnePrepared("SELECT id FROM watch_list WHERE userid = ? AND pr_id = ? LIMIT 1", [$uStr, $pStr]);
        if ($check) {
            $this->execute("DELETE FROM watch_list WHERE userid = ? AND pr_id = ?", [$uStr, $pStr]);
            return ['status' => 'removed'];
        } else {
            $date = date('d/m/Y');
            $this->execute("INSERT INTO watch_list (userid, pr_id, date) VALUES (?, ?, ?)", [$uStr, $pStr, $date]);
            return ['status' => 'added'];
        }
    }
}
