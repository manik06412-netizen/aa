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
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Feedback Details';
echo "<script>var sessionTitle = '$title';</script>";
require_once('header.php'); ?>

<section class="content-header">
    <div class="content-header-left">
        <h1>FeedBack</h1>
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
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM fee order by id desc ");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
            	foreach ($result as $row) {
                    $date = new DateTime($row['dat']);
                    $formatted_date = $date->format('d-m-Y');
            		$i++;
            		?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['msg']; ?></td>
                                <td><?php echo $formatted_date; ?></td>

                                <?php
            	}
            	?>
                        </tbody>
                    </table>
                </div>
            </div>


</section>


<!-- <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this Subscriber?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div> -->

<script>
document.getElementById('download_pdf').addEventListener('click', function() {
    html2canvas(document.querySelector("#example1"), { useCORS: true }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF();
        const imgWidth = 190;
        const pageHeight = pdf.internal.pageSize.height;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;

        // Set text color to black
        pdf.setTextColor(0, 0, 0); // RGB for black

        // Add title and center it
        pdf.setFontSize(18);
        pdf.text('AV Herbals', pdf.internal.pageSize.width / 2, 20, null, null, 'center');

        // Optionally add date range if needed
        const startDate = document.getElementById('startDate') ? document.getElementById('startDate').value : '';
        const endDate = document.getElementById('endDate') ? document.getElementById('endDate').value : '';
        if (startDate && endDate) {
            pdf.text(`Date Range: ${startDate} to ${endDate}`, pdf.internal.pageSize.width / 2, 30, null, null, 'center');
        }

        // Draw the main content
        pdf.addImage(imgData, 'PNG', 10, 45, imgWidth, imgHeight);
        heightLeft -= (pageHeight - 45);

        while (heightLeft >= 0) {
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        // Trigger the PDF download
        pdf.save('table.pdf');
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});

</script>
<?php require_once('footer.php'); ?>