<?php
session_start();
error_reporting(0);
include('./inc/config.php');

if (isset($_POST['product'])) {

    $response = '';
    $response_1 = '';

    function NUMBER_OF_PRODUCTS($con, $orders_lists, $price_id, $filter_date)
    {
        if (!empty($price_id)) {
            $price_check = "AND price_id = '$price_id'";
        } else {
            $price_check = '';
        }
        $select = mysqli_query($con, "SELECT sum(qty) as qt_list FROM final WHERE product_id = '$orders_lists' $price_check $filter_date");
        $row = mysqli_fetch_array($select);
        return $row['qt_list'] ?? 0;
    }

    function CUSTOMER_NAME($con, $id)
    {
        $select = mysqli_query($con, "SELECT fname FROM address WHERE id= $id LIMIT 1");
        $row = mysqli_fetch_array($select);
        return $row['fname'];
    }

    $filter_date = '';
    $formattedStartDate = '';
    $formattedEndDate = '';

    if (!empty($_POST['startDates']) && !empty($_POST['endDates'])) {

        $startDate = DateTime::createFromFormat('Y-m-d', $_POST['startDates']);
        $endDate = DateTime::createFromFormat('Y-m-d', $_POST['endDates']);

        $formattedStartDate = $startDate->format('d-m-Y');
        $formattedEndDate = $endDate->format('d-m-Y');



        $filter_date = "AND order_date BETWEEN '$formattedStartDate' AND '$formattedEndDate'";
    }



    $product = mysqli_real_escape_string($con, $_POST['product']);
    $price_id = mysqli_real_escape_string($con, $_POST['price']);
    $dish_name = mysqli_real_escape_string($con, $_POST['dish_name']);
    $meas = mysqli_real_escape_string($con, $_POST['meas']);
    $img = mysqli_real_escape_string($con, $_POST['dish_img']);


    $current_status_of = mysqli_query($con, "SELECT stk_status FROM prd_stock WHERE pd_code='$product' AND price_id = {$price_id} ORDER BY id DESC LIMIT 1");
    $current_sta = mysqli_fetch_array($current_status_of);


    $response = '<div class="row">
    <div class="col-12 col-lg-12">
        <div class="row">
         <div class="col-2 col-lg-2">
         <img src="' . $img . '" style="width:100%;height:100px">
         </div>
            <div class="col-3 col-lg-3">
            <b style="display:block; margin-bottom:20px;">Product Name: <span>' . $dish_name . '</span></b>
            <b>Measurement: <span>' . $meas . '</span></b></div>
           
            <div class="col-3 col-lg-3"><b>No. of items Sold: <span>' . NUMBER_OF_PRODUCTS($con, $product, $price_id, $filter_date) . '</span></b></div>';

    if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
        $response .= '<div class="col-3 col-lg-3"><b style="display:block;margin-bottom:20px;">From Date: <span>' . $formattedStartDate . '</span></b>
    <b style="display:block;margin-bottom:20px;">To Date:     <span>' . $formattedEndDate . '</span></b></div>';
    }

    $response .= '        </div>
        </div>
    </div>';


    $select = mysqli_query($con, "SELECT * FROM final WHERE product_id = '$product' AND price_id = '$price_id' $filter_date ");

    $response_1 = '<table class="table">
        <tr>
             <th>Ordered Date</th>
            <th>Order Id</th>
            <th>Customer Name</th>
       
            <th>No. of Items</th>
        </tr>';

    while ($row = mysqli_fetch_array($select)) {
        $response_1 .= '<tr>
         <td>' . htmlspecialchars($row['order_date']) . '</td>
            <td>' . htmlspecialchars($row['order_id']) . '</td>
            <td>' .  CUSTOMER_NAME($con, $row['address_id']) . '</td>
            <td>' . htmlspecialchars($row['qty']) . '</td>
        </tr>';
    }

    $response_1 .= '</table>';



    echo json_encode(['status' => 1, 'response' => $response, 'response_1' => $response_1]);
}
