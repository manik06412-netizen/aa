<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CartModel;
use App\Models\UserModel;
use App\Core\Database;

class CartController extends Controller {
    public function index() {
        if (isset($_GET['action']) || isset($_POST['action'])) {
            $act = $_GET['action'] ?? ($_POST['action'] ?? '');
            if ($act === 'add') {
                $this->add();
                return;
            } elseif ($act === 'remove') {
                $this->remove();
                return;
            }
        }

        $userId = UserModel::getOrCreateSessionUser();
        $cartModel = $this->model('CartModel');
        $items = $cartModel->getCartItems($userId);

        $data = [
            'title'   => 'Shopping Cart - Karuda Computers',
            'items'   => $items,
            'user_id' => $userId,  // Pass explicitly so view has correct user_id
        ];

        $this->view('cart/cart', $data);
    }

    public function add() {
        $userId = UserModel::getOrCreateSessionUser();
        $cartModel = $this->model('CartModel');

        $productId = isset($_POST['prdid']) ? $_POST['prdid'] : (isset($_GET['prdid']) ? $_GET['prdid'] : (isset($_POST['productId']) ? $_POST['productId'] : (isset($_GET['id']) ? $_GET['id'] : null)));
        $priceId = isset($_POST['pid']) ? $_POST['pid'] : (isset($_GET['pid']) ? $_GET['pid'] : (isset($_POST['price']) ? $_POST['price'] : null));
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
        if ($qty < 1) $qty = 1;

        $db = Database::getInstance();
        $con = $db->getConnection();
        $uEsc = mysqli_real_escape_string($con, (string)$userId);
        $pEsc = mysqli_real_escape_string($con, (string)$productId);
        $prEsc = mysqli_real_escape_string($con, (string)$priceId);
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_POST['prdid']) || isset($_POST['productId']) || isset($_GET['prdid']) || isset($_GET['action']);

        if ($productId) {
            // Find price ID / price details if missing
            if (!$priceId || $priceId == '0') {
                $pFind = mysqli_query($con, "SELECT id, pp, gst FROM price WHERE pcode='$pEsc' LIMIT 1");
                if (!$pFind || mysqli_num_rows($pFind) == 0) {
                    $dFind = mysqli_query($con, "SELECT d_id, rs_id FROM dishes WHERE rs_id='$pEsc' OR d_id='$pEsc' LIMIT 1");
                    if ($dFind && ($dRow = mysqli_fetch_assoc($dFind))) {
                        $altCode = !empty($dRow['d_id']) ? mysqli_real_escape_string($con, $dRow['d_id']) : '';
                        $altRs = !empty($dRow['rs_id']) ? mysqli_real_escape_string($con, $dRow['rs_id']) : '';
                        $pFind = mysqli_query($con, "SELECT id, pp, gst FROM price WHERE pcode='$altCode' OR pcode='$altRs' LIMIT 1");
                    }
                }
                if ($pFind && ($pRow = mysqli_fetch_assoc($pFind))) {
                    $priceId = $pRow['id'];
                    $prEsc = mysqli_real_escape_string($con, (string)$priceId);
                }
            }

            // Check if product is ALREADY in cart (check both 'card' and 'Card')
            $tblCard = 'card';
            $check = mysqli_query($con, "SELECT id, qty FROM card WHERE userid='$uEsc' AND (p_id='$pEsc' OR (pric_id='$prEsc' AND pric_id!='0')) AND status='0'");
            if (!$check) {
                $tblCard = 'Card';
                $check = mysqli_query($con, "SELECT id, qty FROM Card WHERE userid='$uEsc' AND (p_id='$pEsc' OR (pric_id='$prEsc' AND pric_id!='0')) AND status='0'");
            }

            if ($check && mysqli_num_rows($check) > 0) {
                // Product ALREADY in cart — increment quantity!
                $existingRow = mysqli_fetch_assoc($check);
                $existingCartId  = (int)$existingRow['id'];
                $existingQty     = (int)$existingRow['qty'];
                $newQty          = $existingQty + $qty;

                // Cap at available stock
                $stockQ = mysqli_query($con, "SELECT COALESCE(SUM(total_stock),0) AS stock FROM price WHERE pcode='$pEsc' OR id='$prEsc'");
                $stockRow = $stockQ ? mysqli_fetch_assoc($stockQ) : null;
                $maxStock = $stockRow ? (int)$stockRow['stock'] : 9999;
                if ($newQty > $maxStock && $maxStock > 0) {
                    $newQty = $maxStock;
                }

                // Recalculate amount
                $sel = mysqli_query($con, "SELECT pp, gst FROM price WHERE id='$prEsc' OR pcode='$pEsc' LIMIT 1");
                $unitPrice = 0;
                if ($sel && ($ro = mysqli_fetch_array($sel))) {
                    $unitPrice = (float)$ro['pp'];
                }
                $newAmt = $unitPrice * $newQty;
                mysqli_query($con, "UPDATE $tblCard SET qty='$newQty', amt='$newAmt', tot='$newAmt' WHERE id='$existingCartId' AND userid='$uEsc'");

                $cntQ = mysqli_query($con, "SELECT COUNT(*) as cnt FROM $tblCard WHERE userid='$uEsc' AND status='0'");
                $cntRow = $cntQ ? mysqli_fetch_assoc($cntQ) : null;
                $numCart = $cntRow ? (int)$cntRow['cnt'] : 0;

                if ($isAjax) {
                    $this->json([
                        'status' => 3, 
                        'message' => "Item is already in your cart! Quantity updated to $newQty.", 
                        'number_of_cart' => $numCart, 
                        'new_qty' => $newQty
                    ]);
                    return;
                }
            } else {
                // NEW Product insertion
                $sel = mysqli_query($con, "SELECT * FROM price WHERE id='$prEsc' OR pcode='$pEsc' LIMIT 1");
                $amt = 0;
                $gst = 0;
                if ($sel && ($ro = mysqli_fetch_array($sel))) {
                    $amt = (float)$ro['pp'];
                    $gst = (float)$ro['gst'];
                }
                $date = date('d/m/Y');
                $tot = $amt * $qty;

                // Try insertion with explicit column names first (safest)
                $insertRes = mysqli_query($con, "INSERT INTO $tblCard (userid, p_id, pric_id, qty, amt, gst, tot, date, status) VALUES ('$uEsc', '$pEsc', '$prEsc', '$qty', '$amt', '$gst', '$tot', '$date', '0')");
                if (!$insertRes) {
                    // Try positional VALUES
                    $insertRes = mysqli_query($con, "INSERT INTO $tblCard VALUES(null, '$uEsc', '$pEsc', '$prEsc', '$qty', '$amt', '$gst', '$tot', '$date', '0')");
                }
                
                $cntQ = mysqli_query($con, "SELECT COUNT(*) as cnt FROM $tblCard WHERE userid='$uEsc' AND status='0'");
                $cntRow = $cntQ ? mysqli_fetch_assoc($cntQ) : null;
                $numCart = $cntRow ? (int)$cntRow['cnt'] : 0;

                if ($isAjax) {
                    if ($numCart > 0 || $insertRes) {
                        $this->json([
                            'status' => 2, 
                            'message' => 'Hooray! Item added to your cart!', 
                            'number_of_cart' => $numCart
                        ]);
                    } else {
                        $dbErr = mysqli_error($con);
                        $this->json([
                            'status' => 0, 
                            'error' => 'Database error adding to cart: ' . ($dbErr ?: 'unknown error'),
                            'number_of_cart' => 0
                        ]);
                    }
                    return;
                }
            }
        }

        $this->redirect(BASE_URL . 'cart.php');
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
