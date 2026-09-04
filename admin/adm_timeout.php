<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
?>
<?php
include('../config.php');
$sqlee = "SELECT * FROM promo ORDER BY id DESC";
$queryee = mysqli_query($con, $sqlee);

if (!mysqli_num_rows($queryee) > 0) {
    echo '<td colspan="7"><center>No Categories-Data!</center></td>';
} else {
    while ($rowsee = mysqli_fetch_array($queryee)) {
        $current = strtotime($rowsee['sdat']);
        $end = strtotime($rowsee['edat']);

        // Calculate the timestamp for the day after the end date
        $nextDayAfterEnd = strtotime('+1 day', $end);
        
        if (time() >= $nextDayAfterEnd) {
            // The current date is equal to or exceeds the day after the end date

            // Update the status to '0'
            $mqlee = "UPDATE promo SET status = '1' WHERE edat = '$rowsee[edat]'";
            mysqli_query($con, $mqlee);
        }
    }
}
?>
