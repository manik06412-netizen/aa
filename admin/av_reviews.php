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

require_once('header.php');
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Review Details';
echo "<script>var sessionTitle = '$title';</script>";
// Modified SQL query to count the number of reviews per product
$sql = "SELECT dishes.dish_name, cust_reviews.cid, COUNT(cust_reviews.uid) as review_count, MIN(cust_reviews.createdat) as createdat 
        FROM cust_reviews  
        JOIN dishes ON cust_reviews.cid = dishes.rs_id 
        GROUP BY cust_reviews.cid
        ORDER BY cust_reviews.cid";
$query = mysqli_query($con, $sql);
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Product Review</h1>
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
                                <!-- <th>Si.no</th> -->
                                <th>Product Name</th>
                                <th>No. of Reviews</th> <!-- Added column for number of reviews -->
                                <th>Date </th>
                                <th>View Reviews</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!mysqli_num_rows($query) > 0) {
                                echo '<tr><td colspan="5"><center>No products with reviews!</center></td></tr>';
                            } else {
                                // $ik = 1;
                                while ($rows = mysqli_fetch_array($query)) {
                                    // Format the date
                                    $date = DateTime::createFromFormat('Y-m-d', $rows['createdat']);
                                    $formattedDate = $date->format('d-m-Y');
                                    ?>
                                    <tr>
                                        <!-- <td><?php echo $ik++ ;?></td> -->
                                        <td><?php echo htmlspecialchars($rows['dish_name']); ?></td>
                                        <td><?php echo $rows['review_count']; ?></td> <!-- Display number of reviews -->
                                        <td><?=$formattedDate; ?></td>
                                        <td>
                                            <a href="get_reviews.php?id=<?php echo $rows['cid']; ?>">
                                                <button type="button" data-id="<?php echo $rows['cid']; ?>"
                                                        class="btn btn-md view-reviews"
                                                        style="background-color:#FF851B; color:white; border:1px solid #FF851B;"
                                                        data-bs-toggle="modal" data-bs-target="#confirm-delete">
                                                    View Reviews
                                                </button>
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
