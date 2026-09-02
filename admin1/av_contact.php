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
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Contact Details';
echo "<script>var sessionTitle = '$title';</script>";
require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Contacts</h1>
	</div>
	<div class="content-header-right"> 
    <button class="btn btn-primary btn-xs" id="export_table">CSV</button>
	<button class="btn btn-primary btn-xs" id="print_table">Print</button>
    <button class="btn btn-primary btn-xs" id="download_pdf">PDF</button>
	</div>
</section>

<style>
    /* Add this CSS to ensure table data is in dark color */
table, th, td {
    color: #000000; /* Black color */
}
</style>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-hover ">
			<thead>
			    <tr>
                    <th style="display: none;">Id</th>
			        <th>UserId</th>
			        <th>Name</th>
			        <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Message</th>
                    <th>Date</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM comment order by id desc ");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
            	foreach ($result as $row) {
                    $date = new DateTime($row['date']);
                    $formatted_date = $date->format('d-m-Y');
            		
            		?>
					<tr>
                    <td style="display: none;"><?php echo $row['id']; ?></td>
	                    <td><?php echo $row['userid']; ?></td>
	                    <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['mobile']; ?></td>
                        <td><?php echo $row['comment']; ?></td>
                        <td><?php echo $formatted_date; ?></td>
	                   
            		<?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div>
  

</section>
<script>
$('#example1').DataTable({
    "order": [],
    "columnDefs": [{
        "orderable": false,
        "targets": "_all"
    }]
});
</script>

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
    const lastColumn = document.querySelectorAll("td:last-child, th:last-child");
    const table = document.querySelector("#example1");
    const originalClass = table.className;

    // Hide the last column
    lastColumn.forEach(cell => {
        cell.style.display = 'none'; // Hides the last column cells completely
    });

    // Remove the table class
    table.className = '';

    // Set border and spacing styles for PDF generation
   
    table.style.borderSpacing = '0.5em'; // Add space between cells
    table.querySelectorAll('th, td').forEach(cell => {
        cell.style.border = '0.1px solid black'; // Set border for cells
        cell.style.padding = '0.5em'; // Add padding for spacing within cells
    });

    html2canvas(table, { backgroundColor: null, useCORS: true }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF();
        const imgWidth = 190;
        const pageHeight = pdf.internal.pageSize.height;
        const pageWidth = pdf.internal.pageSize.width;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let currentPage = 1;
        const totalPages = Math.ceil(imgHeight / pageHeight);

        pdf.setTextColor(0, 0, 0);
        pdf.setFontSize(18);
        pdf.text('AV Herbals', pdf.internal.pageSize.width / 2, 20, null, null, 'center');
        
        const currentDateTime = new Date().toLocaleString();
        pdf.setFontSize(12);
        pdf.text(`Date: ${currentDateTime}`, 10, 10); 
        pdf.setLineWidth(1); 
        pdf.rect(5, 5, pageWidth - 10, pageHeight - 10); 

        pdf.addImage(imgData, 'PNG', 10, 45, imgWidth, imgHeight);
        heightLeft -= (pageHeight - 45);

        pdf.setFontSize(10);
        pdf.text(`Page ${currentPage} of ${totalPages}`, pdf.internal.pageSize.width / 2, pageHeight - 10, null, null, 'center');

        while (heightLeft >= 0) {
            currentPage++;
            pdf.addPage();
            pdf.setLineWidth(1);
            pdf.rect(5, 5, pageWidth - 10, pageHeight - 10); 

            pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            pdf.text(`Page ${currentPage} of ${totalPages}`, pdf.internal.pageSize.width / 2, pageHeight - 10, null, null, 'center');
        }

        // Save the PDF
        pdf.save('table.pdf');

        // Restore the last column visibility after the PDF is generated
        lastColumn.forEach(cell => {
            cell.style.display = ''; // Restore visibility
        });

        // Restore original table styles
        table.className = originalClass;
        table.style.borderCollapse = ''; // Reset border collapse
        table.style.borderSpacing = ''; // Reset border spacing
        table.querySelectorAll('th, td').forEach(cell => {
            cell.style.border = ''; // Reset border for cells
            cell.style.padding = ''; // Reset padding for cells
        });
        
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});
</script>




<?php require_once('footer.php'); ?>