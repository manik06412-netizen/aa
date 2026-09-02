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
error_reporting(0);
session_start();
?>
<?php require_once('header.php');
include('../controller/reuse.php');
?>
<link rel="stylesheet" href="./css/loader.css">
<style>
.bg-prim {
    background-color: rgb(146, 187, 211) !important;
}

.btn-secondary {
    background-color: rgb(108, 117, 125) !important;
    color: white;
}

.new_btn {
    background-color: #FF851B !important;
    color: white !important;
    border: 1px solid #FF851B !important;
}

.filter-options button {
    background-color: transparent;
    border: 1px solid #007bff;
    color: #007bff;
    margin: 3px;
    border-radius: 5px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-options button:hover {
    background-color: #007bff;
    color: #fff;
}

.filter-options button:focus {
    outline: none;
}
</style>

<?php 
// Total quantity function
function Qty_of($con, $where) {
    $where = mysqli_real_escape_string($con, $where);
    $query = "SELECT SUM(qty) AS totals FROM final WHERE order_id = '$where'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['totals'] ?? 0;
}

function Orders_listOut($con, $where, $where_2) {
    $orders = [];
    $stmt = mysqli_query($con,"SELECT COUNT(DISTINCT order_id) AS total_orders FROM final $where");
    $row = mysqli_fetch_array($stmt);
    $orders[0] = $row['total_orders'];

    $count_s = mysqli_query($con,"SELECT SUM(qty) AS totals FROM final $where");
    $count_s_value = mysqli_fetch_array($count_s);
    $orders[1] = $count_s_value['totals'];

    $order_sts = mysqli_query($con,"SELECT SUM(CASE 
        WHEN order_sts.status = 6 THEN qty 
        WHEN order_sts.status = 5 AND NOT EXISTS (SELECT 1 FROM order_sts os2 WHERE os2.order_id = order_sts.order_id AND os2.status = 6) THEN qty
        ELSE 0 END) AS total_qty 
        FROM order_sts 
        INNER JOIN final ON order_sts.order_id = final.order_id 
        WHERE $where_2 AND order_sts.status IN (5, 6)");

    $order_sts_value = mysqli_fetch_array($order_sts);
    $orders[2] = $order_sts_value['total_qty'];

    $order_sts_full = mysqli_query($con,"SELECT COUNT(id) AS packing FROM order_sts WHERE $where_2 AND status = 2");
    $order_sts_full_f = mysqli_fetch_array($order_sts_full);
    $orders[3] = $order_sts_full_f['packing'];

    return $orders;
}

$current_date = date('d-m-Y');
$currentDate = new DateTime();
$currentDate->modify('-30 days');
$futureDate = $currentDate->format('d-m-Y');

$results = Orders_listOut($con, "WHERE order_date = '$current_date'", "sts_date = '$current_date'");
$results_2 = Orders_listOut($con, "WHERE STR_TO_DATE(order_date, '%d-%m-%Y') BETWEEN STR_TO_DATE('$futureDate', '%d-%m-%Y') AND STR_TO_DATE('$current_date', '%d-%m-%Y')", "STR_TO_DATE(sts_date, '%d-%m-%Y') BETWEEN STR_TO_DATE('$futureDate', '%d-%m-%Y') AND STR_TO_DATE('$current_date', '%d-%m-%Y')");

?>
<!-- end -->
<section class="content-header">
    <div class="content-header-left">
        <h1>My Orders</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table">Export to CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print Table</button>
        <a href="#" class="btn new_btn btn-xs">Create Order</a>
    </div>
</section>
<section class="content">

    <div class="row">

        <div class="col-md-12">

            <div class="box box-info  ml-2"   >
            <div class="row align-items-center">
    <div class="col-md-3"style="margin-left:20px;">
        <label for="startDate">Start Date:</label>
        <input type="date" class="form-control" id="startDate" required>
    </div>
    <div class="col-md-3">
        <label for="endDate">End Date:</label>
        <input type="date" class="form-control" id="endDate" required>
    </div>
    <div class="col-md-2">
        <label for="orderStatus">Order Status:</label>
        <select class="form-control" id="orderStatus">
            <option value="">All Orders</option>
            <option value="placed">Order Placed</option>
            <option value="Order Processed">Order Processed</option>
            <option value="Order Packing">Order Packing</option>
            <option value="delivered">Order Delivered</option>
            <option value="cancelled">Order Cancelled</option>
            <option value="Order Refund">Order Refund</option> 
            <option value="Order Declined">Order Declined</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary mt-4" style="margin-top:20px;" onclick="filterByDateRange()">Filter</button>
    </div>
</div>


                <div class="box-body table-responsive">
                    <table id="order2" class="table table-bordered table-hover table-striped">
                    <thead>
    <tr>
        <th style="width:8%">Date</th>
        <th style="width:15%">Order Id</th>
        <th style="width:15%">Customer Name</th>
        <th style="width:12%">Amount <span id="totalAmountHeader"><strong>(Total 0.00)</strong></span></th> <!-- Display total here -->
        <th style="width:10%">Items</th>
        <th style="width:10%">Payment Status</th>
        <th style="width:10%">Fulfillment Status</th>
    </tr>
</thead>

                        <tbody>
                            <?php 

                            function Show_Shipment($con, $order_id) {
                                $stmt = $con->prepare("SELECT status FROM shipment WHERE order_id = ? ORDER BY status DESC LIMIT 1");
                                $stmt->bind_param("s", $order_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $values = $result->fetch_assoc();
                                $stmt->close();

                                return $values['status'] ?? null; 
                            }

                            $query ="SELECT final.*, chekout.*, order_sts.* FROM final 
                                     INNER JOIN chekout ON final.refid = chekout.ref_id 
                                     INNER JOIN order_sts ON final.order_id = order_sts.order_id 
                                     INNER JOIN (SELECT order_id, MAX(status) AS max_status FROM order_sts
                                                 GROUP BY order_id HAVING MAX(status) <= 6) AS max_status_table 
                                     ON order_sts.order_id = max_status_table.order_id 
                                     AND order_sts.status = max_status_table.max_status 
                                     WHERE final.status = '0' 
                                     GROUP BY final.order_id 
                                     ORDER BY max_status_table.max_status DESC, final.id DESC";

                            $sql = mysqli_query($con, $query);
                            while ($orders = mysqli_fetch_array($sql)) {
                                $whereClause = 'user_id ="'.$orders['user_id'].'"';
                                $user_details = AlreadyRegisterCheck('user', $whereClause, $con);
                                
                                $payment_sts = '';
                                $payment_color = '';
                                $full_sts = '';
                                $full_color = '';

                                if($orders['status'] == 4){
                                    $payment_sts = 'Failed';
                                    $payment_color = 'btn-danger';
                                } elseif($orders['status'] == 6){
                                    $payment_sts = 'Refunded';
                                    $payment_color = 'btn-warning';
                                } else {    
                                    $payment_sts = 'Paid';
                                    $payment_color = 'btn-success'; 
                                }

                                switch ($orders['status']) {
                                    case 0:
                                        $full_sts = 'Order Placed';
                                        $full_color = 'text-primary';
                                        break;
                                    case 1:
                                        $full_sts = 'Order Processed';
                                        $full_color = 'text-success';
                                        break;
                                    case 2:
                                        $full_sts = 'Order Packing';
                                        $full_color = 'text-success';
                                        break;
                                    case 3:
                                        $full_sts = 'Order Delivered';
                                        $full_color = 'text-secondary';
                                        break;
                                    case 4:
                                        $full_sts = 'Order Declined';
                                        $full_color = 'text-danger';
                                        break;
                                    case 5:
                                        $full_sts = 'Order Cancelled';
                                        $full_color = 'text-danger';
                                        break;
                                    case 6:
                                        $full_sts = 'Order Refund';
                                        $full_color = 'text-warning';
                                        break;
                                }

                                $order_id = $orders['order_id'];
                                $status = Show_Shipment($con, $order_id);
                                $status = $status ?? 0; 

                                ?>
                            <tr>
                                <td><?=$orders['sts_date']; ?></td>
                                <td>#<?=$orders['order_id']; ?></td>
                                <td><?=$user_details['user']['fname']; ?></td>
                                <td class="final-amt"><?=$orders['final_amt']; ?></td>
                                <td><?= Qty_of($con, $orders['order_id']); ?></td>
                                <td><span style="flex-grow:4;"
                                        class="btn btn-xs w-100 <?=$payment_color; ?>"><?=$payment_sts; ?></span></td>
                                <td style="display:flex;gap:8px;">
                                    <span style="flex-grow:4;"
                                        class="w-100 <?= $full_color; ?>"><b><?=$full_sts; ?></b></span>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                                <td id="totalAmount"><strong>₹ <?=$total; ?></strong></td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#order2').DataTable({
        "ordering": false,
        "info": false,
        "paging":false,
        "searching": false,
        "lengthChange": false,
        "order": []
    });

    calculateTotal(); // Call total calculation on page load
});

