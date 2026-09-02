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
    th{
    font-size: 13px !important;
}
td{
    font-size: 13px !important;
}
</style>

<?php
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
$selectedDish = isset($_POST['orderStatus']) ? $_POST['orderStatus'] : '';

// Fetch total reviews for each dish
$totalReviewsQuery = "SELECT dishes.dish_name, COUNT(cust_reviews.uid) as total_review_count 
                      FROM dishes 
                      LEFT JOIN cust_reviews ON cust_reviews.cid = dishes.rs_id 
                      GROUP BY dishes.rs_id";
$totalReviewsResult = mysqli_query($con, $totalReviewsQuery);

$totalReviews = [];
while ($row = mysqli_fetch_assoc($totalReviewsResult)) {
    $totalReviews[$row['dish_name']] = $row['total_review_count'];
}

// Base SQL query for filtered results
$sql = "SELECT dishes.dish_name, cust_reviews.cid, 
        COUNT(cust_reviews.uid) as review_count,
        DATE(cust_reviews.createdat) as review_date
        FROM dishes 
        JOIN cust_reviews ON cust_reviews.cid = dishes.rs_id 
        WHERE 1=1";

// Add conditions based on filters
if ($startDate != '' && $endDate != '') {
    $sql .= " AND DATE(cust_reviews.createdat) BETWEEN '$startDate' AND '$endDate'";
}
if ($selectedDish != '') {
    $sql .= " AND dishes.dish_name = '$selectedDish'";
}

// Group by dish and ensure we only get products with reviews
$sql .= " GROUP BY dishes.rs_id
          HAVING COUNT(cust_reviews.uid) > 0
          ORDER BY dishes.dish_name";

$query = mysqli_query($con, $sql);

