<?php
error_reporting(0);
session_start();

if (isset($_POST['report_name'])) {
    $_SESSION['active_report'] = $_POST['report_name'];
    header("Location: " . $_SESSION['active_report']); // Redirect to the selected report page
    exit(); // Ensure no further processing occurs
}
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<?php require_once('header.php');
include('../controller/reuse.php');
?>
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
    /* Light green color */
}
</style>
<?php
$orderStatus = [];
$query = "SELECT DISTINCT name FROM comment ORDER BY name ASC";
$result = mysqli_query($con, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $orderStatus[] = $row['name'];
}

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

th {
    font-size: 13px !important;
}

td {
    font-size: 13px !important;
}
</style>
<section class="content-header">
    <div class="content-header-left">
        <h1>Contact Reports</h1>
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
    'Customers' => 'report_customer.php',
    'Subscribers' => 'report_subsb.php',
    'Testimonials' => 'reports_testimonial.php',
    'Product Reviews' => 'report_reviews.php',
    'Feedback' => 'report_feedback.php',
    'Contacts' => 'report_contact.php'
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
                                <div class="ml-2">
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
                                            <label for="orderStatus">User Name</label>
                                            <select class="form-control" id="orderStatus" name="orderStatus">
                                                <option value="">All Users</option>
                                                <?php foreach ($orderStatus as $user): ?>
                                                <option value="<?= htmlspecialchars($user) ?>">
                                                    <?= htmlspecialchars($user) ?></option>
                                                <?php endforeach; ?>
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
                                                    <th style="width:13%">Date</th>
                                                    <th style="width:8%">Name</th>
                                                    <th style="width:10%">Email</th>
                                                    <th style="width:8% !important;">Mobile Number</th>
                                                    <th style="width:20%">Message</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                            $query = "SELECT * FROM comment ORDER BY id DESC";
                            $sql = mysqli_query($con, $query);
                            while ($orders = mysqli_fetch_array($sql)) { 
                            ?>
                                                <tr>
                                                    <td style="text-align: left;"><?=$orders['date']; ?></td>
                                                    <td style="text-align: left;"><?=$orders['name']; ?></td>
                                                    <td style="text-align: left;"><?=$orders['email']; ?></td>
                                                    <td style="text-align: left;"><?=$orders['mobile']; ?></td>
                                                    <td style="text-align: left;"><?=$orders['comment']; ?></td>
                                                </tr>
                                                <?php
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


});
$(document).ready(function() {
    $('#orderStatus').select2({
        placeholder: 'Select a user',
        allowClear: true,
        width: '100%'
    });
});


function filterByDateRange() {
    var table = $('#order2').DataTable();
    var startDate = new Date(document.getElementById("startDate").value);
    var endDate = new Date(document.getElementById("endDate").value);
    var selectedUser = document.getElementById("orderStatus").value;
    endDate.setHours(23, 59, 59, 999);
    var rowsVisible = 0;
    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var dateCell = this.data()[0];
        var nameCell = this.data()[1];
        var orderDate = new Date(dateCell.split("-").reverse().join("-"));
        var showRow = true;
        if (orderDate < startDate || orderDate > endDate) {
            showRow = false;
        }
        if (selectedUser && nameCell !== selectedUser) {
            showRow = false;
        }
        if (showRow) {
            $(this.node()).show();
            rowsVisible++;
        } else {
            $(this.node()).hide();
        }
    });
    if (rowsVisible === 0) {
        $('#order2 tbody').append(
            '<tr class="no-data"><td colspan="5">No orders found for the selected criteria.</td></tr>'
        );
    } else {
        $('#order2 tbody .no-data').remove();
    }
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
    let csvContent = 'Contact Reports\n';

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
    printWindow.document.write('<h3 style="text-align:center;">Contact Reports</h3>');

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

        // Adjust the Y-coordinate for margin-top (change from 10 to your desired value)
        pdf.text('AV Herbals', pdf.internal.pageSize.width / 2, 20, null, null, 'center');


        pdf.setFontSize(14);
        pdf.text('Contact Reports', pdf.internal.pageSize.width / 2, 27, null, null, 'center');

        // Include date range if provided
        if (startDate && endDate) {
            pdf.text(`Date Range: ${startDate} to ${endDate}`, 10, 36);
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