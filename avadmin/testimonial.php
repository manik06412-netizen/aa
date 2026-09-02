<?php 
session_start();
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Testimonial Details';
echo "<script>var sessionTitle = '$title';</script>";
require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Testimonials</h1>
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
			        <th>Designation</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM testi order by id desc ");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['design']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td><?php echo $row['date']; ?></td>
	                    <td><a href="#" class="btn btn-danger btn-xs" data-href="testimonial_delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a></td>
	                </tr>
            		<?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div>
  

</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('download_pdf').addEventListener('click', function() {
    const lastColumn = document.querySelectorAll("td:last-child, th:last-child");
    const table = document.querySelector("#example1");
    const originalClass = table.className;
    const currentDate = new Date().toLocaleDateString();
    const currentTime = new Date().toLocaleTimeString();
    // Hide the last column
    lastColumn.forEach(cell => {
        cell.style.display = 'none'; // Hides the last column cells completely
    });

    // Remove the table class
    table.className = '';

    // Set border styles for PDF generation
    table.style.borderCollapse = 'collapse';
    table.querySelectorAll('th, td').forEach(cell => {
        cell.style.border = '0.5px solid black'; // Set border for cells
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
        pdf.text( currentDate + ' -' + currentTime, pdf.internal.pageSize.width / 2, 30, null, null, 'center');
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
        table.querySelectorAll('th, td').forEach(cell => {
            cell.style.border = ''; // Reset border for cells
        });
        
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});
</script>


<?php require_once('footer.php'); ?>