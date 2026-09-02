<?php 
session_start();
error_reporting(0);
include('./inc/config.php');

if (isset($_POST['order_id'])) {

    

    $order_id = mysqli_real_escape_string($con, $_POST['order_id']); 

    function Shipping($con, $id) {
        $addressQuery = mysqli_query($con, "SELECT * FROM address WHERE id='$id'");
        if ($getAddress = mysqli_fetch_array($addressQuery)) {
            $nf = htmlspecialchars($getAddress['fname']);
            $mb = htmlspecialchars($getAddress['mobile']);
            $flt = htmlspecialchars($getAddress['flat']);
            $are = htmlspecialchars($getAddress['country']);
            $pin = htmlspecialchars($getAddress['pin']);
            $district = htmlspecialchars($getAddress['district']);
            $ste = htmlspecialchars($getAddress['state']);
    
            return generateShippingHtml($nf, $flt, $district, $ste, $are, $pin, $mb);
        }
        return ''; 
    }
    
    function generateShippingHtml($nf, $flt, $district, $ste, $are, $pin, $mb) {
        return ' <span class="bold" style="display:block;">Shipping Address</span>
                    <h5 class="product-title">
                        <a href="#" class="pr-name" id="addressTitle">' . $nf . '.</a>
                    </h5>
                    <address id="addressDetails">
                        ' . $flt . ',<br>' . $district . ', ' . $ste . ', ' . $are . ' - ' . $pin . '.<br>
                        Phone No: ' . $mb . '.
                    </address>
              ';
    }

    function PayMethod(){
        return ' <span class="bold" style="display:block;">Payment Methods</span>
                    <h5 class="product-title">
                        <a href="#"  id="addressTitle">Razor pay</a>
                    </h5>
              ';
    }





    $response_part_1 = '';
    $response_part_2 ='';
    $response_part_3 ='';


    $response_part_3 = '<table class="table" >
    <tr class="bg-primary">
        <th>#</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price/Unit</th>
        <th>GST</th>
        <th>Amount</th>
    </tr>';



    $total_qty =0;
    $total_off =0 ;
    $shipping_amt =0;
    $promo_amt =0;
    $s_no =0;
    $transaction_id =0;
    $query = "SELECT final.*, chekout.*, order_sts.* 
              FROM final 
              INNER JOIN chekout ON final.refid = chekout.ref_id 
              INNER JOIN order_sts ON final.order_id = order_sts.order_id 
              INNER JOIN (SELECT order_id, MAX(status) AS max_status 
              FROM order_sts  GROUP BY order_id HAVING MAX(status) <= 6) AS max_status_table 
              ON order_sts.order_id = max_status_table.order_id
              AND order_sts.status = max_status_table.max_status
              WHERE final.status = '0' AND order_sts.status = '3' AND final.order_id = '$order_id'
              GROUP BY final.order_id                                                               
              ORDER BY max_status_table.max_status DESC";

    $sql = mysqli_query($con, $query);
    if (!$sql) {
        die("Query Failed: " . mysqli_error($con));
    }

    if (mysqli_num_rows($sql) > 0) {
       if($ree = mysqli_fetch_assoc($sql)){
        $itemQuery1 = mysqli_query($con,"SELECT * FROM final WHERE order_id = " . $order_id);
        while($item_of_products = mysqli_fetch_array($itemQuery1)){ 
        $total_qty += $item_of_products['qty'];
        $total_off += $item_of_products['sel_price'];
        $shipping_amt = $item_of_products['shipping_amt'] ? $item_of_products['shipping_amt'] : 0;
        $promo_amt = $item_of_products['promo_amt'] ? $item_of_products['promo_amt'] : 0;


        $dish_category = mysqli_query($con,"SELECT * FROM dishes where rs_id=". $item_of_products['product_id']);
        $dish_details = mysqli_fetch_array($dish_category);
        if($transaction_id ==0){
            $transaction_id = $item_of_products['trans_id'];
        }
        $single_prod_price = $item_of_products['sel_price'] / $item_of_products['qty'];


        $dish_category = mysqli_query($con, "SELECT * FROM dishes WHERE rs_id=" . $item_of_products['product_id']);
                $dish_details = mysqli_fetch_array($dish_category);
                $s_no++;

                if ($transaction_id == 0) {
                    $transaction_id = $item_of_products['trans_id'];
                }

                $single_prod_price = $item_of_products['sel_price'] / $item_of_products['qty'];

        $response_part_3 .= '<tr>
                        <td><img src="./'.$dish_details['img'].'" style="width:50px; height:50px; "></td>
                        <td style="width:25%">
                
                            <span class="text-dark">' . $dish_details['dish_name'] . '</span>
                        </td>
                        <td style="width:15%">
                            <span>Qty: ' . $item_of_products['qty'] . '</span>
                        </td>
                        <td style="width:15%">
                            <span>Rs. ' . number_format($single_prod_price, 2) . '</span>
                        </td>
                        <td style="width:15%" class="text-left">
                            <span>' . $item_of_products['gst_per'] . ' %</span>
                        </td>
                        <td style="width:15%" class="text-left">
                            <span>Rs. ' . number_format($item_of_products['sel_price'], 2) . '</span>
                        </td>
                    </tr>';

        }
        $response_part_3 .= '</table>';
       
        $response_part_1 = '<div class="part_1">
        <span class="bold" style="border-right:0.1px solid rgb(226, 226, 226);padding-right:6px;">Ordered On '.$ree['order_date'].'</span> 
        <span  class="bold">Order Id:  #'.$ree['order_id'].'</span>
       <span style="float:right;margin-right:10px;"> <span class="bold">Transaction Id: </span> <span class="bold">#'.$transaction_id.' </span></span>
        </div>';

        $response_part_2 = '<div class="row">
        <div class="col-4 col-lg-4">' . Shipping($con, $ree['address_id']) . '</div>
        <div class="col-4 col-lg-4">'.PayMethod().'</div>
         <div class="col-4 col-lg-4 text-center">
         <span class="bold" style="display:block;">Order Summary</span>
         <table class="table">
         <tr><th>'.$ree['items'].' Item(s) Subtotal</th><td> ₹. '.$total_off.'</td></tr>
          <tr><th>Subtotal (disc & tax incl.)</th><td> ₹. '.$ree['total_amt'].'</td></tr>
          <tr><th>Shipping</th><td> ₹. '.$shipping_amt .'</td></tr>
          <tr><th>Promotion Applied</th><td> ₹. '.$promo_amt.'</td></tr>
             <tr><th><span style="font-weight:bold;font-size:15px;">Grand Total:<span></th><td><span style="font-weight:bold;font-size:15px;"> ₹. '.$ree['final_amt'].'</span></td></tr>
         </table>
         </div>
      
    </div>'; 






       }
    }

    

    echo json_encode([
        'status' => 1,
        'response_1' => $response_part_1,
        'response_2' => $response_part_2,
        'response_3'=>$response_part_3
    ]);
}
?>