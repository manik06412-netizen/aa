<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\WishlistModel;
use App\Models\UserModel;
use App\Core\Database;

class WishlistController extends Controller {
    public function index() {
        $userId = UserModel::getOrCreateSessionUser();
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        $uEsc = mysqli_real_escape_string($con, (string)$userId);
        $res = mysqli_query($con, "SELECT w.id as wish_id, w.pr_id, w.date as wish_date,
                                           d.d_id, d.rs_id, d.dish_name, d.img as d_img, d.category, d.cateid, d.ratings,
                                           COALESCE(MIN(p.pp), 0) as pp, 
                                           COALESCE(MAX(p.oprice), 0) as oprice,
                                           COALESCE(MIN(p.id), 0) as price_id
                                    FROM watch_list w 
                                    LEFT JOIN dishes d ON (w.pr_id = d.rs_id OR w.pr_id = d.d_id)
                                    LEFT JOIN price p ON (d.rs_id = p.pcode OR d.d_id = p.pcode) 
                                    WHERE w.userid = '$uEsc'
                                    GROUP BY w.id, d.d_id
                                    ORDER BY w.id DESC");
        $items = [];
        if ($res && mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $imgKey = !empty($row['d_img']) ? $row['d_img'] : 'img/products/hp_laptop.jpg';
                $row['resolved_img'] = \App\Models\ProductModel::resolveImage($imgKey);
                $items[] = $row;
            }
        }

        $data = [
            'title' => 'My Wishlist - Karuda Computers',
            'items' => $items,
            'user_id' => $userId
        ];

        $this->view('wishlist/wishlist', $data);
    }

    public function toggle() {
        $userId = UserModel::getOrCreateSessionUser();
        $productId = isset($_POST['p_id']) ? $_POST['p_id'] : (isset($_GET['p_id']) ? $_GET['p_id'] : (isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (isset($_POST['pr_id']) ? $_POST['pr_id'] : (isset($_GET['pr_id']) ? $_GET['pr_id'] : null)))));
        $img = isset($_POST['img']) ? $_POST['img'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';

        $db = Database::getInstance();
        $con = $db->getConnection();
        $uEsc = mysqli_real_escape_string($con, (string)$userId);

        if ($productId) {
            $pEsc = mysqli_real_escape_string($con, (string)$productId);
            $check = mysqli_query($con, "SELECT id FROM watch_list WHERE userid='$uEsc' AND pr_id='$pEsc' LIMIT 1");
            if ($check && mysqli_num_rows($check) > 0) {
                mysqli_query($con, "DELETE FROM watch_list WHERE userid='$uEsc' AND pr_id='$pEsc'");
                $list = 2;
                $status = 'removed';
            } else {
                $date = date('d/m/Y');
                mysqli_query($con, "INSERT INTO watch_list (userid, pr_id, date) VALUES ('$uEsc', '$pEsc', '$date')");
                $list = 3;
                $status = 'added';
            }

            $countRes = mysqli_query($con, "SELECT COUNT(*) as cnt FROM watch_list WHERE userid='$uEsc'");
            $countRow = mysqli_fetch_assoc($countRes);
            $count = $countRow ? (int)$countRow['cnt'] : 0;

            $this->json(['list' => $list, 'status' => $status, 'count' => $count]);
            return;
        }

        // Return count if no productId provided (for fav.php)
        $countRes = mysqli_query($con, "SELECT COUNT(*) as cnt FROM watch_list WHERE userid='$uEsc'");
        $countRow = mysqli_fetch_assoc($countRes);
        $count = $countRow ? (int)$countRow['cnt'] : 0;
        $this->json(['count' => $count]);
    }

    public function remove() {
        $userId = UserModel::getOrCreateSessionUser();
        $db = Database::getInstance();
        $con = $db->getConnection();

        $productId = isset($_POST['p_id']) ? $_POST['p_id'] : (isset($_GET['p_id']) ? $_GET['p_id'] : (isset($_GET['id']) ? $_GET['id'] : null));
        if ($productId) {
            $uEsc = mysqli_real_escape_string($con, (string)$userId);
            $pEsc = mysqli_real_escape_string($con, (string)$productId);
            $del = mysqli_query($con, "DELETE FROM watch_list WHERE userid='$uEsc' AND pr_id='$pEsc'");
            if ($del) {
                $this->json(['status' => 1, 'message' => 'Removed successfully']);
                return;
            }
        }
        $this->json(['status' => 0, 'error' => 'Could not remove item']);
    }
}
