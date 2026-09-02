<?php
session_start();
error_reporting(0);
?>
<?php require_once('header.php'); 
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Latest Information';
echo "<script>var sessionTitle = '$title';</script>";
?>

<?php
$error = '';
$success = '';

if (isset($_POST['submit'])) {
   
$info=$_POST['info'];
           

                $mql = "INSERT INTO topbar(content) VALUES('$info')";
                if (mysqli_query($con, $mql)) {
                    $success_message = ' New Information Added Successfully.';
                } else {
                    $error_message = 'Error adding Information to the database.';
                }
            }
        
 
?>


<section class="content-header">
    <div class="content-header-left">
        <h1>Add Latest Information</h1>
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
                    <form action='add_info.php' method='post' enctype='multipart/form-data'>
                        <div class="form-body">
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Latest Information*</label>
                                        <input type="text" name="info" class="form-control"
                                            placeholder="Specific Category Name" required>
                                    </div>
                                </div>
                                </div>
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
        <h1>Latest Informations </h1>
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
                                <th>Info</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $sql = "SELECT * FROM topbar order by id desc";
                            $query = mysqli_query($con, $sql);

                            if (!mysqli_num_rows($query) > 0) {
                                echo '<td colspan="7"><center>No Additional Informatiion!</center></td>';
                            } else {
                                while ($rows = mysqli_fetch_array($query)) {
                                    echo '<tr>
                                                <td>' . $rows['id'] . '</td>
                                                <td>' . $rows['content'] . '</td>
                                               

                                                <td>
                                                 <a href="update_info.php?cat_upd=' . $rows['id'] . '" class="btn btn-info btn-xs btn-flat btn-addon   m-b-10 m-l-5">
                                                     Edit</a>
                                                <a href="#"
                                                data-href="info-delete.php?cat_del='.$rows['id'] .'"
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
                Are you sure want to delete this Information?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>