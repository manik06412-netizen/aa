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
<!-- end -->
<section class="content-header">
    <div class="content-header-left">
        <h1>Inventory</h1>
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
       'Stock Inventory' => 'product_inventory.php',
       'Sales Inventory' => 'sales_inventory.php',
   ];
   
   
   $months = [
       ["name" => "January", "number" => "01"],
       ["name" => "February", "number" => "02"],
       ["name" => "March", "number" => "03"],
       ["name" => "April", "number" => "04"],
       ["name" => "May", "number" => "05"],
       ["name" => "June", "number" => "06"],
       ["name" => "July", "number" => "07"],
       ["name" => "August", "number" => "08"],
       ["name" => "September", "number" => "09"],
       ["name" => "October", "number" => "10"],
       ["name" => "November", "number" => "11"],
       ["name" => "December", "number" => "12"]
   ];

   function Products_measure($con, $pro, $price) {
    $stmt = $con->prepare("SELECT dish_name, qn, wg, img FROM price INNER JOIN dishes ON dishes.rs_id = price.pcode WHERE  pcode = ? AND id = ?");
    
    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    $stmt->bind_param("si", $pro, $price);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        return $result->fetch_assoc();
    } else {
        die("Query failed: " . $con->error);
    }
}

   
   ?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body ">
                    <div class="row">
                        <div class="col-2 col-lg-2">
                            <div class="card">
                                <h5 class="text-primary"><b>Stock Inventory</b></h5>
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
                                            <label for="startDate">Month</label>
                                            <select name="product_name" id="month" class="form-control">
                                                <option value="" selected>Select Month</option>
                                                <?php foreach ($months as $month) { ?>
                                                <option value="<?= $month['number']; ?>"><?=$month['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="endDate">Year</label>
                                            <select id="yearSelect" class="form-control" required>
                                                <option value="" selected>Select Year</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="orderStatus">Product Name</label>
                                            <select class="form-control" id="product_id">
                                                <option value="" selected>Select product Name</option>
                                                <?php
                                       $get_pro_id = mysqli_query($con, "SELECT rs_id, dish_name FROM dishes ORDER BY d_id
                                       DESC");
                                       while ($rows = mysqli_fetch_array($get_pro_id)) {
                                       ?>
                                                <option value="<?= htmlspecialchars($rows['rs_id']); ?>">
                                                    <?= htmlspecialchars($rows['dish_name']); ?>
                                                </option>
                                                <?php } ?>
                                            </select>
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
                                                     <th style="width:12%; text-align: left;">Date</th>
                                                     <th style="width:10%">Product Logo</th>
                                                     <th style="width:30%; text-align: left;">Product name</th>
                                                     <th style="width:20%; text-align: left;">Measurement</th>
                                                     <th style="width:14%">Opening Stock</th>
                                                     <th style="width:14%">Closing Stock</th>
                                                 </tr>
                                             </thead>
                                             <tbody id="table_is_append">
                                                  <?php 
                                         $query="SELECT 
                                             prd_id, 
                                             price_id, 
                                             mnt_inv, 
                                             SUM(open_stk) AS tot_open_stk, 
                                             SUM(close_stk) AS tot_close_stk
                                         FROM 
                                             stock_invent 
                                         WHERE 
                                             (open_stk IS NOT NULL OR close_stk IS NOT NULL)
                                         GROUP BY 
                                             price_id, 
                                             prd_id, 
                                             mnt_inv
                                         ORDER BY 
                                             mnt_inv DESC;
                                         ";
                                         
                                         $sql = mysqli_query($con, $query);
                                         
                                         if (!$sql) {
                                             echo "<tr><td colspan='6' style='text-align:center;'>Error: " . mysqli_error($con) . "</td></tr>";
                                         } elseif (mysqli_num_rows($sql) > 0) {
                                             while ($orders = mysqli_fetch_array($sql)) { 
                                              $prod_id = trim($orders['prd_id'] ?? '');
                                              $price_of =  trim($orders['price_id'] ?? '');
                                              $rows_ = Products_measure($con, $prod_id , $price_of) ?? [];
                                              $img_src = !empty($rows_['img']) ? htmlspecialchars($rows_['img']) : 'Res_img/no_image.png';
                                              if (strpos($img_src, 'img/') === 0) {
                                                  $img_src = '../' . $img_src;
                                              }
                                              $dish_title = !empty($rows_['dish_name']) ? htmlspecialchars($rows_['dish_name']) : ('Product #' . htmlspecialchars($prod_id));
                                              $measure_text = (!empty($rows_['qn']) ? htmlspecialchars($rows_['qn']) : '') . (!empty($rows_['wg']) ? (' - ' . htmlspecialchars($rows_['wg'])) : '');
                                             ?>
                                                  <tr>
                                                      <td style="vertical-align: middle; text-align: left; font-weight:600; color:#475569;"><?= htmlspecialchars($orders['mnt_inv'] ?? ''); ?></td>
                                                      <td style="vertical-align: middle;"><img src="<?= $img_src; ?>" style="width:46px; height: 46px; object-fit:cover; border-radius:8px; border: 1px solid #e2e8f0;" onerror="this.onerror=null; this.src='Res_img/no_image.png';" alt=""></td>
                                                      <td style="vertical-align: middle; text-align: left;">
                                                          <b style="color:#0f172a; font-size:14px;"><?= $dish_title; ?></b><br>
                                                          <small class="text-muted" style="font-weight:600;">#<?= htmlspecialchars($prod_id); ?></small>
                                                      </td>
                                                      <td style="vertical-align: middle; text-align: left; font-weight:600; color:#1e293b;">
                                                          <?= $measure_text ?: 'Default'; ?>
                                                      </td>
                                                      <td style="vertical-align: middle;">
                                                          <span class="badge-count" style="background:#eff6ff !important; color:#2563eb !important; border-color:#bfdbfe !important; border:1px solid; padding:4px 10px; font-weight:700; border-radius:6px; font-size:12px; display:inline-block; min-width:40px; text-align:center;"><?= htmlspecialchars($orders['tot_open_stk'] ?? 0); ?></span>
                                                      </td>
                                                      <td style="vertical-align: middle;">
                                                          <span class="badge-count" style="background:#ecfdf5 !important; color:#059669 !important; border-color:#a7f3d0 !important; border:1px solid; padding:4px 10px; font-weight:700; border-radius:6px; font-size:12px; display:inline-block; min-width:40px; text-align:center;"><?= htmlspecialchars($orders['tot_close_stk'] ?? 0); ?></span>
                                                      </td>
                                                  </tr>
                                                  <?php
                                         }
                                         } else {
                                         echo "<tr><td colspan='6' style='text-align:center;'>No inventory data available</td></tr>";
                                         }
                                         ?>
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
<div id="loading_spinner" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); z-index:99999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:20px 30px; border-radius:8px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.2);">
        <i class="fa fa-spinner fa-spin fa-3x" style="color:#007bff;"></i>
        <p style="margin-top:10px; color:#444; font-weight:600;">Filtering Inventory...</p>
    </div>
</div>
<?php require_once('footer.php'); ?>
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

    const currentYear = new Date().getFullYear();
    const yearSelect = document.getElementById('yearSelect');

    if (yearSelect) {
        for (let year = currentYear; year >= 2020; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }
    }

    if ($.fn.select2) {
        $('#month').select2({ placeholder: 'Select Month', allowClear: true, width: '100%' });
        $('#yearSelect').select2({ placeholder: 'Select Year', allowClear: true, width: '100%' });
        $('#product_id').select2({ placeholder: 'Select Product', allowClear: true, width: '100%' });
    }
});

