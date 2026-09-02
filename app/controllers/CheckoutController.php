<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CartModel;
use App\Models\UserModel;
use App\Core\Database;

class CheckoutController extends Controller {
    public function index() {
        if (!UserModel::isLoggedIn()) {
            $redirectUrl = 'checkout.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
            $_SESSION['redirect_after_login'] = $redirectUrl;
            $this->redirect(BASE_URL . 'login.php?redirect=' . urlencode($redirectUrl));
            return;
        }

        $userId = UserModel::getOrCreateSessionUser();
        $cartModel = $this->model('CartModel');
        $items = $cartModel->getCartItems($userId);

        $data = [
            'title' => 'Checkout - Karuda Computers',
            'items' => $items
        ];
        $this->view('checkout/checkout', $data);
    }

    public function saveBuyNow() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // 🔒 Purchase Requires Login Guard
        if (!UserModel::isLoggedIn()) {
            $prdId = $_POST['productId'] ?? '';
            $this->json([
                'status' => 0,
                'require_login' => true,
                'redirect' => 'login.php?redirect=' . urlencode('details.php?id=' . $prdId)
            ]);
            return;
        }

        $userId = UserModel::getOrCreateSessionUser();
        $db = Database::getInstance();
        $con = $db->getConnection();

        $uid = $userId;
        $prd_id1 = $_POST['productId'] ?? '';
        $quantity1 = (int)($_POST['quantity'] ?? 1);
        $price_id1 = $_POST['price'] ?? '';
        $date = date("d/m/Y");
        $rfid = rand(10, 100) . time();
        $status = 0;

        $corrent_price1 = 0;
        $old_price_modify1 = 0;
        $get_per1 = 0;
        $dis = 0;

        $ss = mysqli_query($con, "SELECT * FROM price WHERE id='$price_id1'");
        if ($ss && ($prow = mysqli_fetch_array($ss))) { 
            $corrent_price1 = (float)$prow['pp'];
            $old_price_modify1 = (float)$prow['oprice'];
            $get_per1 = (float)$prow['gst'];
            $dis = (float)$prow['discount'];
        }

        $prd_name1 = '';
        $imgs1 = '';
        $ship = '';
        $select2 = mysqli_query($con, "SELECT * FROM dishes WHERE rs_id='$prd_id1'");
        if ($select2 && ($prow2 = mysqli_fetch_array($select2))) {
            $prd_name1 = $prow2['dish_name'];
            $imgs1 = $prow2['img'];
            $ship = $prow2['deliv_opt'];
        }
        $ship_chg = ($ship == 'Delivery Charge') ? 1 : 0;

        $subtot1 = $corrent_price1 * $quantity1;
        $discount_amount = ($subtot1 * $dis) / 100;
        $subtot2 = $subtot1 - $discount_amount;
        $gst_amount = ($subtot2 * $get_per1) / 100;
        $subtot = $subtot2 + $gst_amount;

        $key = '23232322114432'; 
        $method = 'AES-256-CBC';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        $encrypted = openssl_encrypt($rfid, $method, $key, 0, $iv);
        $encrypted_data = base64_encode($encrypted . '::' . $iv);

        $insert = mysqli_query($con, "INSERT INTO chekout 
            (ref_id, userid, pr_id, price_id, p_name, product_img, qty, gstper, ct_py, old_prc, total, date, status, gst_pri, dis_pri, items, subtotal, ship_chrg, encrypt_rid) 
            VALUES 
            ('$rfid', '$uid', '$prd_id1', '$price_id1', '$prd_name1', '$imgs1', '$quantity1', '$get_per1', '$corrent_price1', '$old_price_modify1', '$subtot', '$date', '$status', '$gst_amount', '$discount_amount', $quantity1, '$subtot1', $ship_chg, '$encrypted_data')");

        if ($insert) {
            $_SESSION['refidd'] = $rfid;
            $this->json(['status' => 1, 'ref_id' => $encrypted_data, 'redirect' => "checkout.php?refid=" . urlencode($encrypted_data)]);
        } else {
            $this->json(['status' => 2, 'error' => mysqli_error($con)]);
        }
    }

    /**
     * Handles Proceed to Checkout from Shopping Cart (shopping_insert.php)
     */
    public function saveCartCheckout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // 🔒 Purchase Requires Login Guard
        if (!UserModel::isLoggedIn()) {
            $this->json([
                'status' => 0,
                'require_login' => true,
                'redirect' => 'login.php?redirect=' . urlencode('cart.php')
            ]);
            return;
        }

        $userId = UserModel::getOrCreateSessionUser();
        $db = Database::getInstance();
        $con = $db->getConnection();

        $uid = mysqli_real_escape_string($con, (string)$userId);
        $date = date("d/m/Y");
        $rfid = rand(10, 100) . time();
        $status = 0;

        $key = '23232322114432'; 
        $method = 'AES-256-CBC';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        $encrypted = openssl_encrypt($rfid, $method, $key, 0, $iv);
        $encrypted_data = base64_encode($encrypted . '::' . $iv);

