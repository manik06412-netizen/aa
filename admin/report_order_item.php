<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
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
<?php 
function NUMBER_OF_PRODUCTS($con, $orders_lists,$price_id){
    if (!empty($price_id)) {
        $price_check = "AND price_id = '$price_id'";
    }else{ $price_check =''; }
    $select = mysqli_query($con,"SELECT sum(qty) as qt_list FROM final WHERE product_id = '$orders_lists' $price_check");
    $row = mysqli_fetch_array($select);
    return $row['qt_list'] ?? 0 ;
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Order's Items Reports</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table"> CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print </button>
        <button class="btn btn-primary btn-xs" id="download_pdf"> PDF</button>
        <a href="reports_list.php" class="btn btn-primary btn-xs new_btn" style="background-color:#FF851B; border-color:#FF851B; color:white; text-decoration:none;"> Back</a>
    </div>
</section>
<?php 
$customers_category = [
    'Order by Customer' => 'report_order_customer.php',
    'Order by Item' => 'report_order_item.php',
    'Order Fullfillment' => 'report_order_fullfillment.php',
    'Order Placed' => 'report_order_placed.php',
    'Order Packing' => 'report_order_packing.php',
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
                                <div class="  ml-2">
                                    <div class="row align-items-center">
                                        <div class="col-md-1" style="margin-left:10px;">
                                            <h5 class="mt-6" style="margin-top:30px;"><b>Filters:</b></h5>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="startDate">From</label>
                                            <input type="date" class="form-control" id="startDate" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="endDate">To</label>
                                            <input type="date" class="form-control" id="endDate" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-primary btn-xs" style="margin-top:20px; height:34px; border-radius:6px; font-weight:600; padding:0 20px; background:#0070F3 !important; border-color:#0070F3 !important; color:#fff;"
                                                onclick="filterByDateRange()">Apply</button>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="box-body table-responsive">
                                        <table id="order2" class="table table-bordered table-hover table-striped text-center">
                                             <thead>
                                                 <tr>
                                                     <th style="width:25%; text-align: left;">Product Name</th>
                                                     <th style="width:10%">No. of Items Sold</th>
                                                     <th style="width:65%">Measurement & Qty</th>
                                                 </tr>
                                             </thead>
                                             <tbody id="orderTableBody">
                                                 <?php 
                                                         $query = "SELECT * FROM final GROUP BY product_id";
                                                         $sql = mysqli_query($con, $query);
 
                                                         while ($orders = mysqli_fetch_array($sql)) { 
                                                             $orders_list = $orders['product_id'];
                                                             $price_ids=$orders['price_id'];
                                                             $tot_qn=$orders['qty'];
                                                             $number_of = NUMBER_OF_PRODUCTS($con, $orders_list, '');
                                                             $query_of = mysqli_query($con, "SELECT * FROM dishes WHERE rs_id ='$orders_list'");
                                                             $dish_is = mysqli_fetch_array($query_of);
                                                         ?>
                                                 <tr>
                                                     <td style="vertical-align: middle; text-align: left;">
                                                         <img src="./<?= $dish_is['img']; ?>" width="60" height="50" style="object-fit:cover; border-radius:6px; border:1px solid #e2e8f0; margin-bottom: 5px;" onerror="this.onerror=null; this.src='Res_img/no_image.png';" alt="">
                                                         <b style="display:block; color:#0f172a; font-size:14px;"><?= $dish_is['dish_name']; ?></b>
                                                     </td>
                                                     <td style="vertical-align: middle;"><span class="badge-count" style="background:#f1f5f9 !important; color:#475569 !important; border-color:#cbd5e1 !important; border:1px solid; padding:4px 10px; font-weight:700; border-radius:6px; font-size:12.5px; display:inline-block; min-width:40px; text-align:center;"><?= $number_of; ?></span></td>
                                                     <td style="vertical-align: middle;">
                                                         <table class="table text-center table-bordered table-striped"
                                                             style="background-color: #f8fafc !important; border-radius: 8px; overflow: hidden; margin-bottom:0;">
                                                             <thead>
                                                                 <tr style="background:#f1f5f9;">
                                                                     <th style="font-weight:600; font-size:12px;">Measurement</th>
                                                                     <th style="font-weight:600; font-size:12px;">Current Status</th>
                                                                     <th style="font-weight:600; font-size:12px;">No. of Items Sold</th>
                                                                     <th style="font-weight:600; font-size:12px; width:60px;">Action</th>
                                                                 </tr>
                                                             </thead>
                                                             <tbody>
                                                             <?php  
                                                                 $measurement_s = mysqli_query($con, "SELECT * FROM price WHERE pcode='$orders_list'");
                                                                 while($price_of = mysqli_fetch_array($measurement_s)){
                                                                     $current_status_of = mysqli_query($con, "SELECT stk_status FROM prd_stock WHERE pd_code='$orders_list' AND price_id = {$price_of['id']} ORDER BY id DESC LIMIT 1");
                                                                     $current_sta = mysqli_fetch_array($current_status_of);
                                                                     $stk_val = $current_sta['stk_status'] ?? '0';
                                                                 ?>
                                                             <tr>
                                                                 <td style="vertical-align: middle; font-weight:600; color:#1e293b;"><?= $price_of['qn'].'-'.$price_of['wg']; ?></td>
                                                                 <td style="vertical-align: middle;">
                                                                     <span class="status-badge <?= ($stk_val == '1' || strtolower($stk_val) == 'active' ? 'status-active' : 'status-inactive') ?>"><?= $stk_val == '1' ? 'Active' : ($stk_val == '0' ? 'Inactive' : htmlspecialchars($stk_val)); ?></span>
                                                                 </td>
                                                                 <td style="vertical-align: middle;">
                                                                     <span class="badge-count" style="background:#eff6ff !important; color:#2563eb !important; border-color:#bfdbfe !important; border:1px solid; padding:3px 8px; font-weight:700; border-radius:6px; font-size:11.5px; display:inline-block; min-width:35px; text-align:center;"><?= NUMBER_OF_PRODUCTS($con, $orders_list, $price_of['id']); ?></span>
                                                                 </td>
                                                                 <td style="vertical-align: middle;">
                                                                     <a href="#"
                                                                         onclick="OPEN_MODEL_FORM('<?=$orders_list ?>','<?=$price_of['id']; ?>','<?= $dish_is['dish_name']; ?>','<?= $price_of['qn'].'-'.$price_of['wg']; ?>','<?=$dish_is['img']; ?>')"
                                                                         data-toggle='modal'
                                                                         data-target='#confirm-delete'
                                                                         class="btn btn-info btn-xs" style="height:26px; border-radius:6px; font-weight:600; padding:0 12px; margin:0 !important; cursor:pointer; background:#0070F3 !important; border-color:#0070F3 !important; color:#fff; display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">View</a>
                                                                 </td>
                                                             </tr>
                                                             <?php } ?>
                                                             </tbody>
                                                         </table>
                                                     </td>
                                                 </tr>
                                                 <?php } ?>
                                             </tbody>
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

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="orders_status">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Order Details</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-lg-12" style="margin-top:20px;">
                            <div class="display_part_1" id="display_part_1"></div>
                        </div>
                        <div class="row" style="padding-bottom:20px;margin:7px;">
                            <div class="col-lg-1 text-center" style="margin-top:30px;">
                                <b style="font-weight:bold;font-size:20px">Filters:</b>
                            </div>
                            <div class="col-md-3">
                                <label for="startDate">From</label>
                                <input type="date" class="form-control" id="startDates" name="startDates" required>
                            </div>
                            <div class="col-md-3">
                                <label for="endDate">To</label>
                                <input type="date" class="form-control" id="endDates" name="endDates" required>
                            </div>
                            <input type="hidden" id="order_id">
                            <input type="hidden" id="price_id">
                            <input type="hidden" id="dish_name">
                            <input type="hidden" id="meas">
                            <input type="hidden" id="dish_img">

                            <div class="col-md-2" style="margin-top:25px;">
                                <button type="button" class="btn btn-primary mt-4"
                                    onclick="filterByDate()">Apply</button>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12" style="margin-top:30px;">
                            <div class="display_part_1" id="display_part_2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function OPEN_MODEL_FORM(product, price, dish_name, meas, img) {
    let set_1 = document.getElementById('display_part_1');
    let set_2 = document.getElementById('display_part_2');

    document.getElementById('order_id').value = product;
    document.getElementById('price_id').value = price;
    document.getElementById('dish_name').value = dish_name;
    document.getElementById('meas').value = meas;
    document.getElementById('dish_img').value = img;

    let data = new FormData();
    data.append('product', product);
    data.append('price', price);
    data.append('dish_name', dish_name);
    data.append('meas', meas);
    data.append('dish_img', img);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", 'report_order_item_details.php', true);
    xhr.onload = function() {
        let result = JSON.parse(xhr.responseText);
        if (result.status == 1) {
            set_1.innerHTML = result.response; // Full details
            set_2.innerHTML = result.response_1; // Full order records
        } else {
            alert('Error fetching data');
        }
    }
    xhr.send(data);
}

function filterByDate() {
    product = document.getElementById('order_id').value;
    price = document.getElementById('price_id').value;

    let data = new FormData();
    data.append('startDates', document.getElementById('startDates').value);
    data.append('endDates', document.getElementById('endDates').value);
    data.append('product', product);
    data.append('price', price);
    data.append('dish_name', document.getElementById('dish_name').value);
    data.append('meas', document.getElementById('meas').value);
    data.append('dish_img', document.getElementById('dish_img').value);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", 'report_order_item_details.php', true);
    xhr.onload = function() {
        let result = JSON.parse(xhr.responseText);
        if (result.status == 1) {
            let set_2 = document.getElementById('display_part_2');
            set_2.innerHTML = result.response_1; // Update filtered order records
        } else {
            alert('Error fetching data');
        }
    }
    xhr.send(data);
}
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
});

