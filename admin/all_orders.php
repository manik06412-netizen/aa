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
// Helper: Get total qty for an order
function Qty_of_delivery($con, $where) {
    $where = mysqli_real_escape_string($con, $where);
    $query = "SELECT SUM(qty) AS totals FROM final WHERE order_id = '$where'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['totals'] ?? 0;
}

// Helper: Get shipment status text
function Show_Shipment_delivery($con, $order_id) {
    $stmt = $con->prepare("SELECT status FROM shipment WHERE order_id = ? ORDER BY status DESC LIMIT 1");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $values = $result->fetch_assoc();
    $stmt->close();
    return $values['status'] ?? null;
}

$current_date = date('d-m-Y');
$currentDate = new DateTime();
$currentDate->modify('-30 days');
$futureDate = $currentDate->format('d-m-Y');

// Delivery stat card counts
$del_total_q = mysqli_query($con, "SELECT COUNT(DISTINCT f.order_id) AS cnt FROM final f INNER JOIN order_sts os ON f.order_id = os.order_id INNER JOIN (SELECT order_id, MAX(status) AS ms FROM order_sts GROUP BY order_id HAVING MAX(status) >= 2 AND MAX(status) <= 4) mx ON os.order_id = mx.order_id AND os.status = mx.ms WHERE f.status = '0'");
$del_total = mysqli_fetch_assoc($del_total_q)['cnt'] ?? 0;

$del_packing_q = mysqli_query($con, "SELECT COUNT(DISTINCT f.order_id) AS cnt FROM final f INNER JOIN order_sts os ON f.order_id = os.order_id INNER JOIN (SELECT order_id, MAX(status) AS ms FROM order_sts GROUP BY order_id HAVING MAX(status) = 2) mx ON os.order_id = mx.order_id AND os.status = mx.ms WHERE f.status = '0'");
$del_packing = mysqli_fetch_assoc($del_packing_q)['cnt'] ?? 0;

$del_delivered_q = mysqli_query($con, "SELECT COUNT(DISTINCT f.order_id) AS cnt FROM final f INNER JOIN order_sts os ON f.order_id = os.order_id INNER JOIN (SELECT order_id, MAX(status) AS ms FROM order_sts GROUP BY order_id HAVING MAX(status) = 3) mx ON os.order_id = mx.order_id AND os.status = mx.ms WHERE f.status = '0'");
$del_delivered = mysqli_fetch_assoc($del_delivered_q)['cnt'] ?? 0;

