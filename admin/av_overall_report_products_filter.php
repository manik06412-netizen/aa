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

$filter_add_1 = '';

if (!empty($_POST['startDate']) && !empty($_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    
    $startDateTime = DateTime::createFromFormat('Y-m-d', $startDate);
    $endDateTime = DateTime::createFromFormat('Y-m-d', $endDate);
    
    $formattedStartDate = $startDateTime->format('d-m-Y'); 
    $formattedEndDate = $endDateTime->format('d-m-Y');

    $filter_add_1 = "AND dishes.date_of_adding BETWEEN '$formattedStartDate' AND '$formattedEndDate' ";

}

$product_name_ = '';
if (!empty($_POST['product_name'])) {
    $product_name_ = 'AND dishes.dish_name ="' . $_POST['product_name'] . '"';
}

$current_sts = '';
if (!empty($_POST['current_status'])) {
    $current_sts = trim($_POST['current_status']);
}

function MEASUREMENT_LOOP($con, $product, $stock_status) {
    $measurement = [];
    $stmt = $con->prepare("SELECT price.qn, price.wg, price.id,price.pcode,prd_stock.total  FROM price 
            INNER JOIN prd_stock ON price.pcode = prd_stock.pd_code  WHERE price.pcode = ? GROUP BY price.id ");
    $stmt->bind_param("s", $product);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $select = mysqli_query($con, "SELECT * FROM prd_stock WHERE pd_code = '{$row['pcode']}' AND price_id = {$row['id']}  ORDER BY id DESC LIMIT 1");
        $count = mysqli_fetch_array($select);
        if($count['total'] > 0){
        if($count['stk_status'] == $stock_status){
          
        $measurement[] = [
            'quantity' => $row['qn'],
            'weight' => $row['wg'],
            'stock' => $count['total'],
            'product_code' => $row['pcode'],
            'price_id' => $row['id'],
        ];
    }
}
    }

    $stmt->close();
    return $measurement;
}

function MEASUREMENT_LOOP_1($con, $product, $stock_status) {
    $measurement = [];
    $stmt = $con->prepare("SELECT price.qn, price.wg, price.id,price.pcode,prd_stock.total  FROM price 
            INNER JOIN prd_stock ON price.pcode = prd_stock.pd_code  WHERE price.pcode = ? GROUP BY price.id ");
    $stmt->bind_param("s", $product);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $select = mysqli_query($con, "SELECT * FROM prd_stock WHERE pd_code = '{$row['pcode']}' AND price_id = {$row['id']}  ORDER BY id DESC LIMIT 1");
        $count = mysqli_fetch_array($select);
        if($count['total'] <= 0){
        $measurement[] = [
            'quantity' => $row['qn'],
            'weight' => $row['wg'],
            'stock' => $count['total'],
            'product_code' => $row['pcode'],
            'price_id' => $row['id'],
        ];
    }
    }

    $stmt->close();
    return $measurement;
}


$sql = "SELECT * FROM dishes  INNER JOIN price ON dishes.rs_id = price.pcode  WHERE dishes.status = '1' ". $product_name_ . $filter_add_1 . " 
        GROUP BY price.pcode  ORDER BY d_id DESC";

$category_list = mysqli_query($con, $sql);

$response = '';

function createMeasurementRows($measurements, $image, $product_name, $current_status) {
    if (empty($measurements)) {
        return '<tr><td>----</td></tr>';
    }

    $rows = '';
    foreach ($measurements as $measurement) {
        $measurementDetails = $measurement['quantity'] . ' ' . $measurement['weight'] . ' - ' . $measurement['stock'];
        $rows .= '<tr>
                    <td><span>' . $measurementDetails . '</span></td>
                    <td>
                        <span class="btn btn-info btn-xs" data-toggle="modal" data-target="#confirm-delete" 
                              onclick="SHOW_THE_MODEL(' . $measurement['product_code'] . ', ' . $measurement['price_id'] . ', \'' . $measurementDetails . '\', \'' . $image . '\', \'' . $product_name . '\', \'' . $current_status . '\')">
                            View
                        </span>
                    </td>
                  </tr>';
    }
    return $rows;
}

$response .='  <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">Date of adding</th>
                                <th style="width:10%">Products Name</th>
                                <th style="width:15%">Instock</th>
                                <th style="width:10%">Out of Stock</th>
                                <th style="width:10%">Stop Selling</th>
                            </tr>
                        </thead>
                        <tbody >';

while ($results_of = mysqli_fetch_array($category_list)) {
    $image = $results_of['img'];
    $product_name = $results_of['dish_name'];

    $measurement_list = MEASUREMENT_LOOP($con, $results_of['rs_id'], 'Instock');
    $measurement_list_1 = MEASUREMENT_LOOP($con, $results_of['rs_id'], 'Currently Unavailable');
    $measurement_list_2 = MEASUREMENT_LOOP_1($con, $results_of['rs_id'], 'Inactive');

    if($current_sts == '1'){
        if(empty($measurement_list)){ continue; }
    }
    if($current_sts == '3'){
        if(empty($measurement_list_1)){ continue; }
    }
    if($current_sts == '2'){
        if(empty($measurement_list_2)){ continue; }
    }

    $response .= '<tr><td>'.$results_of['date_of_adding'].'</td>';
    $response .= '<td>';
    $response .= '<img src="./' . $image . '" onerror="this.onerror=null; this.src=\'Res_img/no_image.png\';" style="width:40px; height:40px;" alt=""><br>';
    $response .= '<span class="text-bold">' . $product_name . '</span>';
    $response .= '</td>';

    $response .= '<td><table class="table table-borderless" style="background-color:transparent !important;">';
    $response .= createMeasurementRows($measurement_list, $image, $product_name, 'Instock');
    $response .= '</table></td>';

    $response .= '<td><table class="table table-borderless" style="background-color:transparent !important;">';
    $response .= createMeasurementRows($measurement_list_2, $image, $product_name, 'Currently Unavailable');
    $response .= '</table></td>';

    $response .= '<td><table class="table table-borderless" style="background-color:transparent !important;">';
    $response .= createMeasurementRows($measurement_list_1, $image, $product_name, 'Inactive');
    $response .= '</table></td>';

    $response .= '</tr>';
}
$response .= '</tbody>
                    </table>';

echo json_encode(['status'=>1,'response'=>$response]);

?>