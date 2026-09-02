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
// Start Session
session_start();
require_once('header.php');
require_once('../dbconnect.php'); // Ensure you have your DB connection here

// Capture the parameters
$id = isset($_GET['id']) ? $_GET['id'] : '';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

// Initialize an empty array for reviews
$reviews = [];

// Base SQL query to fetch reviews within the date range
$sql = "SELECT * FROM cust_reviews 
        WHERE cid = '$id'";

// Add date filtering if both dates are provided
if ($startDate != '' && $endDate != '') {
    $sql .= " AND DATE(createdat) BETWEEN '$startDate' AND '$endDate'";
}

// Execute the query
$query = mysqli_query($con, $sql);

// Fetch reviews
while ($row = mysqli_fetch_assoc($query)) {
    $reviews[] = $row;
}

?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Product Review</h1>
    </div>
    <div class="content-header-right">
       <a href="report_reviews.php"><button class="btn" style="background-color:#FF851B; color:white; border:1px solid #FF851B;">Go Back</button></a> 
    </div>
</section>
<section class="content">
    <div class="box box-info">
        <div class="box-body table-responsive">
            <table class="table table-bordered table-hover text-center table-striped">
                <thead>
                    <tr>
                        <th>Review ID</th>
                        <th>User ID</th>
                        <th>Review Text</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $s=0;
               
                if (empty($reviews)) {
                    echo '<tr><td colspan="4"><center>No reviews found for this date range!</center></td></tr>';
                } else {
                    foreach ($reviews as $review) {
                        $s++;
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($s) . '</td>'; // Adjust as per your column names
                        echo '<td>' . htmlspecialchars($review['uid']) . '</td>'; // Adjust as per your column names
                        echo '<td>' . htmlspecialchars($review['ureview']) . '</td>'; // Adjust as per your column names
                        echo '<td>' . date('d-m-Y', strtotime($review['createdat'])) . '</td>'; // Adjust as per your column names
                        echo '</tr>';
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>