function calculateTotal() {
    var total = 0;
    // Iterate over each visible row
    $('#order2 tbody tr:visible').each(function() {
        // Get the amount value from the 4th column (Amount in ₹)
        var amount = parseFloat($(this).find('td:nth-child(4)').text().replace(/[₹,]/g, '')); // Remove '₹' and commas
        if (!isNaN(amount)) {
            total += amount;
        }
    });
    
    // Update the total amount in both the footer and the header
    var totalFormatted = '₹ ' + total.toFixed(2);
    $('#totalAmount').html('<strong>' + totalFormatted + '</strong>'); // Footer total
    $('#totalAmountHeader').html('<strong>(' + totalFormatted + ')</strong>'); // Header total
}


function filterByDateRange() {
    var table = $('#order2').DataTable();
    var startDate = new Date(document.getElementById("startDate").value);
    var endDate = new Date(document.getElementById("endDate").value);
    var selectedStatus = document.getElementById("orderStatus").value; // Get selected status

    endDate.setHours(23, 59, 59, 999); // End of day for the end date

    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var dateCell = this.data()[0]; // Assuming the date is in the first column
        var statusCell = this.data()[6]; // Assuming the status is in the 7th column
        var orderDate = new Date(dateCell.split("-").reverse().join("-"));

        var showRow = true;

        // Date filtering
        if (orderDate < startDate || orderDate > endDate) {
            showRow = false;
        }

        // Status filtering
        if (selectedStatus) {
            if (selectedStatus === 'placed' && !statusCell.includes('Order Placed')) {
                showRow = false;
            } else if (selectedStatus === 'delivered' && !statusCell.includes('Order Delivered')) {
                showRow = false;
            }else if (selectedStatus === 'Order Processed' && !statusCell.includes('Order Processed')) {
                showRow = false;
            }else if (selectedStatus === 'Order Refund' && !statusCell.includes('Order Refund')) {
                showRow = false;
            }else if (selectedStatus === 'Order Packing' && !statusCell.includes('Order Packing')) {
                showRow = false;
            }else if (selectedStatus === 'Order Declined' && !statusCell.includes('Order Declined')) {
                showRow = false;
            }else if (selectedStatus === 'Order Refund' && !statusCell.includes('Order Refund')) {
                showRow = false;
            }else if (selectedStatus === 'cancelled' && !statusCell.includes('Order Cancelled')) {
                showRow = false;
            }
            // else if (selectedStatus === 'cancelled' && !statusCell.includes('Order Declined') && !statusCell.includes('Order Cancelled') && !statusCell.includes('Order Refund')) {
            //     showRow = false;
            // }
        }

        if (showRow) {
            $(this.node()).show();
        } else {
            $(this.node()).hide();
        }
    });

    calculateTotal(); // Recalculate total after filtering
}

</script>

<?php require_once('footer.php'); ?>