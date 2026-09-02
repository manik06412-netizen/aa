<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Core\Database;

class UserController extends Controller {
    public function profile() {
        $userId = UserModel::getOrCreateSessionUser();
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserById($userId);

        if (!$user) {
            $user = [
                'user_id' => $userId,
                'fname' => $_SESSION['uname'] ?? 'Guest',
                'lname' => '',
                'email' => $_SESSION['uemail'] ?? '',
                'mobile' => '',
                'status' => '1'
            ];
        }

        $data = [
            'title' => 'My Profile - Karuda Computers',
            'user' => $user
        ];
        $this->view('user/profile', $data);
    }

    public function myOrders() {
        $userId = UserModel::getOrCreateSessionUser();
        $db = Database::getInstance();
        $con = $db->getConnection();
        $orders = [];
        $uEsc = mysqli_real_escape_string($con, (string)$userId);
        
        $sql = "SELECT f.*, d.dish_name, d.img as dish_img, d.category, d.cateid,
                       p.pp, p.oprice, p.discount
                FROM final f
                LEFT JOIN dishes d ON (f.product_id = d.rs_id OR f.product_id = d.d_id)
                LEFT JOIN price p ON (f.price_id = p.id OR (p.pcode = d.rs_id AND p.pp = f.sel_price))
                WHERE f.user_id = '$uEsc'
                GROUP BY f.id
                ORDER BY f.id DESC";

        $res = mysqli_query($con, $sql);
        if (!$res || mysqli_num_rows($res) === 0) {
            // Also check chekout table
            $res = mysqli_query($con, "SELECT c.*, c.pr_id as product_id, c.p_name as dish_name, c.product_img as dish_img, 
                                              c.ct_py as sel_price, c.old_prc as sel_old, c.total as final_amt, c.ref_id as order_id, 
                                              c.date as order_date 
                                       FROM chekout c 
                                       WHERE c.userid='$uEsc' 
                                       ORDER BY c.id DESC");
        }

        if ($res && mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $imgKey = !empty($row['dish_img']) ? $row['dish_img'] : (!empty($row['product_img']) ? $row['product_img'] : 'img/products/hp_laptop.jpg');
                $row['resolved_img'] = \App\Models\ProductModel::resolveImage($imgKey);
                $orders[] = $row;
            }
        }

        $data = [
            'title' => 'My Orders - Karuda Computers',
            'orders' => $orders
        ];
        $this->view('user/myorders', $data);
    }

    public function trackOrder() {
        UserModel::getOrCreateSessionUser();
        $db = Database::getInstance();
        $con = $db->getConnection();

        $orderId = trim($_GET['oid'] ?? ($_GET['order_id_input'] ?? ($_GET['order_id'] ?? ($_POST['order_id'] ?? ''))));
        $order = null;
        $address = null;

        if (!empty($orderId)) {
            $oidEsc = mysqli_real_escape_string($con, $orderId);

            // 1. Search in final table
            $sql = "SELECT f.*, d.dish_name, d.img as dish_img, d.category,
                           p.pp, p.oprice, p.discount
                    FROM final f
                    LEFT JOIN dishes d ON (f.product_id = d.rs_id OR f.product_id = d.d_id)
                    LEFT JOIN price p ON (f.price_id = p.id OR (p.pcode = d.rs_id AND p.pp = f.sel_price))
                    WHERE f.order_id = '$oidEsc' OR f.refid = '$oidEsc'
                    LIMIT 1";
            $res = mysqli_query($con, $sql);
            if ($res && mysqli_num_rows($res) > 0) {
                $order = mysqli_fetch_assoc($res);
            } else {
                // 2. Fallback to chekout table
                $sql2 = "SELECT c.*, c.pr_id as product_id, c.p_name as dish_name, c.product_img as dish_img, 
                                c.ct_py as sel_price, c.old_prc as sel_old, c.total as final_amt, c.ref_id as order_id, 
                                c.date as order_date 
                         FROM chekout c 
                         WHERE c.ref_id = '$oidEsc' OR c.id = '$oidEsc' OR c.encrypt_rid = '$oidEsc'
                         LIMIT 1";
                $res2 = mysqli_query($con, $sql2);
                if ($res2 && mysqli_num_rows($res2) > 0) {
                    $order = mysqli_fetch_assoc($res2);
                }
            }

            if ($order) {
                $imgKey = !empty($order['dish_img']) ? $order['dish_img'] : (!empty($order['product_img']) ? $order['product_img'] : 'img/products/hp_laptop.jpg');
                $order['resolved_img'] = \App\Models\ProductModel::resolveImage($imgKey);

                // Fetch address
                $addrId = (int)($order['address_id'] ?? 0);
                if ($addrId > 0) {
                    $aq = mysqli_query($con, "SELECT * FROM deliver_address WHERE address_id='$addrId' LIMIT 1");
                    if ($aq && ($arow = mysqli_fetch_assoc($aq))) {
                        $address = $arow;
                    }
                }
            }
        }

        $data = [
            'title' => 'Live Order Tracking - Karuda Computers',
            'order' => $order,
            'orderId' => $orderId,
            'address' => $address
        ];
        $this->view('user/track_order', $data);
    }

