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
//  total-qty
function Qty_of($con, $where)
{
    $where = mysqli_real_escape_string($con, $where);
    $query = "SELECT SUM(qty) AS totals FROM final WHERE order_id = '$where'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['totals'] ?? 0;
}


function FINAL_PRICE($con)
{
    $total = 0;
    $select = mysqli_query($con, "SELECT final_amt FROM final GROUP BY order_id");
    while ($row = mysqli_fetch_array($select)) {
        $total += $row['final_amt'];
    }
    return $total;
}



function Orders_listOut($con, $where, $where_2)
{
    $orders = [];
    $stmt = mysqli_query($con, "SELECT COUNT(DISTINCT order_id) AS total_orders FROM final $where");
    $row = mysqli_fetch_array($stmt);
    $orders[0] = $row['total_orders'];
    // end
    $final_price_of = 0;

    $count_s = mysqli_query($con, "SELECT f.final_amt FROM final AS f INNER JOIN order_sts AS os ON f.order_id = os.order_id INNER JOIN (SELECT order_id, MAX(status) AS max_status  FROM order_sts GROUP BY 
     order_id HAVING  MAX(status) <= 2) AS max_status_table ON os.order_id = max_status_table.order_id  AND os.status = max_status_table.max_status WHERE  $where_2 GROUP BY  f.order_id ORDER BY 
    max_status_table.max_status DESC, f.id DESC;");


    while ($count_s_value = mysqli_fetch_array($count_s)) {
        $final_price_of += $count_s_value['final_amt'];
    }

    $orders[1] = $final_price_of;
    // end

    $order_sts_full = mysqli_query($con, "SELECT final_amt FROM final INNER JOIN order_sts ON final.order_id = order_sts.order_id WHERE $where_2 AND order_sts.status = '6' GROUP BY final.order_id ");

    $final_price_of_1 = 0;

    while ($order_sts_value = mysqli_fetch_array($order_sts_full)) {
        $final_price_of_1 += $order_sts_value['final_amt'];
    }
    $orders[2] = $final_price_of_1;
    // end

    $order_sts_full = mysqli_query($con, "SELECT final_amt FROM final INNER JOIN order_sts ON final.order_id = order_sts.order_id WHERE $where_2 AND order_sts.status = '3' GROUP BY final.order_id ");
    $final_price_of_2 = 0;
    while ($order_sts_full_f = mysqli_fetch_array($order_sts_full)) {
        $final_price_of_2 +=  $order_sts_full_f['final_amt'];
    }
    $orders[3] = $final_price_of_2;

    return $orders;
}

$current_date = date('d-m-Y');
$currentDate = new DateTime();
$currentDate->modify('-30 days');
$futureDate = $currentDate->format('d-m-Y');


$results = Orders_listOut($con, "WHERE order_date = '$current_date'", "sts_date = '$current_date'");

$results_2 = Orders_listOut($con, "WHERE STR_TO_DATE(order_date, '%d-%m-%Y') BETWEEN STR_TO_DATE('$futureDate', '%d-%m-%Y') AND STR_TO_DATE('$current_date', '%d-%m-%Y')", "STR_TO_DATE(sts_date, '%d-%m-%Y') BETWEEN STR_TO_DATE('$futureDate', '%d-%m-%Y') AND STR_TO_DATE('$current_date', '%d-%m-%Y')");


