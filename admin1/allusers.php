<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

require_once('header.php');
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>All Registered Users</h1>
    </div>
    <div class="content-header-right">
        <a href="add_users.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New User</a>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 15px;">
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="10">#</th>
                                <th width="180">Name</th>
                                <th width="180">Email</th>
                                <th width="140">Phone / Mobile</th>
                                <th width="140">Created Date</th>
                                <th width="100">Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $sql = "SELECT * FROM user WHERE fname != 'Guest' ORDER BY id DESC";
                            $query = mysqli_query($con, $sql);
                            if ($query && mysqli_num_rows($query) > 0) {
                                while ($rows = mysqli_fetch_array($query)) {
                                    $i++;
                                    $status_badge = ($rows['status'] == 1) 
                                        ? '<span class="label label-success">Active</span>' 
                                        : '<span class="label label-danger">Inactive</span>';
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><b><?php echo htmlspecialchars($rows['fname'] . ' ' . ($rows['lname'] ?? '')); ?></b></td>
                                        <td><?php echo htmlspecialchars($rows['email']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['mobile']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['date'] ?? 'N/A'); ?></td>
                                        <td><?php echo $status_badge; ?></td>
                                        <td>
                                            <a href="customer-change-status.php?id=<?php echo $rows['id']; ?>" class="btn btn-warning btn-xs" title="Toggle Status">
                                                <i class="fa fa-refresh"></i> Status
                                            </a>
                                            <a href="#" class="btn btn-danger btn-xs" data-href="customer-delete.php?id=<?php echo $rows['id']; ?>" data-toggle="modal" data-target="#confirm-delete" title="Delete User">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">No Users Found!</td></tr>';
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
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>