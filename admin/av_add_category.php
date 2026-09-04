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
error_reporting(0);
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Category Details';
echo "<script> var sessionTitle = '$title';</script>";
?>
<?php require_once('header.php'); ?>

<?php
$error = '';
$success = '';

if (isset($_POST['submit'])) {

    if (empty($_POST['c_name'])) {
        $error_message = 'Field Required!';

    } else {
        $c_name = mysqli_real_escape_string($con, $_POST['c_name']);
        $check_cat = mysqli_query($con, "SELECT c_name FROM res_category WHERE c_name = '$c_name'");

        if (mysqli_num_rows($check_cat) > 0) {
            $error_message = 'Category already exists!';

      
        } else {
            $allowed_extensions = array('jpg', 'jpeg', 'png','svg');

            if (isset($_FILES['images']) && $_FILES['images']['error'] == 0) {
                $fname = $_FILES['images']['name'];
                $temp = $_FILES['images']['tmp_name'];
                $extension = pathinfo($fname, PATHINFO_EXTENSION);
                $fnew = uniqid() . '.' . $extension;
                $store = "Res_img/dishes/" . basename($fnew);

                if (in_array($extension, $allowed_extensions) && move_uploaded_file($temp, $store)) {
                    $store1 = $store;
                } else {
                    $error_message = 'Invalid file type or error uploading first image.';

                }
            } else {
                $error_message = 'No file uploaded for the first image.';

            }

            if (isset($_FILES['images1']) && $_FILES['images1']['error'] == 0) {
                $fname1 = $_FILES['images1']['name'];
                $temp1 = $_FILES['images1']['tmp_name'];
                $extension1 = pathinfo($fname1, PATHINFO_EXTENSION);
                $fnew1 = uniqid() . '.' . $extension1;
                $store1 = "Res_img/dishes/" . basename($fnew1);

                if (in_array($extension1, $allowed_extensions) && move_uploaded_file($temp1, $store1)) {
                } else {
                    $error_message = 'Invalid file type or error uploading second image.';
                }
            } else {
                $error_message = 'No file uploaded for the second image.';

            }

            if (empty($error)) {

                $mql = "INSERT INTO res_category(c_name, k1, k2, fpath, icon) VALUES('$c_name', '" . mysqli_real_escape_string($con, $_POST['k1']) . "', '" . mysqli_real_escape_string($con, $_POST['k3']) . "', '$store', '$store1')";
                if (mysqli_query($con, $mql)) {
                    $success_message = ' New Category Added Successfully.';
                } else {
                    $error_message = 'Error adding category to the database.';
                }
            }
        }
    }
}

?>
<style>
    
th {
    font-size: 13px !important;
}