// Fetch available dishes for the select dropdown
$statement = $pdo->prepare("SELECT dish_name FROM dishes");
$statement->execute();
$dishes = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
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
<section class="content-header">
    <div class="content-header-left">
        <h1>Product Review</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table"> CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print </button>
        <button class="btn btn-primary btn-xs" id="download_pdf"> PDF</button>
        <a href="reports_list.php" class="btn btn-xs" style="background-color:#FF851B; color:white; border:1px solid #FF851B;height:23px !important; font-size: 13px; margin-top:1px !important;"> Back</a>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body ">
                    <div class="row">
                        <div class="col-2 col-lg-2">
                            <div class="card">
                                <h5 class="text-primary"><b>Customer Reports</b></h5>
                                <?php foreach ($customers_category as $name => $page): ?>
                                    <input type="radio" name="report_name" class="category_radio" id="category<?= $name; ?>"
                                        value="<?= $page; ?>" onchange="redirectToPage(this)"
                                        <?php echo (isset($_SESSION['active_report']) && $_SESSION['active_report'] === $page) ? 'checked' : ''; ?>>
                                    <label
                                        class="report_label <?php echo (isset($_SESSION['active_report']) && $_SESSION['active_report'] === $page) ? 'selected' : ''; ?>"
                                        for="category<?= $name; ?>"><?= $name; ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-10 col-lg-10">
                            <div class="card">

                                <div class="ml-4">
                                    <form method="POST" action="">
                                        <div class="row align-items-center">
                                            <div class="col-md-1 ">
                                                <h4 style="padding-left:25px !important; margin-top:29px !important;"
                                                    class="text-center mt-4">Filters:</h4>
                                            </div>
                                            <div class="col-md-3" style="margin-left:20px;">
                                                <label for="startDate">From</label>
                                                <input type="date" class="form-control" id="startDate" name="startDate"
                                                    value="<?= htmlspecialchars($startDate); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="endDate">To</label>
                                                <input type="date" class="form-control" id="endDate" name="endDate"
                                                    value="<?= htmlspecialchars($endDate); ?>">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="orderStatus">Dish Name</label>
                                                <select class="form-control" id="orderStatus" name="orderStatus">
                                                    <option value="">All Dishes</option>
                                                    <?php foreach ($dishes as $dish): ?>
                                                        <option value="<?= htmlspecialchars($dish['dish_name']) ?>" <?= ($dish['dish_name'] == $selectedDish) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($dish['dish_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary mt-4"
                                                    style="margin-top:20px;">Apply</button>
                                            </div>
                                        </div>
                                        <hr>
                                    </form>

                                    <div class="box-body table-responsive">
                                        <table id="order2"
                                            class="table table-bordered table-hover text-center table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="display:none;">Date</th>
                                                    <th>Product Name</th>
                                                    <th>No. of Reviews</th>
                                                    <th>View Reviews</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!mysqli_num_rows($query) > 0) {
                                                    echo '<tr><td colspan="4"><center>No products with reviews!</center></td></tr>';
                                                } else {
                                                    while ($rows = mysqli_fetch_array($query)) {
                                                        $formattedDate = date('d-m-Y', strtotime($rows['review_date']));
                                                        $totalCount = isset($totalReviews[$rows['dish_name']]) ? $totalReviews[$rows['dish_name']] : 0;

                                                        // Show the review count based on filtering
                                                        $reviewCountToShow = ($startDate != '' && $endDate != '') ? $rows['review_count'] : $totalCount;
                                                ?>
                                                        <tr>
                                                            <td style="display:none;"><?= $formattedDate; ?></td>
                                                            <td><?= htmlspecialchars($rows['dish_name']); ?></td>
                                                            <td><?= $reviewCountToShow; ?></td>
                                                            <td>
                                                                <a
                                                                    href="get_report_reviews.php?id=<?= $rows['cid']; ?>&startDate=<?= htmlspecialchars($startDate); ?>&endDate=<?= htmlspecialchars($endDate); ?>">
                                                                    <button type="button" class="btn btn-md view-reviews"
                                                                        style="background-color:#FF851B; color:white; border:1px solid #FF851B;">
                                                                        View Reviews
                                                                    </button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#orderStatus').select2({
            placeholder: 'Select a dish',
            allowClear: true,
            width: '100%'
        });
    });


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

        // Add the column header text you want to skip (i.e., the column with the button "View Reviews")
        const skipColumns = ["Action", "Update", "View Reviews"]; // Add "View Reviews" or the actual column name

        // Add AV Herbals as the heading
        let csvContent = 'AV Herbals\n';

        // Add subheading for Review Reports
        csvContent += 'Review Reports\n';

        // Include the date range if provided
        if (startDate && endDate) {
            csvContent += `Date Range: ${startDate} to ${endDate}\n`;
        }

        // Helper function to handle CSV formatting (escape commas, quotes, etc.)
        function escapeCSV(value) {
            if (value.includes('"') || value.includes(',') || value.includes('\n')) {
                value = `"${value.replace(/"/g, '""')}"`;
            }
            return value;
        }

        // Get table headers and skip specific columns
        const headers = table.querySelectorAll('thead th');
        const headerIndices = [];
        headers.forEach((header, index) => {
            if (!skipColumns.includes(header.textContent.trim())) { // Skip "View Reviews" or its respective column name
                headerIndices.push(index);
                csvContent += escapeCSV(header.textContent.trim()) + ',';
            }
        });
        csvContent = csvContent.slice(0, -1) + '\n'; // Remove the last comma and add a newline

        // Loop through each row and collect data
        for (let row of table.querySelectorAll('tbody tr')) {
            let rowData = [];
            let cells = row.querySelectorAll('td');
            headerIndices.forEach(index => {
                if (cells[index]) {
                    rowData.push(escapeCSV(cells[index].textContent.trim()));
                }
            });
            csvContent += rowData.join(',') + '\n'; // Join row data with commas
        }

        // Create a blob and download the CSV file
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

   document.getElementById('print_table').addEventListener('click', function() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const table = document.getElementById('order2');
    
    // Add the column to be skipped (e.g., "View Reviews")
    const skipColumns = ["Delivery Method", "View Reviews"];

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
    printWindow.document.write('<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align:center;">AV Herbals</h1>');
    printWindow.document.write('<h3 style="text-align:center;">Review Reports</h3>');

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

        // Temporarily hide all "View Reviews" buttons and the corresponding column
        const viewReviewButtons = document.querySelectorAll('.view-reviews');
        const table = document.querySelector('#order2');
        const viewReviewColumnIndex = Array.from(document.querySelectorAll('#order2 th'))
            .findIndex(th => th.textContent.trim() === 'View Reviews');

        // Hide the "View Reviews" column if it exists
        if (viewReviewColumnIndex >= 0) {
            // Hide the "View Reviews" column header
            document.querySelectorAll(`#order2 th`)[viewReviewColumnIndex].style.display = 'none';

            // Hide each cell in the "View Reviews" column
            document.querySelectorAll(`#order2 tr`).forEach(row => {
                row.children[viewReviewColumnIndex].style.display = 'none';
            });
        }

        // Hide "View Reviews" buttons
        viewReviewButtons.forEach(button => {
            button.style.display = 'none';
        });

        // Generate the PDF
        html2canvas(table, {
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
            pdf.text('Review Reports', pdf.internal.pageSize.width / 2, 25, null, null, 'center');

            // Include date range if provided
            if (startDate && endDate) {
                pdf.text(`Date Range: ${startDate} to ${endDate}`, pdf.internal.pageSize.width / 2, 35, null, null, 'center');
            }

            // Draw the border for the whole page
            pdf.setDrawColor(0, 0, 0); // Black border
            pdf.setLineWidth(0.5);
            pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height - 10); // Border around the entire page

            let position = 45; // Adjust starting position for content

            // Add image to the PDF
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            // If content exceeds one page, add new pages
            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height - 10); // Add border for subsequent pages
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            // Save the generated PDF
            pdf.save('table.pdf');
            window.location.reload();
            // Restore the "View Reviews" buttons after PDF generation
            viewReviewButtons.forEach(button => {
                button.style.display = '';
            });

            // Restore the hidden "View Reviews" column
            if (viewReviewColumnIndex >= 0) {
                document.querySelectorAll(`#order2 th`)[viewReviewColumnIndex].style.display = '';
                document.querySelectorAll(`#order2 tr`).forEach(row => {
                    row.children[viewReviewColumnIndex].style.display = '';
                });
            }

        }).catch(err => {
            console.error('Error generating PDF:', err);

            // Restore the "View Reviews" buttons and column in case of error
            viewReviewButtons.forEach(button => {
                button.style.display = '';
            });

            if (viewReviewColumnIndex >= 0) {
                document.querySelectorAll(`#order2 th`)[viewReviewColumnIndex].style.display = '';
                document.querySelectorAll(`#order2 tr`).forEach(row => {
                    row.children[viewReviewColumnIndex].style.display = '';
                });
            }
        });
    });
</script>
<?php require_once('footer.php'); ?>