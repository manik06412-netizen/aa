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
session_start();
include("../dbconnect.php");


    $postOfficeId = $_GET['postOfficeId'];
    $price = $_GET['price'];

    // Additional parameters
 
    $pincode = $_GET['pincode'];

    $district = $_GET['district'];
    
    // Check if the record already exists
    $checkQuery = "SELECT * FROM pinamount WHERE pincode = '$pincode'";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Record already exists, update it
        $updateQuery = "UPDATE pinamount SET price='$price' WHERE pincode='$pincode'";
        if (mysqli_query($con, $updateQuery)) {
           echo" <script>alert('Updated successfully');window.location.href='pinsave.php';</script>";
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    } else {
        // Record does not exist, insert a new record
        $insertQuery = "INSERT INTO pinamount ( pincode, district, price) VALUES ( '$pincode',  '$district','$price')";
        if (mysqli_query($con, $insertQuery)) {
            echo" <script>alert('Added successfully');window.location.href='pinsave.php';</script>";
        } else {
            echo "Error inserting record: " . mysqli_error($con);
        }
    }

?>
