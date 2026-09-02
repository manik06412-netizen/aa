<?php
session_start();
error_reporting(0);

if (isset($_POST['report_name'])) {
    $_SESSION['active_report'] = $_POST['report_name'];
    header("Location: " . $_SESSION['active_report']); 
    exit(); 
}
?>
<?php require_once('header.php');
include('../controller/reuse.php');
?>
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

function Orders_listOut($con, $where, $where_2)
{
    $orders = [];
    $stmt = mysqli_query($con, "SELECT COUNT(DISTINCT order_id) AS total_orders FROM final $where");
    $row = mysqli_fetch_array($stmt);
    $orders[0] = $row['total_orders'];
    // end
    $count_s = mysqli_query($con, "SELECT sum(qty) as totals FROM final $where");
    $count_s_value = mysqli_fetch_array($count_s);
    $orders[1] = $count_s_value['totals'];
    // end
    $order_sts = mysqli_query($con, "SELECT SUM( CASE 
            WHEN order_sts.status = 6 THEN qty WHEN order_sts.status = 5 
            AND NOT EXISTS ( SELECT 1 FROM order_sts os2
            WHERE os2.order_id = order_sts.order_id AND os2.status = 6) THEN qty
            ELSE 0 END ) AS total_qty FROM order_sts INNER JOIN  final ON order_sts.order_id = final.order_id
            WHERE  $where_2  AND order_sts.status IN (5, 6) ");

    $order_sts_value = mysqli_fetch_array($order_sts);
    $orders[2] = $order_sts_value['total_qty'];
    // end

    $order_sts_full = mysqli_query($con, "SELECT count(id) as packing FROM order_sts WHERE $where_2 AND status = 2");
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
<link rel="stylesheet" href="./css/loader.css">
<style>
.card {
    margin: 2px;
    border: 0.1px solid rgb(216, 216, 216);
    border-radius: 3px;
    text-align: center;
}

.report_title {
    font-weight: bold;
}

ul li {
    list-style: none;
}

.category_radio {
    display: none;
}

th {
    font-size: 13px !important;
}

td {
    font-size: 13px !important;
}

.report_label {
    display: block;
    padding: 10px;
    width: 100%;
    border: 0.1px solid rgb(216, 216, 216);
    cursor: pointer;
}

.report_label.selected {
    background-color: #d4edda;
    /* Light green color */
}
</style>

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

<!-- end -->
<section class="content-header">
    <div class="content-header-left">
        <h1>Order's Fullfillment Reports</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table"> CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print </button>
        <button class="btn btn-primary btn-xs" id="download_pdf"> PDF</button>
        <a href="reports_list.php" class="btn btn-xs" style="background-color:#FF851B; color:white; border:1px solid #FF851B;height:23px !important; font-size: 13px; margin-top:1px !important;"> Back</a>
    </div>
</section>
<?php 
$customers_category = [
    'Order by Customer' => 'report_order_customer.php',
    'Order by Item' => 'report_order_item.php',
    'Order Fullfillment' => 'report_order_fullfillment.php',
    'Order Placed' => 'report_order_placed.php',
    'Order Packed' => 'report_order_packing.php',
    'Order Delivered' => 'report_order_delivered.php',
    'Returned Orders' => 'report_order_return.php',
    'Packing History' => 'report_order_pkhistory.php'
];
?>

<section class="content">

    <div class="row">

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body ">
                    <div class="row">
                        <div class="col-2 col-lg-2">
                            <div class="card">
                                <h5 class="text-primary"><b>Order's Reports</b></h5>
                                <?php foreach($customers_category as $name => $page): ?>
                                <input type="radio" name="report_name" class="category_radio" id="category<?=$name; ?>"
                                    value="<?=$page; ?>" onchange="redirectToPage(this)"
                                    <?php echo (isset($_SESSION['active_report']) && $_SESSION['active_report'] === $page) ? 'checked' : ''; ?>>
                                <label
                                    class="report_label <?php echo (isset($_SESSION['active_report']) && $_SESSION['active_report'] === $page) ? 'selected' : ''; ?>"
                                    for="category<?=$name; ?>"><?=$name; ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-10 col-lg-10">
                            <div class="card">
                            <div class=" ml-2">
                <div class="row align-items-center">
                    <div class="col-md-3" style="margin-left:20px;">
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
                            <option value="Order Processed">Processed</option>
                            <option value="Order Packing">Packing</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="Order Refund">Refund</option>
                            <option value="Order Declined">Declined</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary mt-4" style="margin-top:20px;"
                            onclick="filterByDateRange()">Filter</button>
                    </div>
                </div>


                <div class="box-body table-responsive">
                    <table id="order2" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:8%">Date</th>
                                <th style="width:15%">Order Id</th>
                                <th style="width:15%">Customer Name</th>
                                <th style="width:12%">Amount <span id="totalAmountHeader"><strong>(Total
                                            0.00)</strong></span></th> <!-- Display total here -->
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
                                <td  style="text-align: left;"><?=$orders['sts_date']; ?></td>
                                <td  style="text-align: left;">#<?=$orders['order_id']; ?></td>
                                <?php

$user_id = $orders['user_id'];
$user_query = "SELECT * FROM address WHERE userid = '$user_id'";
$user_result = mysqli_query($con, $user_query);
if ($user_row = mysqli_fetch_array($user_result)) {

    ?>
                                <td style="text-align: left;">
                                <?php echo htmlspecialchars($user_row['fname']); ?></td>
                                <?php

}
?>
                                <td  style="text-align: left;"class="final-amt"><?=$orders['final_amt']; ?></td>
                                <td  style="text-align: left;"><?= Qty_of($con, $orders['order_id']); ?></td>
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
                    </div>
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
        "paging": false,
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
        var amount = parseFloat($(this).find('td:nth-child(4)').text().replace(/[₹,]/g,
        '')); // Remove '₹' and commas
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
            } else if (selectedStatus === 'Order Processed' && !statusCell.includes('Order Processed')) {
                showRow = false;
            } else if (selectedStatus === 'Order Refund' && !statusCell.includes('Order Refund')) {
                showRow = false;
            } else if (selectedStatus === 'Order Packing' && !statusCell.includes('Order Packing')) {
                showRow = false;
            } else if (selectedStatus === 'Order Declined' && !statusCell.includes('Order Declined')) {
                showRow = false;
            } else if (selectedStatus === 'Order Refund' && !statusCell.includes('Order Refund')) {
                showRow = false;
            } else if (selectedStatus === 'cancelled' && !statusCell.includes('Order Cancelled')) {
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

function redirectToPage(radio) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.location.href;

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'report_name';
    input.value = radio.value;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>
<script>
document.getElementById('export_table').addEventListener('click', function() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const table = document.getElementById('order2');
    const skipColumns = ["Action", "Update"];
    let csvContent = 'Customer Reports\n';

    // Include the date range if provided
    if (startDate && endDate) {
        csvContent += `Date Range: ${startDate} to ${endDate}\n`;
    }

    function escapeCSV(value) {
        if (value.includes('"') || value.includes(',') || value.includes('\n')) {
            value = `"${value.replace(/"/g, '""')}"`;
        }
        return value;
    }

    const headers = table.querySelectorAll('thead th');
    const headerIndices = [];
    headers.forEach((header, index) => {
        if (!skipColumns.includes(header.textContent.trim())) {
            headerIndices.push(index);
            csvContent += escapeCSV(header.textContent.trim()) + ',';
        }
    });
    csvContent = csvContent.slice(0, -1) + '\n';

    for (let row of table.querySelectorAll('tbody tr')) {
        let rowData = [];
        let cells = row.querySelectorAll('td');
        headerIndices.forEach(index => {
            if (cells[index]) {
                rowData.push(escapeCSV(cells[index].textContent.trim()));
            }
        });
        csvContent += rowData.join(',') + '\n';
    }

    const blob = new Blob([csvContent], {
        type: 'text/csv;charset=utf-8;'
    });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'table.csv';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});

