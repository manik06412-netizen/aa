<?php 

include "../dbconnect.php";

// Get the review ID and the product ID from the query parameters
$id = $_GET['id'];
$product_id = $_GET['product_id'];

// Delete the review based on the review ID
$query = "DELETE FROM cust_reviews WHERE uid='$id'";
$result = mysqli_query($con, $query);

// Redirect to the `get_reviews.php` page with the selected product ID
header("Location:reviews.php");
exit;

?>
