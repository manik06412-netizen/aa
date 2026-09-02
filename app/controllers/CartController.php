<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CartModel;
use App\Models\UserModel;
use App\Core\Database;

class CartController extends Controller {
    public function index() {
        $userId = UserModel::getOrCreateSessionUser();
        $cartModel = $this->model('CartModel');
        $items = $cartModel->getCartItems($userId);

        $data = [
            'title' => 'Shopping Cart - Karuda Computers',
            'items' => $items
        ];

        $this->view('cart/cart', $data);
    }

    public function add() {
        $userId = UserModel::getOrCreateSessionUser();
        $cartModel = $this->model('CartModel');

        $productId = isset($_POST['prdid']) ? $_POST['prdid'] : (isset($_GET['prdid']) ? $_GET['prdid'] : (isset($_POST['productId']) ? $_POST['productId'] : null));
        $priceId = isset($_POST['pid']) ? $_POST['pid'] : (isset($_GET['pid']) ? $_GET['pid'] : (isset($_POST['price']) ? $_POST['price'] : null));
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
        if ($qty < 1) $qty = 1;

        $db = Database::getInstance();
        $con = $db->getConnection();
        $uEsc = mysqli_real_escape_string($con, (string)$userId);
        $pEsc = mysqli_real_escape_string($con, (string)$productId);
        $prEsc = mysqli_real_escape_string($con, (string)$priceId);
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_POST['prdid']) || isset($_POST['productId']);

        if ($productId) {
            if (!$priceId || $priceId == '0') {
                $pFind = mysqli_query($con, "SELECT id, pp, gst FROM price WHERE pcode='$pEsc' LIMIT 1");
                if ($pFind && ($pRow = mysqli_fetch_assoc($pFind))) {
                    $priceId = $pRow['id'];
                    $prEsc = mysqli_real_escape_string($con, (string)$priceId);
                }
            }
            if ($priceId) {
                $check = mysqli_query($con, "SELECT id, qty FROM card WHERE userid='$uEsc' AND p_id='$pEsc' AND pric_id='$prEsc' AND status='0'");
                if ($check && mysqli_num_rows($check) > 0) {
                    // Product already in cart — increment quantity
                    $existingRow = mysqli_fetch_assoc($check);
                    $existingCartId  = (int)$existingRow['id'];
                    $existingQty     = (int)$existingRow['qty'];
                    $newQty          = $existingQty + $qty;

                    // Cap at available stock
                    $stockQ = mysqli_query($con, "SELECT COALESCE(SUM(total_stock),0) AS stock FROM price WHERE pcode='$pEsc'");
                    $stockRow = $stockQ ? mysqli_fetch_assoc($stockQ) : null;
                    $maxStock = $stockRow ? (int)$stockRow['stock'] : 9999;
                    if ($newQty > $maxStock && $maxStock > 0) {
                        $newQty = $maxStock;
                    }

                    // Recalculate amount
                    $sel = mysqli_query($con, "SELECT pp, gst FROM price WHERE id='$prEsc'");
                    $unitPrice = 0;
                    if ($sel && ($ro = mysqli_fetch_array($sel))) {
                        $unitPrice = (float)$ro['pp'];
                    }
                    $newAmt = $unitPrice * $newQty;
                    mysqli_query($con, "UPDATE card SET qty='$newQty', amt='$newAmt', tot='$newAmt' WHERE id='$existingCartId' AND userid='$uEsc'");

                    $cntQ = mysqli_query($con, "SELECT COUNT(*) as cnt FROM card WHERE userid='$uEsc' AND status='0'");
                    $cntRow = mysqli_fetch_assoc($cntQ);
                    $numCart = $cntRow ? (int)$cntRow['cnt'] : 0;

                    if ($isAjax) {
                        $this->json(['status' => 3, 'message' => "Quantity updated to $newQty!", 'number_of_cart' => $numCart, 'new_qty' => $newQty]);
                        return;
                    }
                } else {
                    $sel = mysqli_query($con, "SELECT * FROM price WHERE id='$prEsc'");
                    $amt = 0;
                    $gst = 0;
                    if ($sel && ($ro = mysqli_fetch_array($sel))) {
                        $amt = (float)$ro['pp'];
                        $gst = (float)$ro['gst'];
                    }
                    $date = date('d/m/Y');
                    $sta = 0;
                    mysqli_query($con, "INSERT INTO card VALUES(null, '$uEsc', '$pEsc', '$prEsc', '$qty', '$amt', '$gst', '$amt', '$date', '$sta')");
                    
                    $cntQ = mysqli_query($con, "SELECT COUNT(*) as cnt FROM card WHERE userid='$uEsc' AND status='0'");
                    $cntRow = mysqli_fetch_assoc($cntQ);
                    $numCart = $cntRow ? (int)$cntRow['cnt'] : 0;

                    if ($isAjax) {
                        $this->json(['status' => 2, 'message' => 'Hooray! Item added to the cart!', 'number_of_cart' => $numCart]);
                        return;
                    }
                }
            }
        }

        $this->redirect(BASE_URL . 'shopping_cart.php');
    }

    public function remove($id = null) {
        $userId = UserModel::getOrCreateSessionUser();
        $db = Database::getInstance();
        $con = $db->getConnection();

        $cartId = $id ?? ($_GET['card_id'] ?? ($_GET['id'] ?? ($_POST['card_id'] ?? ($_POST['id'] ?? null))));
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_POST['ajax']);

        if ($cartId) {
            $uEsc = mysqli_real_escape_string($con, (string)$userId);
            $cEsc = mysqli_real_escape_string($con, (string)$cartId);
            
            // Delete from card table matching by row id OR product p_id
            mysqli_query($con, "DELETE FROM card WHERE (id='$cEsc' OR p_id='$cEsc') AND userid='$uEsc'");
            $_SESSION['shopping_remove'] = 1;

            if ($isAjax) {
                $this->json(['status' => 1, 'message' => 'Product removed from cart successfully']);
                return;
            }
        }

        $this->redirect(BASE_URL . 'cart.php');
    }
}