// print table code
document.getElementById('print_table').addEventListener('click', function() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const table = document.getElementById('order2');
    const skipColumns = ["Delivery Method"];

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
    });

    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write(
        '<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>'
    );
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align:center;">AV Herbals</h1>');
    printWindow.document.write('<h3 style="text-align:center;">Customer Reports</h3>');

    // Add date range if provided
    if (startDate && endDate) {
        printWindow.document.write(`<p style="text-align:center;">Date Range: ${startDate} to ${endDate}</p>`);
    }

    printWindow.document.write(printTable.outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
});

document.getElementById('download_pdf').addEventListener('click', function() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    html2canvas(document.querySelector("#order2"), {
        useCORS: true
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF();
        const imgWidth = 190;
        const pageHeight = pdf.internal.pageSize.height;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;

        pdf.setFontSize(18);

        pdf.text('AV Herbals', pdf.internal.pageSize.width / 2, 20, null, null, 'center');


        pdf.setFontSize(14);
        pdf.text('Customer Reports', pdf.internal.pageSize.width / 2, 25, null, null, 'center');

        // Include date range if provided
        if (startDate && endDate) {
            pdf.text(`Date Range: ${startDate} to ${endDate}`, 10, 30);
        }

        pdf.setDrawColor(0, 0, 0); // Black border
        pdf.setLineWidth(0.5);
        pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height -
            10); // Border around the entire page

        let position = 45; // Adjust starting position for content


        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }
        pdf.save('table.pdf');
        window.location.reload();
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});
</script>
<?php require_once('footer.php'); ?>