?>
<!-- end  -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<section class="content-header">
    <div class="content-header-left">
        <h1>My Orders</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table">CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print</button>
        <button class="btn btn-primary btn-xs" id="download_pdf">PDF</button>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 ">
            <div class="box box-info" style="padding:10px;">
                <table class="table  table-bordered">
                    <tr class="bg-info">
                        <th>#</th>
                        <th>Orders</th>
                        <th>Received Amount (₹)</th>
                        <th>Refund Amount (₹)</th>
                        <th>Fullfilled Amount (₹)</th>
                    </tr>
                    <tr>
                        <th>Today</th>
                        <td><?= $results[0] ?? 0; ?></td>
                        <td><?= $results[1] ?? 0; ?></td>
                        <td><?= $results[2] ?? 0; ?></td>
                        <td><?= $results[3] ?? 0; ?></td>
                    </tr>
                    <tr>
                        <th>Last 30 days</th>
                        <td><?= $results_2[0] ?? 0; ?></td>
                        <td><?= $results_2[1] ?? 0; ?></td>
                        <td><?= $results_2[2] ?? 0; ?></td>
                        <td><?= $results_2[3] ?? 0; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    
                        <div class="row align-items-center mt-5" style="padding-top:15px !important;">
                    <div class="col-md-3 mt-5" style="margin-left:20px;">
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
                            <option value="placed">Placed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="Order Refund">Refund</option>
                            <option value="Order delivered">Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary mt-4" style="margin-top:20px;"
                            onclick="filterByDateRange()">Filter</button>
                    </div>
                </div>
                    <table id="example2" class="table  table-bordered table-hover table-striped">
                       
                        <thead>
                            <tr>
                                <th style="width:8%">Date</th>
                                <th style="width:15%">Order Id</th>
                                <th style="width:15%">Customer Name</th>
                                <th style="width:8%">In Amount<span id="totalAmountHeader"><strong>(Total
                                            0.00)</strong></span></th>
                                <th style="width:8%">Out Amount<span id="totalAmountHeader1"><strong>(Total
                                            0.00)</strong></span></th>
                                <th style="width:10%">Payment Status</th>
                                <th style="width:10%">Fullfilled Status</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php
    function Show_Shipment($con, $order_id)
    {
        $stmt = $con->prepare("SELECT status FROM shipment WHERE order_id = ? ORDER BY status DESC LIMIT 1");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $values = $result->fetch_assoc();
        $stmt->close();

        return $values['status'] ?? null;
    }

    $query = "SELECT final.*, chekout.*, order_sts.* 
    FROM final 
    INNER JOIN chekout ON final.refid = chekout.ref_id 
    INNER JOIN order_sts ON final.order_id = order_sts.order_id 
    INNER JOIN ( 
        SELECT order_id, MAX(status) AS max_status 
        FROM order_sts 
        GROUP BY order_id 
        HAVING MAX(status) <= 6 
    ) AS max_status_table ON order_sts.order_id = max_status_table.order_id AND order_sts.status = max_status_table.max_status 
    WHERE final.status = '0' 
    GROUP BY final.order_id 
    ORDER BY max_status_table.max_status DESC, final.id DESC";
    $sql = mysqli_query($con, $query);
    while ($orders = mysqli_fetch_array($sql)) {
        $user_id = $orders['user_id'];
        $payment_sts = ($orders['status'] == 4) ? 'Failed' : 'Paid';
        $payment_color = ($orders['status'] == 4) ? 'text-danger' : 'text-success';

        // Fetch the order placed date or refund date based on status
        if ($orders['status'] == 6) {
            // Refund Status
            $refund_query = $con->prepare("SELECT sts_date FROM order_sts WHERE order_id = ? AND status = 6 ORDER BY sts_date ASC LIMIT 1");
            $refund_query->bind_param("s", $orders['order_id']);
            $refund_query->execute();
            $result = $refund_query->get_result();
            $refund = $result->fetch_assoc();
            $order_date = $refund['sts_date'] ?? 'Refund Date Not Found'; // Refund date
            $refund_query->close();

            // Add Order Placed details
            $order_placed_query = $con->prepare("SELECT sts_date FROM order_sts WHERE order_id = ? AND status = 0 ORDER BY sts_date ASC LIMIT 1");
            $order_placed_query->bind_param("s", $orders['order_id']);
            $order_placed_query->execute();
            $result = $order_placed_query->get_result();
            $order_placed = $result->fetch_assoc();
            $order_placed_date = $order_placed['sts_date'] ?? 'No Date Found'; // Order placed date
            $order_placed_query->close();

            // Display the "Order Placed" row
            ?>
            <tr>
                <td><?= htmlspecialchars($order_placed_date); ?></td>
                <td>#<?= htmlspecialchars($orders['order_id']); ?></td>
                <?php
                // Fetch the user details from the address table
                $user_query = "SELECT * FROM address WHERE userid = '$user_id'";
                $user_result = mysqli_query($con, $user_query);
                if ($user_row = mysqli_fetch_array($user_result)) {
                    ?>
                    <td><?php echo htmlspecialchars($user_row['fname']); ?></td>
                    <?php
                } else {
                    ?>
                    <td>User not found</td>
                    <?php
                }
                ?>
              <td><?=$orders['final_amt'] ?></td>
              <td>-</td>
                <td> <span style="flex-grow:4;" class="w-100 text-success"><?= htmlspecialchars($payment_sts); ?></span></td>
                <td style="display:flex;gap:8px;"><span style="flex-grow:4;" class="text-danger w-100 ">Order Cancelled</span></td>
            </tr>
            <?php
            ?>
            <tr>
                <td><?= htmlspecialchars($order_date); ?></td>
                <td>#<?= htmlspecialchars($orders['order_id']); ?></td>
                <?php
                if ($user_row) {
                    ?>
                    <td><?php echo htmlspecialchars($user_row['fname']); ?></td>
                    <?php
                } else {
                    ?>
                    <td>User not found</td>
                    <?php
                }
                ?>
                <td>-</td>
                <td><?= htmlspecialchars($orders['final_amt']); ?></td>
                <td ><span class=' text-danger'>Refunded</span></td>
                <td style="display:flex;gap:8px;"><span style="flex-grow:4;" class="text-warning w-100 ">Order Refund</span></td>
            </tr>
            <?php
        } else {
            // Handle other statuses as usual
            // Fetch order placed date
            $order_placed_query = $con->prepare("SELECT sts_date FROM order_sts WHERE order_id = ? AND status = 0 ORDER BY sts_date desc LIMIT 1");
            $order_placed_query->bind_param("s", $orders['order_id']);
            $order_placed_query->execute();
            $result = $order_placed_query->get_result();
            $order_placed = $result->fetch_assoc();
            $order_placed_date = $order_placed['sts_date'] ?? 'No Date Found'; // Order placed date
            $order_placed_query->close();

            $in_amount = 0;
            $out_amount = '-';
            switch ($orders['status']) {
                case 0:
                case 1:
                case 2: 
                case 3: 
                    $in_amount += $orders['final_amt'];
                    break;
                case 5: 
                    $in_amount += $orders['final_amt'];
                    $out_amount = '-';
                    break;
                case 6: 
                    $return=mysqli_query($con,"select * from refund where order_id=$orders[order_id]");
                    if($return_fund=mysqli_fetch_array($return)){
                        $out_amount = $orders['refund_amt'];
                    }

                   // $out_amount = $orders['final_amt'];
                    break;
            }

            // Retrieve shipment status
            $latestStatus = Show_Shipment($con, $orders['order_id']);
            $latestOrderStatus = $orders['status'];

            // Determine the final status to display
            switch ($latestOrderStatus) {
                case 6:
                    $full_sts = 'Order Refund';
                    $full_color = 'text-warning';
                    break;
                case 4:
                    $full_sts = 'Order Declined';
                    $full_color = 'text-danger';
                    break;
                case 5:
                    $full_sts = 'Order Cancelled';
                    $full_color = 'text-danger';
                    break;
                case 3:
                    $full_sts = 'Order Delivered';
                    $full_color = 'text-secondary';
                    break;
                default:
                    if ($latestStatus === null) {
                        $full_sts = 'Order Placed';
                        $full_color = 'text-primary';
                    } elseif ($latestStatus == 0) {
                        $full_sts = 'Order Placed';
                        $full_color = 'text-primary';
                    } elseif ($latestStatus == 1) {
                        $full_sts = 'Order Processed';
                        $full_color = 'text-success';
                    } elseif ($latestStatus == 2) {
                        $full_sts = 'Order Packing';
                        $full_color = 'text-success';
                    }
                    break;
            }
            ?>
            <tr>
                <td><?= ($order_placed_date !== null) ? htmlspecialchars($order_placed_date) : 'No date found'; ?></td>
                <td>#<?= htmlspecialchars($orders['order_id']); ?></td>
                <?php
                // Fetch the user details from the address table
                $user_query = "SELECT * FROM address WHERE userid = '$user_id'";
                $user_result = mysqli_query($con, $user_query);
                if ($user_row = mysqli_fetch_array($user_result)) {
                    ?>
                    <td><?php echo htmlspecialchars($user_row['fname']); ?></td>
                    <?php
                } else {
                    ?>
                    <td>User not found</td>
                    <?php
                }
                ?>
                <td><?= ($in_amount == 0) ? '-' : htmlspecialchars($in_amount); ?></td>
                <td><?= ($out_amount === '-') ? '-' : htmlspecialchars($out_amount); ?></td>
                <td>
                    <span class=' <?= htmlspecialchars($payment_color); ?>'><?= htmlspecialchars($payment_sts); ?></span>
                </td>
                <td style="display:flex;gap:8px;">
                    <span style="flex-grow:4;" class=" w-100 <?= htmlspecialchars($full_color); ?>"><?= htmlspecialchars($full_sts); ?></span>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</tbody>

                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                                <td id="totalAmount"><strong>₹ <?=$total; ?></strong></td>
                                <td id="totalAmount1"><strong>₹ <?=$total; ?></strong></td>
                                <td colspan="1"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
</section>
<div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
<script></script>
<div class="modal fade " id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="orders_status">
            <div class="modal-content" style="max-width: 1000px !important;">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Order Status</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="display:flex; justify-content:center;margin-top:10px"
                            id="append_results_right">
                        </div>
                        <div class="col-12 " style="margin-left:20px;margin-bottom:20px;">
                            <h6 class="modal-title" id="myModalLabel"><b>Order Status List: </b></h6>
                        </div>
                        <div class="col-md-12" id="append_left_side"></div>
                        <div class="col-12 col-md-12" id="mgs_update">
                            <label for="message">Message:</label>
                            <textarea name="Message" class="form-control" cols="30" rows="5" id="message"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="order_id" id="order_id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="submit_btn" class="btn btn-danger btn-ok">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require_once('footer.php'); ?>
<script>
  function calculateTotal() {
    var total = 0;
    
    // Loop through each visible row in the table
    $('#example2 tbody tr:visible').each(function() {
        var amountText = $(this).find('td:nth-child(4)').text().trim(); // Ensure column index is correct
        
        // Check if the amountText is a valid number or if it is '-'
        if (amountText !== '-' && amountText !== '') {
            var amount = parseFloat(amountText.replace(/[₹,]/g, '')); // Remove '₹' and commas

            // Add to the total if it's a valid number
            if (!isNaN(amount)) {
                total += amount;
            }
        }
    });

    // Format the total amount
    var totalFormatted = '₹ ' + total.toFixed(2);

    // Update the total amount in both the footer and the header
    $('#totalAmount').html('<strong>' + totalFormatted + '</strong>'); // Footer total
    $('#totalAmountHeader').html('<strong>(' + totalFormatted + ')</strong>'); // Header total
}

// Call the function
calculateTotal();

    function calculateTotal1() {
    var total = 0;
    
    // Loop through each visible row in the table
    $('#example2 tbody tr:visible').each(function() {
        var amountText = $(this).find('td:nth-child(5)').text().trim(); // Ensure column index is correct
        
        // Check if the amountText is a valid number or if it is '-'
        if (amountText !== '-' && amountText !== '') {
            var amount = parseFloat(amountText.replace(/[₹,]/g, '')); // Remove '₹' and commas

            // Add to the total if it's a valid number
            if (!isNaN(amount)) {
                total += amount;
            }
        }
    });

    // Format the total amount
    var totalFormatted = '₹ ' + total.toFixed(2);

    // Update the total amount in both the footer and the header
    $('#totalAmount1').html('<strong>' + totalFormatted + '</strong>'); // Footer total
    $('#totalAmountHeader1').html('<strong>(' + totalFormatted + ')</strong>'); // Header total
}

// Call the function
calculateTotal1();


    function Call_value_1(id_name_) {
        let order = document.getElementById("order" + id_name_).value;
        document.getElementById("order_id").value = order;
        let data = new FormData();
        data.append("id_name", order);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", 'my_orders_list.php', true);
        xhr.onload = function() {
            let result = JSON.parse(this.responseText);
            if (result.status == 1) {
                document.getElementById("append_results_right").innerHTML = result.right_side;
                document.getElementById("append_left_side").innerHTML = result.left_side;
                if (result.delivery_Status == 3) {
                    document.getElementById("mgs_update").style.display = 'none';
                    document.getElementById("submit_btn").style.display = 'none';
                } else {
                    document.getElementById("mgs_update").style.display = 'block';
                    document.getElementById("submit_btn").style.display = 'inline-block';
                }
            } else {
                console.log("error");
            }
        }
        xhr.send(data);
    }
    document.getElementById("orders_status").addEventListener("submit", function(e) {
        e.preventDefault();
        let loading_spinner = document.getElementById("loading_spinner");
        loading_spinner.style.display = "block";
        let data = new FormData(this);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", 'update_orders.php', true);

        xhr.onload = function() {
            loading_spinner.style.display = "none";
            let result = JSON.parse(this.responseText);
            if (result.status == 1) {
                location.reload();

            } else {
                alert('error');
            }
        };
        xhr.send(data);
    });

    function filterByDateRange() {
    var table = $('#example2').DataTable();
    var startDate = new Date(document.getElementById("startDate").value);
    var endDate = new Date(document.getElementById("endDate").value);
    var selectedStatus = document.getElementById("orderStatus").value;

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

        // Status filtering for only Refund, Placed, and Cancelled
        if (selectedStatus) {
            console.log("Selected status: " + selectedStatus); // Log selected status
            console.log("Status cell content: " + statusCell); // Log the status in the table row

            if (selectedStatus === 'placed' && !statusCell.includes('Placed')) {
                showRow = false;
            } else if (selectedStatus === 'Order Refund' && !statusCell.includes('Refund')) {
                showRow = false;
            } else if (selectedStatus === 'cancelled' && !statusCell.includes('Cancelled')) {
                showRow = false;
            }
            else if (selectedStatus === 'Order delivered' && !statusCell.includes('Delivered')) {
                showRow = false;
            }
        }

        if (showRow) {
            $(this.node()).show();
        } else {
            $(this.node()).hide();
        }
    });

    calculateTotal(); // Recalculate total after filtering
    calculateTotal1();
}