td {
    font-size: 13px !important;
}
</style>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add Category</h1>
    </div>
    <div class="content-header-right">

    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-12">
            <?php if($error_message): ?>
            <div class="callout callout-danger">
                <p>
                    <?php echo $error_message; ?>
                </p>
            </div>
            <?php endif; ?>

            <?php if($success_message): ?>
            <div class="callout callout-success">
                <p><?php echo $success_message; ?></p>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <div class="box box-info " style="padding:25px">
                <div class="card-body">
                    <form action='add_category.php' method='post' enctype='multipart/form-data'>
                        <div class="form-body">
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Specific Category Name *</label>
                                        <input type="text" name="c_name" class="form-control"
                                            placeholder="Specific Category Name" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Specific Keyword Tag 1 *</label>
                                        <input type="text" name="k1" class="form-control"
                                            placeholder="Specific Keyword Tag 1" required>

                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Specific Keyword Tag 2 *</label>
                                        <input type="text" name="k3" class="form-control"
                                            placeholder="Specific Keyword Tag 2" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Category Image Upload* <small>(Only allowed for
                                                .jpeg,
                                                .jpg, .png, .svg)</small></label>
                                        <input type="file" name="images" accept=".jpg, .jpeg, .png, .svg"
                                            class="form-control" placeholder="images" required>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Category Icon Upload* <small>(Only allowed for
                                                .jpeg,
                                                .jpg, .png, .svg)</small></label>
                                        <input type="file" accept=".jpg, .jpeg, .png, .svg" name="images1"
                                            class="form-control" required>
                                    </div>

                                </div>
                                <!--/span-->

                            </div>
                            <div class="form-actions text-right">
                                <input type="submit" name="submit" class="btn btn-success" value="Add">
                                <input type="reset" class="btn btn-warning" value="Cancel">
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content-header">
    <div class="content-header-left">
        <h1>Category Details</h1>
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
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Category Name</th>
                                <th>Category Image</th>
                                <th>Category Icon</th>
                                <th>Date of Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $sql = "SELECT * FROM res_category order by c_id desc";
                            $query = mysqli_query($con, $sql);
                          

                            if (!mysqli_num_rows($query) > 0) {
                                echo '<td colspan="7"><center>No Categories-Data!</center></td>';
                            } else {
                                while ($rows = mysqli_fetch_array($query)) {
                                    $date = new DateTime($rows['date']);
                                    $formatted_date = $date->format('d-m-Y');
                                    echo '<tr>
                                                <td>' . $rows['c_id'] . '</td>
                                                <td>' . $rows['c_name'] . '</td>
                                                <td>
                                              
                                                 <img src="' . $rows['fpath'] . '" class="img-responsive radius" style="max-height:70px;max-width:80px;" />
                                                   </td>
                                                    <td>
                                              
                                                 <img src="' . $rows['icon'] . '" class="img-responsive radius" style="max-height:70px;max-width:80px;" />
                                                   </td>
                                                <td>' . $formatted_date . '</td>

                                                <td>
                                                 <a href="update_category.php?cat_upd=' . $rows['c_id'] . '" class="btn btn-info btn-xs btn-flat btn-addon   m-b-10 m-l-5">
                                                     Edit</a>
                                                <a href="#"
                                                data-href="category-delete.php?cat_del='.$rows['c_id'] .'"
                                                data-toggle="modal" data-target="#confirm-delete"
                                                class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                <i class="fa fa-trash-o" style="font-size:16px"></i></a>

                                                 
                                                  </td>
                                             </tr>';
                                             }
                                            }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this category?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<script>
document.getElementById('download_pdf').addEventListener('click', function () {
    const { jsPDF } = window.jspdf; // Load jsPDF
    const doc = new jsPDF(); // Create a new jsPDF instance

    const buttons = document.querySelectorAll('.content-header-right button');

    // Function to hide buttons and their text
    function hideButtons() {
        buttons.forEach(button => {
            button.setAttribute('data-original-text', button.innerText); // Store the original text
            button.innerText = ''; // Clear the button text
        });
    }

    // Function to show buttons and restore their text
    function showButtons() {
        buttons.forEach(button => {
            button.style.visibility = 'visible'; // Restore visibility
            button.innerText = button.getAttribute('data-original-text'); // Restore the original text
        });
    }

    // Hide the buttons before generating the PDF
    hideButtons();

    // Temporarily disable pagination
    const table = $('#example1').DataTable();
    const currentPageLength = table.page.len(); // Store the current page length
    const currentPage = table.page(); // Store the current page index

    // Set the page length to a large number to show all records
    table.page.len(-1).draw(); // Disable pagination

    // Extract table headers
    const headers = [];
    document.querySelectorAll('#example1 thead th').forEach(th => {
        headers.push(th.innerText); // Collect the text of each header
    });

    // Extract all table data (rows)
    const rows = [];
    const allRows = document.querySelectorAll('#example1 tbody tr'); // Get all rows in the tbody
    allRows.forEach(tr => {
        const rowData = [];
        tr.querySelectorAll('td').forEach(td => {
            // Check if the cell contains an anchor tag
            const anchor = td.querySelector('a');
            if (!anchor) {
                // If there's no anchor, add the plain text
                rowData.push(td.innerText);
            } 
            // If it contains an anchor, we skip adding it to the rowData
        });
        rows.push(rowData); // Add row data to the rows array
    });

    // Add the extracted table data to the PDF
    doc.autoTable({
        head: [headers],  
        body: rows,       
        theme: 'grid',    // Use a grid theme for better structure
    });

    // Save the generated PDF with a name
    doc.save('customer_details.pdf'); // Save the file as 'customer_details.pdf'

    // Restore pagination settings
    table.page.len(currentPageLength).draw(); // Restore the original page length
    table.page(currentPage).draw(); // Restore the original page

    // Show the buttons again after generating the PDF
    showButtons();
});
</script>