function filterByDateRange() {
    const data = new FormData();
    const monthEl = document.getElementById('month');
    const yearEl = document.getElementById('yearSelect');
    const prodEl = document.getElementById('product_id');

    data.append('month', monthEl ? monthEl.value : '');
    data.append('yearSelect', yearEl ? yearEl.value : '');
    data.append('product_id', prodEl ? prodEl.value : '');

    const spinner = document.getElementById("loading_spinner");
    if (spinner) spinner.style.display = 'flex';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'product_inventory_filter.php', true);
    xhr.onload = function() {
        if (spinner) spinner.style.display = 'none';
        try {
            const result = JSON.parse(this.responseText);
            if (result.status === 1) {
                document.getElementById('table_is_append').innerHTML = result.response;
            } else {
                document.getElementById('table_is_append').innerHTML =
                    "<tr><td colspan='6' style='text-align:center;'>No data available</td></tr>";
            }
        } catch(e) {
            console.error("Filter error:", e, this.responseText);
            document.getElementById('table_is_append').innerHTML =
                "<tr><td colspan='6' style='text-align:center;'>Error loading filtered data</td></tr>";
        }
    };
    xhr.onerror = function() {
        if (spinner) spinner.style.display = 'none';
        alert('Network error while filtering');
    };
    xhr.send(data);
}

function redirectToPage(radio) {
    if (radio && radio.value) {
        window.location.href = radio.value;
    }
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
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});
</script>
<?php require_once('footer.php'); ?>