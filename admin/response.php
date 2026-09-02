<?php
session_start();
error_reporting(0);
include ("../dbconnect.php");

if (isset($_POST['Standard'])) {
    $selectedStandard = $_POST['Standard'];
    $query = "SELECT DISTINCT currency_code FROM countries WHERE country_name = '$selectedStandard'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['status' => true, 'res' => $row['currency_code']]);
    } else {
        echo json_encode(['status' => false, 'res' => 'Currency code not found']);
    }
} else {
    echo json_encode(['status' => false, 'res' => 'No country selected']);
}
?>