        // Fetch all active items in user cart
        $cartQ = mysqli_query($con, "SELECT c.*, d.dish_name, d.img as d_img, d.deliv_opt, p.pp, p.oprice, p.discount, p.gst 
                                      FROM card c
                                      LEFT JOIN dishes d ON (c.p_id = d.rs_id OR c.p_id = d.d_id)
                                      LEFT JOIN price p ON c.pric_id = p.id
                                      WHERE c.userid='$uid' AND c.status='0'");

        $hasItems = false;

        if ($cartQ && mysqli_num_rows($cartQ) > 0) {
            while ($row = mysqli_fetch_assoc($cartQ)) {
                $hasItems = true;
                $prd_id = $row['p_id'];
                $price_id = $row['pric_id'];
                $prd_name = !empty($row['dish_name']) ? $row['dish_name'] : 'Tech Product';
                $product_img = !empty($row['d_img']) ? $row['d_img'] : 'img/products/hp_laptop.jpg';
                $qty = (int)($row['qty'] > 0 ? $row['qty'] : 1);
                $unit_price = (float)$row['pp'];
                $old_price = (float)$row['oprice'];
                $discount_per = (float)$row['discount'];
                $gst_per = (float)$row['gst'];

                $subtot1 = $unit_price * $qty;
                $discount_amount = ($subtot1 * $discount_per) / 100;
                $subtot2 = $subtot1 - $discount_amount;
                $gst_amount = ($subtot2 * $gst_per) / 100;
                $subtot = $subtot2 + $gst_amount;

                $ship = $row['deliv_opt'] ?? '';
                $ship_chg = ($ship == 'Delivery Charge') ? 1 : 0;

                $prd_name_esc = mysqli_real_escape_string($con, $prd_name);
                $product_img_esc = mysqli_real_escape_string($con, $product_img);

                mysqli_query($con, "INSERT INTO chekout 
                    (ref_id, userid, pr_id, price_id, p_name, product_img, qty, gstper, ct_py, old_prc, total, date, status, gst_pri, dis_pri, items, subtotal, ship_chrg, encrypt_rid) 
                    VALUES 
                    ('$rfid', '$uid', '$prd_id', '$price_id', '$prd_name_esc', '$product_img_esc', '$qty', '$gst_per', '$unit_price', '$old_price', '$subtot', '$date', '$status', '$gst_amount', '$discount_amount', $qty, '$subtot1', $ship_chg, '$encrypted_data')");
            }
        }

        // Also check if submitted via POST arrays from cart form
        if (!$hasItems && isset($_POST['prd_id']) && is_array($_POST['prd_id'])) {
            for ($i = 0; $i < count($_POST['prd_id']); $i++) {
                $hasItems = true;
                $prd_id = $_POST['prd_id'][$i];
                $price_id = $_POST['price_id'][$i] ?? '';
                $prd_name = $_POST['pro_name'][$i] ?? 'Tech Product';
                $product_img = $_POST['imgs'][$i] ?? '';
                $qty = (int)($_POST['pro_qty'][$i] ?? 1);
                $unit_price = (float)($_POST['corrent_price'][$i] ?? 0);
                $old_price = (float)($_POST['old_Price'][$i] ?? 0);
                $discount_per = (float)($_POST['discount_per'][$i] ?? 0);
                $gst_per = (float)($_POST['get_per'][$i] ?? 0);

                $subtot1 = $unit_price * $qty;
                $discount_amount = ($subtot1 * $discount_per) / 100;
                $subtot2 = $subtot1 - $discount_amount;
                $gst_amount = ($subtot2 * $gst_per) / 100;
                $subtot = $subtot2 + $gst_amount;

                $ship_chg = (int)($_POST['delivery_option'] ?? 0);

                $prd_name_esc = mysqli_real_escape_string($con, $prd_name);
                $product_img_esc = mysqli_real_escape_string($con, $product_img);

                mysqli_query($con, "INSERT INTO chekout 
                    (ref_id, userid, pr_id, price_id, p_name, product_img, qty, gstper, ct_py, old_prc, total, date, status, gst_pri, dis_pri, items, subtotal, ship_chrg, encrypt_rid) 
                    VALUES 
                    ('$rfid', '$uid', '$prd_id', '$price_id', '$prd_name_esc', '$product_img_esc', '$qty', '$gst_per', '$unit_price', '$old_price', '$subtot', '$date', '$status', '$gst_amount', '$discount_amount', $qty, '$subtot1', $ship_chg, '$encrypted_data')");
            }
        }

        if ($hasItems) {
            $_SESSION['refidd'] = $rfid;
            $this->json([
                'status' => 1,
                'ref_id' => $encrypted_data,
                'redirect' => "checkout.php?refid=" . urlencode($encrypted_data)
            ]);
        } else {
            $this->json(['status' => 2, 'error' => 'Cart is empty.']);
        }
    }

    public function processFinalCheckout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // 🔒 Purchase Requires Login Guard
        if (!UserModel::isLoggedIn()) {
            $this->json([
                'status' => 'error',
                'message' => 'Please login to complete your order purchase.',
                'redirect' => 'login.php'
            ]);
            return;
        }

        $db = Database::getInstance();
        $con = $db->getConnection();

        $user_id = $_SESSION['uid'] ?? UserModel::getOrCreateSessionUser();
        $ref_id = mysqli_real_escape_string($con, $_POST['ref_id'] ?? ($_SESSION['refidd'] ?? ''));
        $address_id = (int)($_POST['address_id'] ?? 0);
        if ($address_id <= 0) {
            $deliv_res = mysqli_query($con, "SELECT address_id FROM deliver_address WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1");
            if ($deliv_res && ($drow = mysqli_fetch_assoc($deliv_res))) {
                $address_id = (int)$drow['address_id'];
            }
        }

        $total_amt = (float)($_POST['total_amt'] ?? 0);
        $shipping_amt = (float)($_POST['shipping_amt'] ?? 0);
        $promo_amt = mysqli_real_escape_string($con, $_POST['promo_amt'] ?? '');
        $payment_id = mysqli_real_escape_string($con, $_POST['razorpay_payment_id'] ?? ($_POST['order_id'] ?? ('PAY_' . time())));

        $product_ids = $_POST['product_id'] ?? [];
        $price_ids = $_POST['price_id'] ?? [];
        $qtys = $_POST['product_qty'] ?? [];
        $current_prices = $_POST['product_cr_price'] ?? [];
        $old_prices = $_POST['product_old_price'] ?? [];
        $gsts = $_POST['product_gst'] ?? [];
        $dis_prices = $_POST['dis_price'] ?? [];

        $order_id = time() . rand(100, 999) . date('Ymd');
        $order_date = date('d-m-Y');
        $sts_time = date('H:i');
        $trans_id = rand(1000, 9999) . time();

        if (!empty($product_ids) && is_array($product_ids)) {
            for ($i = 0; $i < count($product_ids); $i++) {
                $p_id = mysqli_real_escape_string($con, $product_ids[$i] ?? '');
                $prc_id = mysqli_real_escape_string($con, $price_ids[$i] ?? '');
                $qty = (int)($qtys[$i] ?? 1);
                $cr_price = (float)($current_prices[$i] ?? 0);
                $old_prc = (float)($old_prices[$i] ?? 0);
                $gst_per = (float)($gsts[$i] ?? 0);
                $dis_amt = (float)($dis_prices[$i] ?? 0);
                $item_subtotal = $cr_price * $qty;
                
                mysqli_query($con, "INSERT INTO final 
                    (refid, user_id, order_id, address_id, product_id, price_id, qty, sel_price, sel_old, gst_per, discount_amt, shipping_amt, promo_amt, total_amt, final_amt, status, order_date, trans_id, payment_id)
                    VALUES
                    ('$ref_id', '$user_id', '$order_id', '$address_id', '$p_id', '$prc_id', '$qty', '$cr_price', '$old_prc', '$gst_per', '$dis_amt', '$shipping_amt', '$promo_amt', '$item_subtotal', '$total_amt', '0', '$order_date', '$trans_id', '$payment_id')");
            }
        } else {
            $chk_q = mysqli_query($con, "SELECT * FROM chekout WHERE ref_id='$ref_id' OR userid='$user_id' ORDER BY id DESC");
            if ($chk_q && mysqli_num_rows($chk_q) > 0) {
                while ($crow = mysqli_fetch_assoc($chk_q)) {
                    $p_id = $crow['pr_id'];
                    $prc_id = $crow['price_id'];
                    $qty = $crow['qty'];
                    $cr_price = $crow['ct_py'];
                    $old_prc = $crow['old_prc'];
                    $gst_per = $crow['gst_pri'];
                    $dis_amt = $crow['dis_pri'];
                    $item_subtotal = $crow['subtotal'];
                    $ref_id = $crow['ref_id'];

                    mysqli_query($con, "INSERT INTO final 
                        (refid, user_id, order_id, address_id, product_id, price_id, qty, sel_price, sel_old, gst_per, discount_amt, shipping_amt, promo_amt, total_amt, final_amt, status, order_date, trans_id, payment_id)
                        VALUES
                        ('$ref_id', '$user_id', '$order_id', '$address_id', '$p_id', '$prc_id', '$qty', '$cr_price', '$old_prc', '$gst_per', '$dis_amt', '$shipping_amt', '$promo_amt', '$item_subtotal', '$total_amt', '0', '$order_date', '$trans_id', '$payment_id')");
                }
            }
        }

        mysqli_query($con, "INSERT INTO order_sts (order_id, message, sts_date, sts_time, status) VALUES ('$order_id', 'Order Placed Successfully', '$order_date', '$sts_time', 1)");

        if (!empty($ref_id)) {
            mysqli_query($con, "UPDATE chekout SET status='1' WHERE ref_id='$ref_id'");
        }
        mysqli_query($con, "UPDATE card SET status='1' WHERE userid='$user_id'");

        $_SESSION['order_success_toast'] = "Payment Successful! Your order #$order_id has been placed successfully.";
        $_SESSION['last_order_id'] = $order_id;
        unset($_SESSION['refidd']);
        unset($_SESSION['raw_refid']);

        header("Location: myorders.php?order_success=1&oid=" . urlencode($order_id));
        exit;
    }
}
