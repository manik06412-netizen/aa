<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'dbconnect.php';

$user_id = $_SESSION['uid'] ?? '';
if (empty($user_id)) {
    header("Location: index.php");
    exit;
}

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

// If arrays are passed via POST
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
    // Fallback: Query from chekout table directly
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

// Insert initial order tracking status
mysqli_query($con, "INSERT INTO order_sts (order_id, message, sts_date, sts_time, status) VALUES ('$order_id', 'Order Placed Successfully', '$order_date', '$sts_time', 1)");

// Mark chekout items as processed
if (!empty($ref_id)) {
    mysqli_query($con, "UPDATE chekout SET status='1' WHERE ref_id='$ref_id'");
}
// Clear active cart items for this user
mysqli_query($con, "UPDATE card SET status='1' WHERE userid='$user_id'");

$_SESSION['order_success_toast'] = "Payment Successful! Your order #$order_id has been placed successfully.";
$_SESSION['last_order_id'] = $order_id;
unset($_SESSION['refidd']);
unset($_SESSION['raw_refid']);

header("Location: myorders.php?order_success=1&oid=" . urlencode($order_id));
exit;
