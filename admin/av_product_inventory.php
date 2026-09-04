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
   //error_reporting(0);
   
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
        <a href="reports_list.php" class="btn btn-xs" style="background-color:#FF851B; color:white; border:1px solid #FF851B;height:23px !important; font-size: 13px; margin-top:1px !important;"> Back</a>
    </div>
</section>
<?php 
   $customers_category = [
       'Stock Inventory' => 'product_inventory.php',
       'Sales Inventory' => 'report_subsb.php',
      
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
                                            <button class="btn btn-primary mt-4" style="margin-top:20px;"
                                                onclick="filterByDateRange()">Apply</button>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="box-body table-responsive">
                                        <table id="order2" class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width:8%">Date</th>
                                                    <th style="width:8%">Product Logo</th>
                                                    <th style="width:15%">Product name</th>
                                                    <th style="width:15%">Measurement</th>
                                                    <th style="width:15%">Opening Stock</th>
                                                    <th style="width:12%">Closing Stock </th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_is_append">
                                                <?php 
                                       include('inc/config.php');
                                       
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
                                           // Handle query error
                                           echo "<tr><td colspan='5' style='text-align:center;'>Error: " . mysqli_error($con) . "</td></tr>";
                                       } elseif (mysqli_num_rows($sql) > 0) {
                                           while ($orders = mysqli_fetch_array($sql)) { 
                                            $prod_id = trim($orders['prd_id']);
                                            $price_of =  trim($orders['price_id']);
                                            $rows_ = Products_measure($con, $prod_id , $price_of);
                              
                                           ?>
                                                <tr>
                                                    <td><?php echo $orders['mnt_inv']; ?></td>
                                                    <td><img src="<?=$rows_['img']; ?>" style="width:70px; height: 70px; " alt=""></td>
                                                    <td>
                                                        <?=  $rows_['dish_name']; ?><br>
                                                        <?php echo $orders['prd_id']; ?></td>
                                                    <td style="text-align: left;">
                                                        <?php echo $rows_['qn'] . "-" . $rows_['wg']; ?></td>
                                                    <td style="text-align: left;"><?php echo $orders['tot_open_stk']; ?>
                                                    </td>
                                                    <td style="text-align: left;">
                                                        <?php echo $orders['tot_close_stk']; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                       }
                                       } else {
                                       echo "<tr><td colspan='4' style='text-align:center;'>No data available</td></tr>";
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
<div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
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

    const currentYear = new Date().getFullYear();
    const yearSelect = document.getElementById('yearSelect');

    for (let year = currentYear; year >= 1900; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;

        yearSelect.appendChild(option);
    }

});

$(document).ready(function() {
    $('#month').select2({
        placeholder: 'Select a Month',
        allowClear: true,
        width: '100%'
    });
    $('#yearSelect').select2({
        placeholder: 'Select a Year',
        allowClear: true,
        width: '100%'
    });
    $('#product_id').select2({
        placeholder: 'Select a Product',
        allowClear: true,
        width: '100%'
    });

});


function filterByDateRange() {
    const data = new FormData();

    data.append('month', document.getElementById('month').value || '');
    data.append('yearSelect', document.getElementById('yearSelect').value || '');
    data.append('product_id', document.getElementById('product_id').value || '');

    document.getElementById("loading_spinner").style.display = 'block';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'product_inventory_filter.php', true);
    xhr.onload = function() {
        const result = JSON.parse(this.responseText);
        if (result.status === 1) {
            document.getElementById('table_is_append').innerHTML = result.response;
        } else {
            document.getElementById('table_is_append').innerHTML =
                "<tr><td colspan='5' style='text-align:center;'>No data available</td></tr>";
        }
        document.getElementById("loading_spinner").style.display = 'none';
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
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});
</script>
<?php require_once('footer.php'); ?>