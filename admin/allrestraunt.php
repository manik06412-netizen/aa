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
        <h1>All Stores / Restaurants</h1>
    </div>
    <div class="content-header-right">
        <a href="add_restraunt.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New Restaurant</a>
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
                                <th width="80">Image</th>
                                <th>Category</th>
                                <th>Store Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Hours</th>
                                <th>Days</th>
                                <th>Address</th>
                                <th width="90">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $sql = "SELECT r.*, c.c_name 
                                    FROM restaurant r 
                                    LEFT JOIN res_category c ON r.c_id = c.c_id 
                                    ORDER BY r.rs_id DESC";
                            $query = mysqli_query($con, $sql);
                            if ($query && mysqli_num_rows($query) > 0) {
                                while ($rows = mysqli_fetch_array($query)) {
                                    $i++;
                                    $img_src = !empty($rows['image']) ? "Res_img/" . $rows['image'] : "Res_img/no_image.png";
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($img_src); ?>" 
                                                 alt="Store" 
                                                 style="width: 55px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;"
                                                 onerror="this.onerror=null; this.src='Res_img/no_image.png';">
                                        </td>
                                        <td><span class="label label-info"><?php echo htmlspecialchars($rows['c_name'] ?? 'General'); ?></span></td>
                                        <td><b><?php echo htmlspecialchars($rows['title']); ?></b></td>
                                        <td><?php echo htmlspecialchars($rows['email']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['phone']); ?></td>
                                        <td><small><?php echo htmlspecialchars($rows['o_hr'] . ' - ' . $rows['c_hr']); ?></small></td>
                                        <td><small><?php echo htmlspecialchars($rows['o_days']); ?></small></td>
                                        <td><small><?php echo htmlspecialchars($rows['address']); ?></small></td>
                                        <td>
                                            <a href="update_restraunt.php?res_upd=<?php echo $rows['rs_id']; ?>" class="btn btn-primary btn-xs" title="Edit Store">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-xs" data-href="delete_stores.php?res_del=<?php echo $rows['rs_id']; ?>" data-toggle="modal" data-target="#confirm-delete" title="Delete Store">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No Stores Found!</td></tr>';
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
                <p>Are you sure you want to delete this store / restaurant?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>