    public function removeAccount() {
        UserModel::getOrCreateSessionUser();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance();
            $con = $db->getConnection();
            $reason = mysqli_real_escape_string($con, $_POST['reason'] ?? '');
            $pwd = mysqli_real_escape_string($con, $_POST['pwd'] ?? '');
            $mobile = mysqli_real_escape_string($con, $_POST['mobile'] ?? '');
            $date = date('d/m/Y');

            @mysqli_query($con, "INSERT INTO remove_account_requests (reason, pwd, mobile, date) VALUES ('$reason', '$pwd', '$mobile', '$date')");
            echo "<script>alert('Account removal request submitted successfully.');window.location.href='" . BASE_URL . "index.php';</script>";
            exit;
        }
        $data = ['title' => 'Remove Account - Karuda Computers'];
        $this->view('user/remove_account', $data);
    }

    /**
     * Submit Order Feedback
     */
    public function submitFeedback() {
        $db = Database::getInstance();
        $con = $db->getConnection();

        $orderId = $_POST['orders_id'] ?? ($_POST['order_id'] ?? '');
        $feedback = $_POST['feedback'] ?? '';
        $date = date('d/m/Y');

        if (!empty($feedback)) {
            $orderEsc = mysqli_real_escape_string($con, trim($orderId));
            $feedbackEsc = mysqli_real_escape_string($con, trim($feedback));
            $dateEsc = mysqli_real_escape_string($con, $date);

            $insert = mysqli_query($con, "INSERT INTO feedback (order_id, feedback, date_feed) VALUES ('$orderEsc', '$feedbackEsc', '$dateEsc')");
            if ($insert) {
                $this->json(['status' => 'success', 'message' => 'Thank you! Your Feedback has been submitted.']);
                return;
            }
        }

        $this->json(['status' => 'error', 'message' => 'Could not save feedback. Please try again.']);
    }

    /**
     * Submit Product Review
     */
    public function submitReview() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = UserModel::getOrCreateSessionUser();
        $db = Database::getInstance();
        $con = $db->getConnection();

        $orderId = $_POST['order_ids'] ?? ($_POST['order_id'] ?? '');
        $productId = $_POST['product_id'] ?? '';
        $review = $_POST['review'] ?? '';
        $rating = (int)($_POST['urating'] ?? 5);
        if ($rating <= 0) $rating = 5;

        $uName = $_SESSION['uname'] ?? 'Customer';
        $uEmail = $_SESSION['uemail'] ?? 'customer@example.com';
        $date = date('d/m/Y');

        if (!empty($review)) {
            $pEsc = (int)$productId;
            $rEsc = mysqli_real_escape_string($con, trim($review));
            $nameEsc = mysqli_real_escape_string($con, $uName);
            $emailEsc = mysqli_real_escape_string($con, $uEmail);
            $dateEsc = mysqli_real_escape_string($con, $date);

            // Insert into cust_reviews
            mysqli_query($con, "INSERT INTO cust_reviews (cid, cname, uemail, uname, ureview, uratings, createdat) 
                                VALUES ('$pEsc', '$nameEsc', '$emailEsc', '$nameEsc', '$rEsc', $rating, '$dateEsc')");

            $this->json(['status' => 'success', 'message' => 'Thank you! Your Review has been submitted.']);
            return;
        }

        $this->json(['status' => 'error', 'message' => 'Please provide a valid review.']);
    }

    /**
     * Cancel Order Handler
     */
    public function cancelOrder() {
        $db = Database::getInstance();
        $con = $db->getConnection();

        $orderId = $_POST['order_id'] ?? ($_POST['orders_id'] ?? '');
        $reason = $_POST['feedback'] ?? ($_POST['reason'] ?? 'Order cancelled by user');
        $date = date('d/m/Y');

        if (!empty($orderId)) {
            $orderEsc = mysqli_real_escape_string($con, trim($orderId));
            $reasonEsc = mysqli_real_escape_string($con, trim($reason));
            $dateEsc = mysqli_real_escape_string($con, $date);

            // Log cancellation
            @mysqli_query($con, "INSERT INTO cancelled_order (order_id, feedback, date) VALUES ('$orderEsc', '$reasonEsc', '$dateEsc')");

            // Update order status in final / final1 / chekout
            mysqli_query($con, "UPDATE final SET status='4' WHERE order_id='$orderEsc' OR refid='$orderEsc'");
            mysqli_query($con, "UPDATE final1 SET status='4' WHERE order_id='$orderEsc' OR refid='$orderEsc'");
            mysqli_query($con, "UPDATE chekout SET status='4' WHERE ref_id='$orderEsc' OR encrypt_rid='$orderEsc'");

            $this->json(['status' => 'success', 'message' => 'Order cancelled successfully.']);
            return;
        }

        $this->json(['status' => 'error', 'message' => 'Invalid order ID.']);
    }

    /**
     * Get Status / Review Details for Modal Popup
     */
    public function getStatusDetails() {
        $db = Database::getInstance();
        $con = $db->getConnection();

        $orderId = $_POST['order_id'] ?? ($_POST['order_ids'] ?? ($_POST['orders_id'] ?? ''));
        $productId = $_POST['product_id'] ?? '';

        $pName = 'Hardware Product';
        $pImg = 'img/products/hp_laptop.jpg';

        if (!empty($productId)) {
            $pEsc = mysqli_real_escape_string($con, (string)$productId);
            $q = mysqli_query($con, "SELECT dish_name, img FROM dishes WHERE rs_id='$pEsc' OR d_id='$pEsc' LIMIT 1");
            if ($q && ($row = mysqli_fetch_assoc($q))) {
                $pName = $row['dish_name'];
                $pImg = \App\Models\ProductModel::resolveImage($row['img']);
            }
        }

        echo '<div class="d-flex align-items-center gap-3 p-3" style="background:#F8FAFC; border-radius:10px; margin-bottom:15px;">
                <img src="' . htmlspecialchars($pImg) . '" alt="Product" style="width:60px; height:60px; object-fit:contain; border-radius:8px; background:#fff; padding:4px; border:1px solid #E2E8F0;">
                <div>
                    <h6 style="font-weight:700; color:#0F172A; margin:0 0 4px 0;">' . htmlspecialchars($pName) . '</h6>
                    <span style="font-size:12px; color:#64748B;">Order #' . htmlspecialchars($orderId) . '</span>
                </div>
              </div>';
        exit;
    }
}