function filterByDateRange() {
    let table = document.getElementById('orderTableBody');
    let startDate = document.getElementById("startDate").value;
    let endDate = document.getElementById("endDate").value;

    let data = new FormData();
    data.append('startDate', startDate);
    data.append('endDate', endDate);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'report_item_order_filter.php', true);
    xhr.onload = () => {
        let result = JSON.parse(xhr.responseText);
        if (result.status == 1) {
            table.innerHTML = result.response;
        }
    }
    xhr.send(data);
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

document.getElementById('export_table').addEventListener('click', function() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const table = document.getElementById('order2');
    const skipColumns = ["Action", "Update"];
    let csvContent = 'Customer Reports\n';

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
    printWindow.document.write('<h1 style="text-align:center;">Karuda Computers</h1>');
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
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();
        const imgWidth = 190;
        const pageHeight = pdf.internal.pageSize.height;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;

        pdf.setFontSize(18);
        pdf.text('Karuda Computers', pdf.internal.pageSize.width / 2, 20, null, null, 'center');

        pdf.setFontSize(14);
        pdf.text('Customer Reports', pdf.internal.pageSize.width / 2, 25, null, null, 'center');

        // Include date range if provided
        if (startDate && endDate) {
            pdf.text(`Date Range: ${startDate} to ${endDate}`, 10, 30);
        }

        pdf.setDrawColor(0, 0, 0); // Black border
        pdf.setLineWidth(0.5);
        pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height - 10); // Border around the entire page

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