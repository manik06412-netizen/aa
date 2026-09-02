<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

$error_message = '';

if(isset($_POST['submit'])) {
    if(empty($_POST['c_name'])) {
        echo "<script>alert('Field Required!');window.location.href='add_measurements.php';</script>";
    } else {
        $q_code = mysqli_real_escape_string($con, $_POST['q_code']);
        $c_name = mysqli_real_escape_string($con, $_POST['c_name']);
        $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

        $check_cat = mysqli_query($con, "SELECT bname FROM btype WHERE (bname = '$c_name' OR q_code = '$q_code') AND category_id = '$category_id'");

        if (mysqli_num_rows($check_cat) > 0) {
            $error_message .= 'Measurements already exist in this category!<br>';
        } else {
            $mql = "INSERT INTO btype(q_code, bname, category_id) VALUES('$q_code', '$c_name', '$category_id')";
            if (mysqli_query($con, $mql)) {
                $_SESSION['flash_success'] = 'New Measurement Added Successfully!';
                header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } else {
                $error_message .= 'Error adding new type: ' . mysqli_error($con) . '<br>';
            }
        }
    }
}

// Delete functionality
if(isset($_GET['cat_del'])) {
    $del = (int)$_GET['cat_del'];
    mysqli_query($con, "DELETE FROM btype WHERE id = $del");
    $_SESSION['flash_success'] = 'Measurement deleted successfully!';
    header('Location: add_measurements.php'); exit;
}
?>
<?php require_once('header.php'); ?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add Measurement's</h1>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info ">
                <div class="card-body">
                    <?php if($error_message): ?>
                    <div class="callout callout-danger">
                        <p><?php echo $error_message; ?></p>
                    </div>
                    <?php endif; ?>

                    <?php $success_message = $_SESSION['flash_success'] ?? ''; unset($_SESSION['flash_success']); ?>
                    <?php if($success_message): ?>
                    <div class="callout callout-success">
                        <p><?php echo $success_message; ?></p>
                    </div>
                    <?php endif; ?>
                    <form action='' method='post' enctype='multipart/form-data'>
                        <div class="form-body">
                            <hr>
                            <div class="container">
                                <div class="row d-flex justify-content-center" style="display:flex; justify-content:center">
                                    <div class="col-md-8">
                                        <div class="row" style="margin-bottom:15px;">
                                            <div class="col-md-3">
                                                <label for="category_id">Category:</label>
                                            </div>
                                            <div class="col-md-4">
                                                <select name="category_id" id="category_id" class="form-control" required>
                                                    <option value="">Select Category</option>
                                                    <?php
                                                    $cats = mysqli_query($con, "SELECT c_id, c_name FROM res_category ORDER BY c_name ASC");
                                                    if ($cats) {
                                                        while ($cat = mysqli_fetch_assoc($cats)) {
                                                            echo '<option value="' . $cat['c_id'] . '">' . htmlspecialchars($cat['c_name']) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom:15px;">
                                            <div class="col-md-3">
                                                <label for="q_code">Quantity:</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="q_code" name="q_code" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="c_name">Measurement Type:</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="c_name" name="c_name" required>
                                                <div class="text-right" style="margin-top:10px;">
                                                    <input type="submit" name="submit" class="btn btn-success btn-small" value="Add" style="margin-bottom:10px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <div class="content-header-left">
            <h1>View Measurement's</h1>
        </div>
        <div class="content-header-right">
            <button class="btn btn-primary btn-xs" id="export_table">CSV</button>
	        <button class="btn btn-primary btn-xs" id="print_table">Print</button>
            <button class="btn btn-primary btn-xs" id="download_pdf">PDF</button>
        </div>
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered text-center table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Measurement Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT b.*, c.c_name as cat_name FROM btype b LEFT JOIN res_category c ON b.category_id = c.c_id ORDER BY b.id DESC";
                            $query = mysqli_query($con, $sql);
                            
                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="4"><center>No Measurements Data!</center></td></tr>';
                            } else {		
                                while ($rows = mysqli_fetch_array($query)) {
                                    echo '<tr>
                                        <td>' . ($rows['cat_name'] ? htmlspecialchars($rows['cat_name']) : 'N/A') . '</td>
                                        <td>' . htmlspecialchars($rows['q_code']) . '</td>
                                        <td>' . htmlspecialchars($rows['bname']) . '</td>
                                        <td><a href="#" data-href="?cat_del='.$rows['id'].'" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-trash-o"></i></a></td>
                                    </tr>';
                                }	
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
                Are you sure want to delete this Measurement?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>

<?php require_once('footer.php'); ?>