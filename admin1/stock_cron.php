<?php
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
?>
<?php
session_start();
error_reporting(0);
include('./inc/config.php');
function Check_STATUS($con, $lastDate, $already)
{
    $Add_con = "";

    if (!empty($already)) {
        $Add_con = $already;
    }
    $checking = 0;
    $check_stock = mysqli_query($con, "SELECT close_stk FROM stock_invent WHERE date_inv = '$lastDate' $Add_con");
    if (mysqli_num_rows($check_stock)) {
        while ($close_stock = mysqli_fetch_array($check_stock)) {
            $close_stock_value = (float)$close_stock['close_stk'];
            $checking += $close_stock_value;
        }
    }
    return $checking;
}

// 


function STOCK_SET($con)
{
    $lastDate = date('d-m-Y', strtotime('last day of previous month'));

    $select = mysqli_query($con, "SELECT * FROM dishes INNER JOIN price ON dishes.rs_id = price.pcode ORDER BY d_id DESC");

    $response = false;

    while ($row = mysqli_fetch_array($select)) {


        $product_id = $row['rs_id'];
        $price_id = $row['id'];
        $dish_name = $row['dish_name'];
        // $find = mysqli_query($con, "SELECT * FROM prd_stock WHERE pd_code='$product_id' AND price_id= $price_id  ORDER BY id DESC LIMIT 1 ");
       
        $find = mysqli_query($con, "SELECT * FROM stock_invent WHERE prd_id='$product_id' AND price_id= $price_id  ORDER BY id DESC LIMIT 1 ");
        if ($getting_that = mysqli_fetch_array($find)) {
            $open_stock = $getting_that['total'];

            $stock_check = "AND prd_id ='$product_id' AND price_id = '$price_id'";
            $finding_status_of_1 = Check_STATUS($con, $lastDate, $stock_check);

            if ($finding_status_of_1 === 0) {
                $dateString_1 = date('d-m-Y');

                    $date_1 = new DateTime($dateString_1);

                    $date_1->modify('last day of last month');
                    $formattedDate_s = $date_1->format('F-Y');
                $close_stock = mysqli_query($con, "INSERT INTO stock_invent VALUES(null, '$product_id', '$price_id','$dish_name','','','','$open_stock','$open_stock','$lastDate','$formattedDate_s')");
            }

            if ($close_stock) {
                $dateString = date('d-m-Y');
                    $date = new DateTime($dateString);
                    $formattedDate = $date->format('F-Y');
                $store = mysqli_query($con, "INSERT INTO stock_invent VALUES(null, '$product_id', '$price_id','$dish_name','$open_stock','','','$open_stock','','$lastDate','$formattedDate')");
                if ($store) {
                    $response = true;
                }
            }
        }
    }
    return $response;

}

// call that

$lastDate = date('d-m-Y', strtotime('last day of previous month'));

    $finding_status_of = Check_STATUS($con, $lastDate, '');

    if ($finding_status_of === 0) {
        $stock_set = STOCK_SET($con);
        if ($stock_set === true) {
            echo json_encode(['status'=>1]);
        } else {
            echo 'error';
        }
    }

?>