</script>
<script>
    $(document).ready(function() {
       // Initialize DataTable

        let currentFilter = ''; // Modify this as needed

        document.getElementById('export_table').addEventListener('click', function() {
            const table = document.getElementById('example2');
            const skipColumns = ["Download Invoice"]; // Columns to skip
            let csvContent = '';

            function escapeCSV(value) {
                if (value.includes('"') || value.includes(',') || value.includes('\n')) {
                    value = `"${value.replace(/"/g, '""')}"`;
                }
                return value;
            }
            const now = new Date();
            const dateOptions = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            };
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const formattedDate = now.toLocaleDateString('en-GB', dateOptions);
            const formattedTime = now.toLocaleTimeString('en-GB', timeOptions);
            // Prepare CSV Headers
            const headers = table.querySelectorAll('thead th');
            const headerIndices = [];
            csvContent += `Date: ${formattedDate} ${formattedTime}\n`; // Add date and time
            csvContent += 'AV Herbals\n';
            csvContent += 'Order Reports\n';
            const filterTitle = currentFilter ?
                `${currentFilter.charAt(0).toUpperCase() + currentFilter.slice(1)} Orders` : '';
            csvContent += `${filterTitle}\n\n`;
            headers.forEach((header, index) => {
                if (!skipColumns.includes(header.textContent.trim())) {
                    headerIndices.push(index);
                    csvContent += escapeCSV(header.textContent.trim()) + ',';
                }
            });
            csvContent = csvContent.slice(0, -1) + '\n'; 
            const tableInstance = $('#example2').DataTable();
            const originalLength = tableInstance.page.len();
            tableInstance.page.len(-1).draw(); 
            table.querySelectorAll('tbody tr').forEach(row => {
                let rowData = [];
                let cells = row.querySelectorAll('td');
                headerIndices.forEach(index => {
                    if (cells[index]) {
                        const editButton = cells[index].querySelector('.btn-info');
                        if (editButton) {
                            editButton.style.display = 'none';
                        }
                        rowData.push(escapeCSV(cells[index].textContent.trim()));
                    }
                });
                csvContent += rowData.join(',') + '\n';
            });
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'table.csv';
            document.body.appendChild(link);
            link.click(); // Trigger download
            document.body.removeChild(link);

            tableInstance.page.len(originalLength).draw(); // Restore original page length
            location.reload();
        });

        document.getElementById('print_table').addEventListener('click', function() {
            const table = document.getElementById('example2');
            const skipColumns = ["Download Invoice"]; // Columns to skip in print view
            function getColumnIndicesToSkip(headers) {
                const skipIndices = [];
                headers.forEach((header, index) => {
                    if (skipColumns.includes(header.textContent.trim())) {
                        skipIndices.push(index);
                    }
                });
                return skipIndices;
            }
            function removeColumns(row, indicesToSkip) {
                const cells = row.querySelectorAll('th, td');
                indicesToSkip.forEach(index => {
                    if (cells[index]) {
                        cells[index].style.display = 'none'; 
                                       }
                });
            }
            const now = new Date();
            const dateOptions = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            };
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const formattedDate = now.toLocaleDateString('en-GB', dateOptions);
            const formattedTime = now.toLocaleTimeString('en-GB', timeOptions);
            const tableInstance = $('#example2').DataTable();
            const originalLength = tableInstance.page.len();
            tableInstance.page.len(-1).draw();
            const printTable = table.cloneNode(true);
            const headers = printTable.querySelectorAll('thead th');
            const columnsToSkip = getColumnIndicesToSkip(headers);
            headers.forEach((header, index) => {
                if (columnsToSkip.includes(index)) {
                    header.style.display = 'none';
                }
            });
            printTable.querySelectorAll('tbody tr').forEach(row => {
                removeColumns(row, columnsToSkip);
                const actionButton = row.querySelector('td .btn-warnings');
                const editButton = row.querySelector('td .btn-infos');
                if (actionButton) {
                    actionButton.style.display = 'none';
                }
                if (editButton) {
                    editButton.style.display = 'none';
                }
            });
            const tableHTML = printTable.outerHTML;
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Table</title>');
            printWindow.document.write(
                '<style>' +
                'table { border-collapse: collapse; width: 100%; } ' +
                'th, td { border: 1px solid black; padding: 4px; text-align: left; font-size: 10px; } ' +
                'th { background-color: #f2f2f2; } ' +
                '@media print { ' +
                '  @page { size: A4 landscape; margin: 0; } ' +
                '  body { margin: 0; } ' +
                '  table { width: 100%; } ' +
                '  tr, td { page-break-inside: avoid; } ' +
                '}' +
                '</style>'
            );
            printWindow.document.write(`Date: ${formattedDate} ${formattedTime}<br>`);
            printWindow.document.write('<h1 style="text-align: center;">AV Herbals</h1>');
            printWindow.document.write('<h1 style="text-align: center;">Order Reports</h1>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(tableHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
            };
            tableInstance.page.len(originalLength).draw();
        });
        document.getElementById('download_pdf').addEventListener('click', function() {
            console.log('Showing all rows, check if pagination is removed.');
            $('#example2').DataTable().page.len(-1).draw();
            setTimeout(function() {
                const totalRows = document.querySelectorAll('#example2 tbody tr').length;
                console.log(`Total rows displayed: ${totalRows}`);
                if (totalRows === 0) {
                    console.error(
                        'No rows displayed. Pagination might still be active or table data is missing.'
                    );
                    return;
                }
                generatePDF();
            }, 1000);
        });

        function generatePDF() {
            const actionButtons = document.querySelectorAll('.btn-warnings');
            const editButtons = document.querySelectorAll('.btn-info');
            const downloadButtons = document.querySelectorAll('.btn-download-invoice');
            const table = document.querySelector('#example2');
            const actionColumnIndex = Array.from(document.querySelectorAll('#example2 th')).findIndex(th => th
                .textContent.trim() === 'Action');
            const editColumnIndex = Array.from(document.querySelectorAll('#example2 th')).findIndex(th => th
                .textContent.trim() === 'Edit');
            const downloadColumnIndex = Array.from(document.querySelectorAll('#example2 th')).findIndex(th => th
                .textContent.trim() === 'Download Invoice');
            hideColumn(actionColumnIndex);
            hideColumn(editColumnIndex);
            hideColumn(downloadColumnIndex);
            actionButtons.forEach(button => {
                button.style.display = 'none';
            });
            editButtons.forEach(button => {
                button.style.display = 'none';
            });
            downloadButtons.forEach(button => {
                button.style.display = 'none';
            });
            const now = new Date();
            const dateOptions = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            };
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const formattedDate = now.toLocaleDateString('en-GB', dateOptions);
            const formattedTime = now.toLocaleTimeString('en-GB', timeOptions);
            html2canvas(table, {
                useCORS: true,
                scale: 3
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgWidth = 190;
                const pageHeight = pdf.internal.pageSize.height;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                let position = 45;
                pdf.setFontSize(10);
                pdf.text(`Date: ${formattedDate} ${formattedTime}`, pdf.internal.pageSize.width / 2, 20,
                    null, null, 'center');
                pdf.setFontSize(18);
                const marginTop = 7;
                pdf.text('AV Herbals', pdf.internal.pageSize.width / 2, 20 + marginTop, null, null,
                    'center');
                pdf.setFontSize(14);
                pdf.text('Order Reports', pdf.internal.pageSize.width / 2, 25 + marginTop, null, null,
                    'center');
                pdf.setFontSize(14);
                const reportTitle = currentFilter ?
                    `${currentFilter.charAt(0).toUpperCase() + currentFilter.slice(1)} Orders` : '';
                pdf.text(reportTitle, pdf.internal.pageSize.width / 2, 25 + 15, null, null, 'center');
                pdf.setDrawColor(0, 0, 0);
                pdf.setLineWidth(0.5);
                pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height - 10);
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height - 10);
                    pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                pdf.save('table.pdf');
                restoreButtonsAndColumns(actionColumnIndex, editColumnIndex, downloadColumnIndex,
                    actionButtons, editButtons, downloadButtons);
                $('#example2').DataTable().page.len(10).draw();
            }).catch(err => {
                console.error('Error generating PDF:', err);
                restoreButtonsAndColumns(actionColumnIndex, editColumnIndex, downloadColumnIndex,
                    actionButtons, editButtons, downloadButtons);
                $('#example2').DataTable().page.len(10).draw();
            });
        }

        function hideColumn(index) {
            if (index >= 0) {
                document.querySelectorAll(`#example2 th`)[index].style.display = 'none';
                document.querySelectorAll(`#example2 tr`).forEach(row => {
                    row.children[index].style.display = 'none';
                });
            }
        }

        function restoreButtonsAndColumns(actionColumnIndex, editColumnIndex, downloadColumnIndex, actionButtons,
            editButtons, downloadButtons) {
            actionButtons.forEach(button => {
                button.style.display = '';
            });
            editButtons.forEach(button => {
                button.style.display = '';
            });
            downloadButtons.forEach(button => {
                button.style.display = '';
            });
            if (actionColumnIndex >= 0) {
                document.querySelectorAll(`#example2 th`)[actionColumnIndex].style.display = '';
                document.querySelectorAll(`#example2 tr`).forEach(row => {
                    row.children[actionColumnIndex].style.display = '';
                });
            }
            if (editColumnIndex >= 0) {
                document.querySelectorAll(`#example2 th`)[editColumnIndex].style.display = '';
                document.querySelectorAll(`#example2 tr`).forEach(row => {
                    row.children[editColumnIndex].style.display = '';
                });
            }
            if (downloadColumnIndex >= 0) {
                document.querySelectorAll(`#example2 th`)[downloadColumnIndex].style.display = '';
                document.querySelectorAll(`#example2 tr`).forEach(row => {
                    row.children[downloadColumnIndex].style.display = '';
                });
            }
            location.reload();
        }
    });
</script>