$del_action_q = mysqli_query($con, "SELECT COUNT(DISTINCT f.order_id) AS cnt FROM final f INNER JOIN order_sts os ON f.order_id = os.order_id INNER JOIN (SELECT order_id, MAX(status) AS ms FROM order_sts GROUP BY order_id HAVING MAX(status) = 2) mx ON os.order_id = mx.order_id AND os.status = mx.ms LEFT JOIN shipment sh ON f.order_id = sh.order_id WHERE f.status = '0' AND sh.order_id IS NULL");
$del_action = mysqli_fetch_assoc($del_action_q)['cnt'] ?? 0;
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
.av-stat-cards-row { display: flex !important; gap: 16px !important; flex-wrap: wrap !important; margin-bottom: 10px !important; }
.av-stat-card {
    flex: 1 !important; min-width: 180px !important;
    border-radius: 14px !important;
    padding: 18px 20px !important;
    color: #fff !important;
    display: flex !important; align-items: center !important; gap: 14px !important;
    box-shadow: 0 4px 18px rgba(0,0,0,0.18) !important;
    transition: transform 0.22s, box-shadow 0.22s !important;
    position: relative !important; overflow: hidden !important;
    border: none !important;
}
.av-stat-card:hover { transform: translateY(-4px) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.22) !important; }
.av-stat-card .av-stat-icon { font-size: 36px !important; opacity: 0.88 !important; flex-shrink: 0 !important; color: #fff !important; }
.av-stat-card .av-stat-info { flex: 1 !important; }
.av-stat-card .av-stat-value { font-size: 28px !important; font-weight: 700 !important; line-height: 1.1 !important; color: #fff !important; }
.av-stat-card .av-stat-label { font-size: 12px !important; color: rgba(255,255,255,0.9) !important; margin-top: 3px !important; font-weight: 600 !important; letter-spacing: 0.3px !important; }
.av-stat-card .av-stat-sub { font-size: 11px !important; color: rgba(255,255,255,0.72) !important; margin-top: 2px !important; }
.av-stat-section-label { font-size: 12px !important; font-weight: 600 !important; color: #777 !important; text-transform: uppercase !important; letter-spacing: 1px !important; margin: 16px 0 8px 2px !important; display:block !important; }
</style>
<section class="content-header">
    <div class="content-header-left">
        <h1>Delivery Orders</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table">CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print</button>
        <button class="btn btn-primary btn-xs" id="download_pdf">PDF</button>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- DELIVERY STAT CARDS -->
            <div class="av-stat-section-label"><i class="fa fa-truck"></i>&nbsp; Delivery Orders Summary</div>
            <div class="av-stat-cards-row" style="margin-bottom:20px;">
                <div class="av-stat-card" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%) !important;">
                    <div class="av-stat-icon"><i class="fa fa-truck"></i></div>
                    <div class="av-stat-info">
                        <div class="av-stat-value"><?= $del_total; ?></div>
                        <div class="av-stat-label">Total Delivery Orders</div>
                        <div class="av-stat-sub">Active in delivery pipeline</div>
                    </div>
                </div>
                <div class="av-stat-card" style="background:linear-gradient(135deg,#f7971e 0%,#ffd200 100%) !important;">
                    <div class="av-stat-icon"><i class="fa fa-archive"></i></div>
                    <div class="av-stat-info">
                        <div class="av-stat-value"><?= $del_packing; ?></div>
                        <div class="av-stat-label">Packing Orders</div>
                        <div class="av-stat-sub">Currently being packed</div>
                    </div>
                </div>
                <div class="av-stat-card" style="background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%) !important;">
                    <div class="av-stat-icon"><i class="fa fa-check-circle"></i></div>
                    <div class="av-stat-info">
                        <div class="av-stat-value"><?= $del_delivered; ?></div>
                        <div class="av-stat-label">Delivered Orders</div>
                        <div class="av-stat-sub">Successfully delivered</div>
                    </div>
                </div>
                <div class="av-stat-card" style="background:linear-gradient(135deg,#ee0979 0%,#ff6a00 100%) !important;">
                    <div class="av-stat-icon"><i class="fa fa-bolt"></i></div>
                    <div class="av-stat-info">
                        <div class="av-stat-value"><?= $del_action; ?></div>
                        <div class="av-stat-label">Action Needed</div>
                        <div class="av-stat-sub">Packing &mdash; no shipment yet</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-info" style="padding:10px;">
                <div class="box-body table-responsive">
                    <table id="example2" class="table table-bordered table-hover table-striped">
                        <div class="filter-options">
                            <button onclick="filterTable('today')">Today's Orders</button>
                            <button onclick="filterTable('last7')">Last 7 Days</button>
                            <button onclick="filterTable('last15')">Last 15 Days</button>
                            <button onclick="filterTable('last30')">Last 30 Days</button>
                        </div>
                        <thead>
                            <tr>
                                <th style="width:8%">Date</th>
                                <th style="width:15%">Order Id</th>
                                <th style="width:15%">Customer Name</th>
                                <th style="width:8%">Amount(₹)</th>
                                <th style="width:8%">Items</th>
                                <th style="width:10%">Payment Status</th>
                                <th style="width:15%">Fulfillment Status</th>
                                <th style="width:15%">Delivery Method</th>
                                <th style="width:12%">Download Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $payment_sts = '';
                            $payment_color = '';
                            $full_sts = '';
                            $full_color = '';
                            $i = 0;

                            // Show orders that are in delivery stage (status 2 = packing/dispatched, or status 3 = delivered)
                            $query = "SELECT final.*, chekout.*, order_sts.* FROM final
                                INNER JOIN chekout ON final.refid = chekout.ref_id
                                INNER JOIN order_sts ON final.order_id = order_sts.order_id
                                INNER JOIN (
                                    SELECT order_id, MAX(status) AS max_status FROM order_sts
                                    GROUP BY order_id HAVING MAX(status) >= 2 AND MAX(status) <= 4
                                ) AS max_status_table ON order_sts.order_id = max_status_table.order_id
                                    AND order_sts.status = max_status_table.max_status
                                WHERE final.status = '0'
                                GROUP BY final.order_id
                                ORDER BY final.id DESC";
                            $sql = mysqli_query($con, $query);
                            $row_count = 0;
                            if ($sql) $row_count = mysqli_num_rows($sql);

                            if (!$sql || $row_count == 0) {
                                echo '<tr><td colspan="9" class="text-center" style="padding:30px;"><i class="fa fa-inbox" style="font-size:40px;color:#ccc;"></i><br><b style="color:#aaa;">No Delivery Orders Found</b></td></tr>';
                            } else {
                                while ($orders = mysqli_fetch_array($sql)) {
                                    $order_id = $orders['order_id'];
                                    $user_id = $orders['user_id'];

                                    // Payment status
                                    if ($orders['status'] == 4) {
                                        $payment_sts = 'Failed';
                                        $payment_color = 'btn-danger';
                                    } else {
                                        $payment_sts = 'Paid';
                                        $payment_color = 'btn-success';
                                    }

                                    // Fulfillment status
                                    $status_messages = [
                                        0 => ['Order Placed',    'btn-primary'],
                                        1 => ['Order Processed', 'btn-success'],
                                        2 => ['Order Packing',   'btn-success'],
                                        3 => ['Order Delivered', 'btn-secondary'],
                                        4 => ['Order Declined',  'btn-danger'],
                                        5 => ['Order Cancelled', 'btn-danger'],
                                        6 => ['Order Refund',    'btn-warning'],
                                    ];
                                    $full_status = (int)$orders['status'];
                                    $full_sts = $status_messages[$full_status][0] ?? 'Order Placed';
                                    $full_color = $status_messages[$full_status][1] ?? 'btn-primary';
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><?= $orders['order_date'] ?? $orders['sts_date']; ?></td>
                                        <td>#<?= $orders['order_id']; ?></td>
                                        <?php
                                        $user_query = "SELECT * FROM address WHERE userid = '$user_id'";
                                        $user_result = mysqli_query($con, $user_query);
                                        if ($user_row = mysqli_fetch_array($user_result)) {
                                            echo '<td>' . htmlspecialchars($user_row['fname']) . '</td>';
                                        } else {
                                            echo '<td>User not found</td>';
                                        }
                                        ?>
                                        <td><?= $orders['final_amt']; ?></td>
                                        <td><?= Qty_of_delivery($con, $orders['order_id']); ?></td>
                                        <td><button class='btn btn-xs <?= $payment_color; ?>'><?= $payment_sts; ?></button></td>
                                        <td style="display:flex;gap:8px;">
                                            <span style="flex-grow:4;" class="btn btn-xs w-100 <?= $full_color; ?>"><?= $full_sts; ?></span>
                                            <span class="btn btn-info btn-infos btn-xs w-50" onclick="Call_value_1('<?= htmlspecialchars($order_id); ?>')"
                                                data-toggle="modal" data-target="#confirm-delete">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            // Shipment delivery method
                                            $query_shipment = "SELECT status FROM shipment WHERE order_id = '$order_id'";
                                            $result_shipment = mysqli_query($con, $query_shipment);
                                            if ($result_shipment && mysqli_num_rows($result_shipment) > 0) {
                                                $row_shipment = mysqli_fetch_assoc($result_shipment);
                                                $ship_status = (int)$row_shipment['status'];
                                            } else {
                                                $ship_status = null;
                                            }
                                            $shipment_labels = [
                                                0 => 'Package delivered to source hub',
                                                1 => 'Package in transit',
                                                2 => 'Package reached destination',
                                                3 => 'Package delivered to destination hub',
                                                4 => 'Package out for delivery',
                                                5 => 'Package delivered',
                                            ];
                                            $ship_value = ($ship_status !== null) ? ($shipment_labels[$ship_status] ?? 'Unknown status') : 'Not yet processed';
                                            ?>
                                            <span style="margin-bottom:6px;"><?= htmlspecialchars($ship_value) ?></span>
                                            <?php if ($full_status == 2) { ?>
                                                <a href="shipment.php?order_id=<?= $order_id; ?>"
                                                    class="btn btn-xs btn-warning btn-warnings text-center m-b-10 m-l-5 w-50">&nbsp;&nbsp;Action&nbsp;&nbsp;</a>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="download_invoice.php?order_id=<?= $order_id; ?>" class="btn btn-success btn-xs">
                                                <i class="fa fa-download" aria-hidden="true"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>

<!-- Order Status Update Modal -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="orders_status">
            <div class="modal-content" style="max-width: 1000px !important;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Order Status</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="display:flex; justify-content:center;margin-top:10px" id="append_results_right"></div>
                        <div class="col-12" style="margin-left:20px;margin-bottom:20px;">
                            <h6 class="modal-title"><b>Order Status History &amp; Update: </b></h6>
                        </div>
                        <div class="col-md-12" id="append_left_side"></div>
                        <div class="col-12 col-md-12" id="mgs_update" style="padding: 0 20px;">
                            <label for="message">Message / Note:</label>
                            <textarea name="Message" class="form-control" cols="30" rows="3" id="message" placeholder="Enter status note or reason..."></textarea>
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
    function Call_value_1(orderId) {
        document.getElementById("order_id").value = orderId;
        document.getElementById("append_results_right").innerHTML = '<div style="text-align:center;padding:15px;"><i class="fa fa-spinner fa-spin fa-2x" style="color:#007bff;"></i><p style="margin-top:8px;color:#777;">Loading order details...</p></div>';
        document.getElementById("append_left_side").innerHTML = '';

        let data = new FormData();
        data.append("id_name", orderId);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", 'my_orders_list.php', true);
        xhr.onload = function() {
            try {
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
                    document.getElementById("append_results_right").innerHTML = '<div class="alert alert-danger">Error loading order.</div>';
                }
            } catch(e) {
                console.error("JSON parse error:", e, this.responseText);
                document.getElementById("append_results_right").innerHTML = '<div class="alert alert-danger">Failed to parse server response.</div>';
            }
        };
        xhr.onerror = function() {
            document.getElementById("append_results_right").innerHTML = '<div class="alert alert-danger">Network error.</div>';
        };
        xhr.send(data);
    }

    document.getElementById("orders_status").addEventListener("submit", function(e) {
        e.preventDefault();
        let loading_spinner = document.getElementById("loading_spinner");
        if (loading_spinner) loading_spinner.style.display = "block";
        let data = new FormData(this);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", 'update_orders.php', true);
        xhr.onload = function() {
            if (loading_spinner) loading_spinner.style.display = "none";
            try {
                let result = JSON.parse(this.responseText);
                if (result.status == 1) {
                    location.reload();
                } else {
                    alert('Error updating order status');
                }
            } catch(e) {
                console.error("Submit error:", e, this.responseText);
                alert('Order status updated!');
                location.reload();
            }
        };
        xhr.onerror = function() {
            if (loading_spinner) loading_spinner.style.display = "none";
            alert('Network error while submitting.');
        };
        xhr.send(data);
    });


    function filterTable(period) {
        var table = $('#example2').DataTable();
        table.page.len(-1).draw();
        var rows = table.rows().nodes();
        var currentDate = new Date();
        rows.each(function(row) {
            var dateCell = $(row).find('td:first-child').text();
            var orderDate = new Date(dateCell.split("-").reverse().join("-"));
            var difference = (currentDate - orderDate) / (1000 * 3600 * 24);
            if (period === 'today' && difference < 1 ||
                period === 'last7' && difference <= 7 ||
                period === 'last15' && difference <= 15 ||
                period === 'last30' && difference <= 30) {
                $(row).show();
            } else {
                $(row).hide();
            }
        });
    }
</script>
<script>
$(document).ready(function() {
    $('#example2').DataTable();
    let currentFilter = '';

    document.getElementById('export_table').addEventListener('click', function() {
        const table = document.getElementById('example2');
        const skipColumns = ["Download Invoice"];
        let csvContent = '';
        function escapeCSV(value) {
            if (value.includes('"') || value.includes(',') || value.includes('\n')) {
                value = `"${value.replace(/"/g, '""')}"`;
            }
            return value;
        }
        const now = new Date();
        const formattedDate = now.toLocaleDateString('en-GB');
        const formattedTime = now.toLocaleTimeString('en-GB');
        const headers = table.querySelectorAll('thead th');
        const headerIndices = [];
        csvContent += `Date: ${formattedDate} ${formattedTime}\n`;
        csvContent += 'Karuda Computers\n';
        csvContent += 'Delivery Orders Report\n\n';
        headers.forEach((header, index) => {
            if (!skipColumns.includes(header.textContent.trim())) {
                headerIndices.push(index);
                csvContent += escapeCSV(header.textContent.trim()) + ',';
            }
        });
        csvContent = csvContent.slice(0, -1) + '\n';
        const tableInstance = $('#example2').DataTable();
        tableInstance.page.len(-1).draw();
        table.querySelectorAll('tbody tr').forEach(row => {
            let rowData = [];
            let cells = row.querySelectorAll('td');
            headerIndices.forEach(index => {
                if (cells[index]) rowData.push(escapeCSV(cells[index].textContent.trim()));
            });
            csvContent += rowData.join(',') + '\n';
        });
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'delivery_orders.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        tableInstance.page.len(10).draw();
    });

    document.getElementById('print_table').addEventListener('click', function() {
        const table = document.getElementById('example2');
        const tableInstance = $('#example2').DataTable();
        tableInstance.page.len(-1).draw();
        const printTable = table.cloneNode(true);
        const now = new Date();
        const tableHTML = printTable.outerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Delivery Orders</title>');
        printWindow.document.write('<style>table{border-collapse:collapse;width:100%}th,td{border:1px solid black;padding:4px;text-align:left;font-size:10px}th{background-color:#f2f2f2}@media print{@page{size:A4 landscape;margin:0}body{margin:0}}</style>');
        printWindow.document.write(`<h1 style="text-align:center;">Karuda Computers - Delivery Orders</h1>`);
        printWindow.document.write('</head><body>');
        printWindow.document.write(tableHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
        };
        tableInstance.page.len(10).draw();
    });

    document.getElementById('download_pdf').addEventListener('click', function() {
        $('#example2').DataTable().page.len(-1).draw();
        setTimeout(function() { generatePDF(); }, 800);
    });

    function generatePDF() {
        const table = document.querySelector('#example2');
        html2canvas(table, { useCORS: true, scale: 3 }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const imgWidth = 190;
            const pageHeight = pdf.internal.pageSize.height;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            pdf.setFontSize(18);
            pdf.text('Karuda Computers - Delivery Orders', pdf.internal.pageSize.width / 2, 15, null, null, 'center');
            pdf.addImage(imgData, 'PNG', 10, 25, imgWidth, imgHeight);
            pdf.save('delivery_orders.pdf');
            $('#example2').DataTable().page.len(10).draw();
        });
    }
});
</script>