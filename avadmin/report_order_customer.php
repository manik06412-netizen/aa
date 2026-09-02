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
        <h1>Customer Order's Reports</h1>
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
                                <h5 class="text-primary"><b>Customer Reports</b></h5>
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



                               

                                    <div class="box-body table-responsive">
                                        <table id="order2" class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>

                                                    <th style="width:15%">Customer Id</th>
                                                    <th style="width:15%">Name/Email</th>

                                                    <th style="width:10%">Orders</th>
                                                    <th style="width:10%" class="view">View</th>
                                                </tr>
                                            </thead>
                                            <!-- <td style="text-align: left;"><?= $orderCount; ?></td> -->
                                            <tbody>

                                                <?php 

$query = "
SELECT user.user_id, user.fname, COUNT(DISTINCT final.order_id) AS order_count
FROM user
LEFT JOIN final ON user.user_id = final.user_id
WHERE user.fname != 'Guest'  -- Exclude users with fname = 'Guest'
GROUP BY user.user_id
ORDER BY user.user_id;
";



$sql = mysqli_query($con, $query);

while ($orders = mysqli_fetch_array($sql)) { 
    $userId = $orders['user_id'];
    $userName = $orders['fname'];
    $orderCount = $orders['order_count'];
?>


                                                <tr>

                                                    <td style="text-align: left;">#<?=$orders['user_id']; ?></td>
                                                    <td style="text-align: left;">
                                                        <b><?php echo $orders['fname']; ?></b>
                                                    </td>
                                                    <td style="text-align: left;"><?=$orderCount; ?></td>

                                                    <td class="view"><span class='btn btn-info btn-xs' data-toggle='modal'
                                                            data-target='#confirm-delete'
                                                            onclick="SHOW_THE_MODEL('<?= $orders['user_id']; ?>')">View</span>
                                                    </td>

                                                    <?php
            }
            ?>

                                                </tr>

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


<div class="modal fade " id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="width: 70% !important;">
        <form id="orders_status">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Customer Reports</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="user_id_of">
                    <div class="row">
                        <div class="col-md-12" id="append_results_right">
                            <section class="content-header">
                                <div class="content-header-left">
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
                                <div class="col-md-2">
    <label for="orderStatus">Status</label>
    <select class="form-control" id="orderStatus">
        <option value="">All</option>
        <option value="0">Order Placed</option>
        <option value="1">Order Processed</option>
        <option value="2">Order Packing</option>
        <option value="3">Order Delivered</option>
        <option value="4">Order Declined</option>
        <option value="5">Order Cancelled</option>
        <option value="6">Order Refund</option>
    </select>
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

function SHOW_THE_MODEL(user_id) {

    let data = new FormData();
    let append_left_side = document.getElementById("append_left_side");
    data.append('user_id', user_id);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'report_customer_filter.php', true);

    xhr.onload = () => {
        if (xhr.status === 200) {
            let result = JSON.parse(xhr.responseText);
            if (result.status === 1) {
                append_left_side.innerHTML = result.response;
                document.getElementById('user_id_of').value = user_id;
            } else {
                alert('Error: ' + (result.message || 'An unknown error occurred.'));
            }
        } else {
            alert('Request failed. Status: ' + xhr.status);
        }
    };
    xhr.onerror = () => {
        alert('Network error. Please try again.');
    };

    xhr.send(data);
}

function filterByDateRange() {
    const data = new FormData();
    data.append('user_id', document.getElementById('user_id_of').value); // Updated line
    data.append('status', document.getElementById('orderStatus').value); 
    data.append('startDate', document.getElementById('startDate').value);
    data.append('endDate', document.getElementById('endDate').value);

    const appendLeftSide = document.getElementById("append_left_side");

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'report_customer_filter.php', true);

    xhr.onload = () => {
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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>


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

document.getElementById('download_pdf').addEventListener('click', function () {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    const invoiceHeader = document.querySelector('th.view'); // The header of the "Download Invoice" column
    const invoiceCells = document.querySelectorAll('td.view'); // The cells containing the "Download Invoice" button
    const downloadButtons = document.querySelectorAll('td.view a'); // The buttons in the "Download Invoice" column

    // Hide invoice header and cells
    if (invoiceHeader) invoiceHeader.style.display = 'none';
    invoiceCells.forEach(cell => cell.style.display = 'none'); // Hides the "Download Invoice" cell
    downloadButtons.forEach(button => button.style.display = 'none'); // Hides the button inside the cell

    html2canvas(document.querySelector("#order2"), {
        useCORS: true,
        ignoreElements: (element) => {
            // Define any elements to ignore
            return ignoreSelectors.includes(element.className);
        }
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF();
        const imgWidth = 190; // Set the image width
        const pageHeight = pdf.internal.pageSize.height; // Get the page height
        const imgHeight = (canvas.height * imgWidth) / canvas.width; // Calculate the image height
        let heightLeft = imgHeight;

        // Add title to the PDF
        pdf.setFontSize(18);
        pdf.text('AV Herbals', pdf.internal.pageSize.width / 2, 20, null, null, 'center');

        pdf.setFontSize(14);
        pdf.text('Customer Reports', pdf.internal.pageSize.width / 2, 25, null, null, 'center');

        // Include date range if provided
        if (startDate && endDate) {
            pdf.text(`Date Range: ${startDate} to ${endDate}`, 10, 30);
        }

        // Draw border around the page
        pdf.setDrawColor(0, 0, 0); // Black border
        pdf.setLineWidth(0.5);
        pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height - 10); // Border around the entire page

        let position = 45; // Adjust starting position for content

        // Add the first image
        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        heightLeft -= (pageHeight - position); // Update height left for the next page

        // Add additional pages if necessary
        while (heightLeft >= 0) {
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 10, 0, imgWidth, imgHeight); // Adjust position as needed
            heightLeft -= pageHeight;
        }

        // Save the PDF
        pdf.save('table.pdf');
window.location.reload();
        // Restore visibility of hidden elements
        if (invoiceHeader) invoiceHeader.style.display = '';
        invoiceCells.forEach(cell => cell.style.display = ''); // Show "Download Invoice" cell
        downloadButtons.forEach(button => button.style.display = ''); // Show button inside the cell
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});


</script>
<?php require_once('footer.php'); ?>