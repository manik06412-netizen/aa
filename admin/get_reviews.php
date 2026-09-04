<?php
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';

if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}

include "header.php"; 

$product_id = isset($_GET['id']) ? mysqli_real_escape_string($con, $_GET['id']) : '';

// Get Product Name
$prd_res = mysqli_query($con, "SELECT dish_name FROM dishes WHERE rs_id='$product_id' OR d_id='$product_id' LIMIT 1");
$prd_info = ($prd_res && mysqli_num_rows($prd_res) > 0) ? mysqli_fetch_assoc($prd_res) : null;
$product_title = $prd_info ? $prd_info['dish_name'] : "Product #$product_id";

$sql = "SELECT * FROM cust_reviews WHERE cid='$product_id' ORDER BY createdat DESC";
$query = mysqli_query($con, $sql);
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Reviews for: <span style="color: #2563eb;"><?php echo htmlspecialchars($product_title); ?></span></h1>
    </div>
    <div class="content-header-right">
        <a href="reviews.php" class="btn btn-sm btn-secondary" style="border-radius: 6px; padding: 6px 16px;">
            <i class="fa fa-arrow-left"></i> Back to Reviews List
        </a>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-hover table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Rating</th>
                                <th style="text-align: left; width: 35%;">Review Comment</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!$query || mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="7"><center>No reviews found for this product!</center></td></tr>';
                            } else {
                                $ik = 1;
                                while ($rows = mysqli_fetch_array($query)) {
                                    $formattedDate = !empty($rows['createdat']) ? date('d-m-Y h:i A', strtotime($rows['createdat'])) : '-';
                                    $rating = (int)($rows['uratings'] ?? ($rows['urating'] ?? 5));
                                    ?>
                                    <tr>
                                        <td><?php echo $ik++; ?></td>
                                        <td style="font-weight: 600;"><?php echo htmlspecialchars($rows['uname']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['uemail'] ?? '-'); ?></td>
                                        <td>
                                            <span style="color: #f59e0b; font-size: 15px; white-space: nowrap;">
                                                <?php
                                                for ($s = 1; $s <= 5; $s++) {
                                                    if ($s <= $rating) {
                                                        echo '<i class="fa fa-star"></i>';
                                                    } else {
                                                        echo '<i class="fa fa-star-o" style="color: #cbd5e1;"></i>';
                                                    }
                                                }
                                                ?>
                                            </span>
                                            <span style="font-weight: 700; font-size: 12px; margin-left: 4px;"><?php echo $rating; ?>.0</span>
                                        </td>
                                        <td style="text-align: left;"><?php echo nl2br(htmlspecialchars($rows['ureview'])); ?></td>
                                        <td><?php echo $formattedDate; ?></td>
                                        <td>
                                            <a href="delete_review.php?id=<?php echo $rows['uid']; ?>&product_id=<?php echo urlencode($product_id); ?>" 
                                               onclick="return confirm('Are you sure you want to delete this review?');" 
                                               class="btn btn-danger btn-xs" style="border-radius: 4px; padding: 4px 10px;">
                                                <i class="fa fa-trash"></i> Delete
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
</section>
<?php
include "footer.php";
?>


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
