<?php
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';

if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}


// Initialize error and success messages
$error = '';
$success = '';

if (isset($_POST['submit'])) {
    // Validate input fields
    if (empty($_POST['d_name']) || empty($_POST['price']) || empty($_POST['oprice']) || empty($_POST['qn']) || empty($_POST['weight']) || empty($_POST['dis'])) {
        $_SESSION['error_message'] = 'All fields must be filled!';
        header("Location: add_price.php?prd_id=" . $_POST['pcode']);
        exit();
    } else {
        // Prepare and execute query to check if the price record already exists
        $stmt = $con->prepare("SELECT id FROM price WHERE pcode = ? AND oprice = ? AND pp = ? AND qn = ? AND wg = ?");
        $stmt->bind_param('sssss', $_POST['pcode'], $_POST['oprice'], $_POST['price'], $_POST['qn'], $_POST['weight']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error_message'] = 'Price already registered for this quantity and product!';
            $stmt->close();
            header("Location: add_price.php?prd_id=" . $_POST['pcode']);
            exit();
        } else {
            // Insert into price table
            $stmt = $con->prepare("INSERT INTO price (pcode, pname, oprice, pp, qn, wg, gst, total_stock, discount, s_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssssss', $_POST['pcode'], $_POST['d_name'], $_POST['oprice'], $_POST['price'], $_POST['qn'], $_POST['weight'], $_POST['gst'], $_POST['stock'], $_POST['dis'], $_POST['s_status']);
            $stmt->execute();

            // Get the last inserted ID
            $price_id = $con->insert_id;

            // Insert into prd_stock table
            $d = date("d-m-Y"); // Date format should be consistent
            $note = $_POST['stock'] . " product added";
            $prev = 0;

            $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param('siidssss', $_POST['pcode'], $price_id, $prev, $_POST['stock'], $_POST['stock'], $d, $note, $_POST['s_status']);
            $stmt2->execute();


            $stmt2 = $con->prepare("INSERT INTO prd_stock (pd_code, price_id, prev_stock, new_stock, total, date, note, stk_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param('siidssss', $_POST['pcode'], $price_id, $prev, $_POST['stock'], $_POST['stock'], $d, $note, $_POST['s_status']);
            $stmt2->execute();

            $dateString = date('d-m-Y');
            $date = new DateTime($dateString);
            $formattedDate = $date->format('F-Y');
            

            $stmt3 = $con->prepare("INSERT INTO stock_invent(prd_id, price_id, prd_name, open_stk, tot_stk, date_inv,mnt_inv) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt3->bind_param('sssssss', $_POST['pcode'], $price_id,$_POST['d_name'], $_POST['stock'], $_POST['stock'], $d, $formattedDate);
            $stmt3->execute();

            $_SESSION['success_message'] = 'New price added successfully.';
            $stmt->close();
            $stmt2->close();
            $stmt3->close();
            header("Location: add_price.php?prd_id=" . $_POST['pcode']);
            exit();
        }
    }
}

?>
