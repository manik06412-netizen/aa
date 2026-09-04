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

require_once('header.php');
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Review Details';
echo "<script>var sessionTitle = '$title';</script>";

// Modified SQL query to count the number of reviews per product
$sql = "SELECT COALESCE(dishes.dish_name, CONCAT('Product #', cust_reviews.cid)) as dish_name, 
               cust_reviews.cid, 
               COUNT(cust_reviews.uid) as review_count, 
               MAX(cust_reviews.createdat) as createdat 
        FROM cust_reviews  
        LEFT JOIN dishes ON (cust_reviews.cid = dishes.rs_id OR cust_reviews.cid = dishes.d_id)
        GROUP BY cust_reviews.cid
        ORDER BY MAX(cust_reviews.createdat) DESC";
$query = mysqli_query($con, $sql);
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Product Reviews</h1>
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
                    <table id="example1" class="table table-bordered table-hover text-center table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>No. of Reviews</th>
                                <th>Latest Review Date</th>
                                <th>View Reviews</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!$query || mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="5"><center>No products with reviews found!</center></td></tr>';
                            } else {
                                $ik = 1;
                                while ($rows = mysqli_fetch_array($query)) {
                                    $formattedDate = !empty($rows['createdat']) ? date('d-m-Y', strtotime($rows['createdat'])) : '-';
                                    ?>
                                    <tr>
                                        <td><?php echo $ik++; ?></td>
                                        <td style="font-weight: 600; text-align: left; padding-left: 20px;"><?php echo htmlspecialchars($rows['dish_name']); ?></td>
                                        <td><span class="badge-count" style="background:#eff6ff !important;color:#2563eb !important;border-color:#bfdbfe !important; font-size:12px; font-weight:700; padding:4px 10px; border-radius:6px; border:1px solid;"><?php echo $rows['review_count']; ?></span></td>
                                        <td><?=$formattedDate; ?></td>
                                        <td>
                                            <a href="get_reviews.php?id=<?php echo urlencode($rows['cid']); ?>" class="btn-table-action" style="border-radius:8px; font-size:12px; font-weight:600; padding:6px 14px; display:inline-flex; align-items:center; gap:6px; background:#fff; border:1.5px solid #0070F3; color:#0070F3; transition:all 0.15s ease; text-decoration:none;" title="View Reviews">
                                                <i class="fa fa-eye"></i> View Reviews
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

<?php require_once('footer.php'); ?>
