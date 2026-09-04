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

function numberOfProducts($con, $ordersList, $priceId,$filter_date) {
    $priceCheck = !empty($priceId) ? "AND price_id = '$priceId'" : '';

    $select = mysqli_query($con, "SELECT SUM(qty) AS qt_list FROM final WHERE product_id = '$ordersList' $priceCheck $filter_date");
    $row = mysqli_fetch_assoc($select);
    return $row['qt_list'] ?? 0;
}

$filterAdd1 = '';
$filter_date ='';

if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $startDate = DateTime::createFromFormat('Y-m-d', $_POST['startDate']);
    $endDate = DateTime::createFromFormat('Y-m-d', $_POST['endDate']);

    $formattedStartDate = $startDate->format('d-m-Y');
    $formattedEndDate = $endDate->format('d-m-Y');

    $filterAdd1 = "WHERE order_date BETWEEN '$formattedStartDate' AND '$formattedEndDate'";
    $filter_date = "AND order_date BETWEEN '$formattedStartDate' AND '$formattedEndDate'";
}

$query = "SELECT * FROM final $filterAdd1 GROUP BY product_id";
$sql = mysqli_query($con, $query);
$response = '';

while ($orders = mysqli_fetch_assoc($sql)) { 
    $ordersList = $orders['product_id'];
    $numberOf = numberOfProducts($con, $ordersList,'',$filter_date);
    
    $queryOf = mysqli_query($con, "SELECT * FROM dishes WHERE rs_id = '$ordersList'");
    $dishIs = mysqli_fetch_assoc($queryOf);

    $response .= "<tr>
        <td style='text-align: left;'>
            <img src='./{$dishIs['img']}' width='80' height='70' alt=''>
            <b style='display:block;'>{$dishIs['dish_name']}</b>
        </td>
        <td style='text-align: left;'>$numberOf</td>
        <td>
            <table class='table text-center' style='background-color: #d3d3d3 !important;'>
                <tr>
                    <th>Measurement</th>
                    <th>Current Status</th>
                    <th>No. of Items Sold</th>
                    <th>View</th>
                </tr>";

    $measurementS = mysqli_query($con, "SELECT * FROM price WHERE pcode='$ordersList'");
    
    while($priceOf = mysqli_fetch_assoc($measurementS)) {
        $currentStatusOf = mysqli_query($con, "SELECT stk_status FROM prd_stock WHERE pd_code='$ordersList' AND price_id = {$priceOf['id']} ORDER BY id DESC LIMIT 1");
        $currentSta = mysqli_fetch_assoc($currentStatusOf);
        
        $response .= "<tr>
            <td>{$priceOf['qn']}-{$priceOf['wg']}</td>
            <td>{$currentSta['stk_status']}</td>
            <td>" . numberOfProducts($con, $ordersList, $priceOf['id'],$filter_date) . "</td>
            <td><a href='#' onclick=\"OPEN_MODEL_FORM('$ordersList', '{$priceOf['id']}', '{$dishIs['dish_name']}', '{$priceOf['qn']}-{$priceOf['wg']}','{$dishIs['img']}')\" data-toggle='modal' data-target='#confirm-delete' class='btn btn-info btn-xs'>View</a></td>
        </tr>";
    }

    $response .= "</table>
        </td>
    </tr>";
}

echo json_encode(['status' => 1, 'response' => $response]);
?>
