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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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

.report_label {
    display: block;
    padding: 10px;
    width: 100%;
    border: 0.1px solid rgb(216, 216, 216);
    cursor: pointer;
}

.report_label.selected {
    background-color: #d4edda;
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

.table-borderless {
    border: 0px !important;
}

#append_left_side {
    max-height: 400px;
    overflow-y: auto;
}

#orderStatus {
    height: 40px !important;
}
</style>
<style>
.bg-prim {
    background-color: rgb(146, 187, 211) !important;
}
</style>
<?php

function MEASUREMENT_LOOP($con, $product, $stock_status)
{
    $measurement = [];
    $stmt = $con->prepare("SELECT price.qn, price.wg, price.id,price.pcode,prd_stock.total  FROM price 
            INNER JOIN prd_stock ON price.pcode = prd_stock.pd_code  WHERE price.pcode = ? GROUP BY price.id ");
    $stmt->bind_param("s", $product);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $select = mysqli_query($con, "SELECT * FROM prd_stock WHERE pd_code = '{$row['pcode']}' AND price_id = {$row['id']}  ORDER BY id DESC LIMIT 1");
        $count = mysqli_fetch_array($select);
        if ($count['total'] > 0) {
            if ($count['stk_status'] == $stock_status) {

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

function MEASUREMENT_LOOP_1($con, $product, $stock_status)
{
    $measurement = [];
    $stmt = $con->prepare("SELECT price.qn, price.wg, price.id,price.pcode,prd_stock.total  FROM price 
            INNER JOIN prd_stock ON price.pcode = prd_stock.pd_code  WHERE price.pcode = ? GROUP BY price.id ");
    $stmt->bind_param("s", $product);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $select = mysqli_query($con, "SELECT * FROM prd_stock WHERE pd_code = '{$row['pcode']}' AND price_id = {$row['id']}  ORDER BY id DESC LIMIT 1");
        $count = mysqli_fetch_array($select);
        if ($count['total'] <= 0) {
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

?>
<!-- end -->
<section class="content-header">
    <div class="content-header-left">
        <h1 id="page_title">Products Reports</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table_excel">CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table_1">Print</button>
        <button class="btn btn-primary btn-xs" id="download_pdf_of_in">PDF</button>
        <a href="reports_list.php" class="btn btn-xs" style="background-color:#FF851B; color:white; border:1px solid #FF851B;height:23px !important; font-size: 13px; margin-top:1px !important;"> Back</a>
        <!-- <a class="btn btn-primary btn-xs" href="export.php">csv</a> -->
    </div>
</section>


<section class="content">

    <div class="row">

        <div class="col-md-12">
            <div class="box box-info">
                <form id="filter_added">
                    <div class="row" style="margin:20px; margin-bottom:40px">
                        <div class="col-lg-2">
                            <h3 style="font-weight:bold;">Filters:</h3>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Product Name:</label>
                            <select name="product_name" id="orderStatus" class="form-control">
                                <option value="" selected>select Product</option>
                                <?php $pro_name = mysqli_query($con, "SELECT * FROM dishes ORDER BY dish_name ASC");
                                while ($list_out = mysqli_fetch_array($pro_name)) {
                                    ?>
                                <option value="<?= $list_out['dish_name'] ?>"><?= $list_out['dish_name'] ?></option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Current Status:</label>
                            <select name="current_status" id="status_ofs" class="form-control">
                                <option value="" selected>select status</option>
                                <option value="1">Instock</option>
                                <option value="2">Out of stock</option>
                                <option value="3">Stop selling</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="">From date:</label>
                            <input type="date" name="startDate" id="st_date" class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <label for="">To date</label>
                            <input type="date" name="endDate" id="et_date" class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-info" style="margin-top:20px" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="box-body  table-responsive" id="update_details_of">
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">Date of adding</th>
                                <th style="width:10%">Products Name</th>
                                <th style="width:15%">Instock</th>
                                <th style="width:10%">Out of Stock</th>
                                <th style="width:10%">Stop Selling</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $category_list = mysqli_query($con, "SELECT  * FROM dishes INNER JOIN price ON dishes.rs_id = price.pcode WHERE dishes.status = '1' GROUP BY price.pcode ORDER BY d_id DESC");

                            while ($results_of = mysqli_fetch_array($category_list)) {

                                ?>
                            <tr class="tr">
                                <td class="td"><?= $results_of['date_of_adding']; ?></td>
                                <td class="td">
                                    <img src="./<?= $results_of['img']; ?>"
                                        onerror="this.onerror=null; this.src='Res_img/no_image.png';"
                                        style="width:40px; height:40px;" alt=""><br>
                                    <span class="text-bold "> <?= $results_of['dish_name'] ?></span><br>
                                    <span class="text-bold "> #<?= $results_of['rs_id'] ?></span>
                                </td>

                                <?php
                                    $image = $results_of['img'];
                                    $product_name = $results_of['dish_name'];
                                    $measurement_list = MEASUREMENT_LOOP($con, $results_of['rs_id'], 'Instock');
                                    $measurement_list_1 = MEASUREMENT_LOOP($con, $results_of['rs_id'], 'Currently Unavailable');
                                    $measurement_list_2 = MEASUREMENT_LOOP_1($con, $results_of['rs_id'], 'Currently Unavailable');

                                    ?>
                                <!--  -->
                                <td>

                                    <table class="table table-borderless "
                                        style="background-color:transparent !important;">
                                        <?php
                                            if (!empty($measurement_list)) {
                                                $measurement_s_2 = '';
                                                foreach ($measurement_list as $measurement) {
                                                    $current_status = 'Instock';
                                                    $measurementDetails = $measurement['quantity'] . ' ' . $measurement['weight'] . ' - ' . $measurement['stock'];

                                                    echo "<tr class='table_tr'>
                                                    <td><span class='span_value'>{$measurementDetails}</span></td>
                                                    <td>
                                                        <span class='btn btn-info btn-xs' data-toggle='modal' data-target='#confirm-delete' 
                                                              onclick='SHOW_THE_MODEL({$measurement['product_code']}, {$measurement['price_id']}, \"{$measurementDetails}\", \"{$image}\", \"{$product_name}\",\"{$current_status}\")'>
                                                            View
                                                        </span>
                                                    </td>
                                                  </tr>";
                                                }
                                            } else {
                                                echo '<tr><span class="span_value">----</span></tr>';
                                            }
                                            ?>
                                    </table>
                                </td>
                                <td>
                                    <table class="table table-borderless "
                                        style="background-color:transparent !important;">
                                        <?php
                                            if (!empty($measurement_list_2)) {
                                                $measurement_s_1 = '';
                                                foreach ($measurement_list_2 as $measurement) {
                                                    $measurement_s_1 = $measurement['quantity'] . ' ' . $measurement['weight'] . ' - ' . $measurement['stock'];
                                                    $current_status_2 = 'Currently Unavailable';
                                                    echo "<tr class='table_tr'>
                                                    <td><span class='span_value'>{$measurement_s_1}</span></td>
                                                    <td>
                                                        <span class='btn btn-info btn-xs' data-toggle='modal' data-target='#confirm-delete' 
                                                              onclick='SHOW_THE_MODEL({$measurement['product_code']}, {$measurement['price_id']}, \"{$measurement_s_1}\", \"{$image}\", \"{$product_name}\",\"{$current_status_2}\")'>
                                                            View
                                                        </span>
                                                    </td>
                                                  </tr>";
                                                }

                                            } else {
                                                echo '<tr><td><span class="span_value">----</span></td></tr>';
                                            }
                                            ?>
                                    </table>
                                </td>

                                <td>
                                    <table class="table table-borderless "
                                        style="background-color:transparent !important;">
                                        <?php
                                            if (!empty($measurement_list_1)) {
                                                $measurement_s = '';
                                                foreach ($measurement_list_1 as $measurement) {
                                                    $measurement_s = $measurement['quantity'] . ' ' . $measurement['weight'] . ' - ' . $measurement['stock'];
                                                    $current_status_1 = 'Inactive';
                                                    echo "<tr class='table_tr'>
                                                    <td><span class='span_value_2'>{$measurement_s}</span></td>
                                                    <td>
                                                        <span class='btn btn-info btn-xs' data-toggle='modal' data-target='#confirm-delete' 
                                                              onclick='SHOW_THE_MODEL({$measurement['product_code']}, {$measurement['price_id']}, \"{$measurement_s}\", \"{$image}\", \"{$product_name}\",\"{$current_status_1}\")'>
                                                            View
                                                        </span>
                                                    </td>
                                                  </tr>";
                                                }

                                            } else {
                                                echo '<tr><span class="span_value_2">----</span></tr>';
                                            }
                                            ?>
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
</section>

<!-- model start -->

<div class="modal fade " id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="width: 70% !important;">
        <form id="orders_status">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Product Report</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="product_id_of"><input id="price_id_of" type="hidden">

                    <div class="row">
                        <div class="col-md-12" id="append_top"></div>
                        <div class="col-md-12" id="append_results_right">
                            <section class="content-header">
                                <div class="content-header-left">
                                </div>
                                <div class="content-header-right">
                                    <button type="button" class="btn btn-primary btn-xs"
                                        id="export_csv_btn">CSV</button>
                                    <button type="button" class="btn btn-primary btn-xs"
                                        id="print_modal_table">Print</button>
                                    <button type="button" class="btn btn-primary btn-xs"
                                        id="download_pdf_table">PDF</button>
                                </div>
                            </section>
                            <div class="row" style="padding-bottom:20px;margin:7px; ">
                                <div class="col-lg-1 text-center" style="margin-top:30px;">
                                    <b style="font-weight:bold;font-size:20px">Filters:</b>
                                </div>
                                <div class="col-md-3">
                                    <label for="startDate">From</label>
                                    <input type="date" class="form-control" id="startDate" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="endDate">To</label>
                                    <input type="date" class="form-control" id="endDate" required>
                                </div>
                                <div class="col-md-2" style="margin-top:25px;">
                                    <button type="button" class="btn btn-primary mt-4"
                                        onclick="filterByDateRange()">Apply</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="append_left_side">

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="order_id" id="order_id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>

    </div>
</div>
<div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
<!-- model end  -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
document.getElementById('export_table_excel').addEventListener('click', () => {
    let orderStatus = document.getElementById('orderStatus').value ?? '';
    let status_ofs = document.getElementById('status_ofs').value ?? '';
    let startDate = document.getElementById('st_date').value ?? '';
    let endDate = document.getElementById('et_date').value ?? '';

    window.location.href =
        `export.php?startDate=${startDate}&endDate=${endDate}&product_name=${orderStatus}&current_status=${status_ofs}`;
});

document.getElementById('download_pdf_of_in').addEventListener('click', (e) => {
    let orderStatus = document.getElementById('orderStatus').value ?? '';
    let status_ofs = document.getElementById('status_ofs').value ?? '';
    let startDate = document.getElementById('st_date').value ?? '';
    let endDate = document.getElementById('et_date').value ?? '';

    window.location.href =
        `export_pdf.php?startDate=${startDate}&endDate=${endDate}&product_name=${orderStatus}&current_status=${status_ofs}`;
})


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

$(document).ready(function() {
    $('#orderStatus').select2({
        placeholder: 'Select a dish',
        allowClear: true,
        width: '100%'
    });
});



function SET_DETAILS_OF(product_id, price_id, measurement, image, product_name, status) {
    let append = document.getElementById("append_top");
    append.innerHTML = `<div class="row">
    <div class="col-2 col-lg-2"><img src="${image}" style="width:100%;height:100px"></div>
     <div class="col-6 col-lg-6">
     <p style="display:block;font-size:14px;"><b>Product ID: </b> #${product_id}</p>
     <p style="display:block;font-size:14px;" ><b>Product Name:</b> ${product_name}</p>
     <p style="display:block;font-size:14px;" ><b>measurement & Qty :</b> ${measurement}</p>
     <p style="display:block;font-size:14px;" ><b>Current Status :</b> ${status}</p>
     </div>

    </div>`;

}

function SHOW_THE_MODEL(product_id, price_id, measurement, image, product_name, status) {

    SET_DETAILS_OF(product_id, price_id, measurement, image, product_name, status);

    let data = new FormData();
    let append_left_side = document.getElementById("append_left_side");
    data.append('product_id', product_id);
    data.append('price_id', price_id);
    document.getElementById("loading_spinner").style.display = 'block';

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'report_products_filter.php', true);

    xhr.onload = () => {
        if (xhr.status === 200) {
            let result = JSON.parse(xhr.responseText);
            if (result.status === 1) {
                append_left_side.innerHTML = result.response;
                document.getElementById('product_id_of').value = product_id;
                document.getElementById('price_id_of').value = price_id;
            } else {
                alert('Error: ' + (result.message || 'An unknown error occurred.'));
            }
        } else {
            alert('Request failed. Status: ' + xhr.status);
        }
        document.getElementById("loading_spinner").style.display = 'none';
    };
    xhr.onerror = () => {
        alert('Network error. Please try again.');
        document.getElementById("loading_spinner").style.display = 'none';
    };

    xhr.send(data);
}

document.getElementById("filter_added").addEventListener('submit', function(e) {
    e.preventDefault();

    const data = new FormData(this);
    const xhr = new XMLHttpRequest();
    let update_details_of = document.getElementById('update_details_of');
    document.getElementById("loading_spinner").style.display = 'block';
    xhr.open('POST', 'overall_report_products_filter.php', true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            const result = JSON.parse(xhr.responseText);
            if (result.status === 1) {
                update_details_of.innerHTML = result.response;
            } else {
                console.error("Error: ", result.message);
            }
            document.getElementById("loading_spinner").style.display = 'none';
        } else {
            console.error("Request failed with status: ", xhr.status);
        }
    };

    xhr.onerror = () => {
        console.error("Request error");
    };

    xhr.send(data);
});




function filterByDateRange() {
    const data = new FormData();
    data.append('product_id', document.getElementById('product_id_of').value);
    data.append('price_id', document.getElementById('price_id_of').value);
    data.append('startDate', document.getElementById('startDate').value);
    data.append('endDate', document.getElementById('endDate').value);

    const appendLeftSide = document.getElementById("append_left_side");
    document.getElementById("loading_spinner").style.display = 'block';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'report_products_filter.php', true);

    xhr.onload = () => {
        document.getElementById("loading_spinner").style.display = 'none';
        if (xhr.status === 200) {
            const result = JSON.parse(xhr.responseText);
            if (result.status === 1) {
                appendLeftSide.innerHTML = result.response;
            } else {
                alert('Error: ' + (result.message || 'An unknown error occurred.'));
            }
        } else {
            alert('Request failed. Status: ' + xhr.status);
        }
    };
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
</script>




<!--  -->
<script>
document.getElementById('print_table_1').addEventListener('click', function() {
    const table = document.getElementById('example1');
    const skipColumns = ["Action", "Update"]; // Columns to skip

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
                cells[index].style.display = 'none'; // Hide specified columns
            }
        });
    }

    const printTable = table.cloneNode(true); // Clone the table for printing
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
    const tableHTML = printTable.outerHTML;

    // Get the dynamic title
    const dynamicTitle = document.getElementById('page_title').textContent;

    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write(
        '<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 4px; text-align: left; font-size: 10px; } th { background-color: #f2f2f2; }</style>'
    );
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center;">AV Herbals - ' + dynamicTitle +
        '</h1>'); // Use dynamic title
    printWindow.document.write(tableHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
});


// 

document.getElementById('download_pdf_of').addEventListener('click', function() {
    const table = document.querySelector('#example1');
    const actionButtons = document.querySelectorAll('.btnwarning, .btn-info, .btn-download-invoice');
    const currentDate = new Date().toLocaleDateString();
    const currentTime = new Date().toLocaleTimeString();

    // Get the dynamic title
    const dynamicTitle = document.getElementById('page_title').textContent;

    function hideButtons() {
        actionButtons.forEach(button => button.style.display = 'none');
    }

    // Get all rows without pagination
    const tableInstance = $('#example1').DataTable();
    const originalLength = tableInstance.page.len();
    tableInstance.page.len(-1).draw(); // Show all rows

    hideButtons();

    html2canvas(table, {
        useCORS: true,
        scale: 3
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('p', 'mm', 'a4');
        const imgWidth = 190;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let position = 45;


        pdf.setFontSize(10);
        pdf.text(currentDate + ' - ' + currentTime, pdf.internal.pageSize.width / 2, 10, null, null,
            'center');
        pdf.setFontSize(18);
        pdf.text('AV Herbals - ' + dynamicTitle, pdf.internal.pageSize.width / 2, 20, null, null,
            'center');
        pdf.setFontSize(14);
        pdf.text('Order Reports', pdf.internal.pageSize.width / 2, 25, null, null, 'center');


        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        pdf.save('table.pdf');

        actionButtons.forEach(button => button.style.display = '');
    }).catch(err => {
        console.error('Error generating PDF:', err);
        actionButtons.forEach(button => button.style.display = '');
    }).finally(() => {
        tableInstance.page.len(originalLength).draw();
    });
});
</script>

<?php require_once('footer.php'); ?>