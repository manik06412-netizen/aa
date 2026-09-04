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
include "../dbconnect.php";

if (isset($_POST['order_id']) && isset($_POST['final_amt'])) {
    function Qty_of($con, $where)
    {
        $where = mysqli_real_escape_string($con, $where);
        $query = "SELECT SUM(qty) AS totals FROM final WHERE order_id = '$where'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['totals'] ?? 0;
    }

    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $final_amt = mysqli_real_escape_string($con, $_POST['final_amt']); // Capture final amount

    // Fetch shipment details
    $sql = "SELECT * FROM shipment WHERE order_id = '$order_id'";
    $sql_result = mysqli_query($con, $sql);

    if (mysqli_num_rows($sql_result) > 0) {
        $row = mysqli_fetch_assoc($sql_result);

        // Use Qty_of function to get items
        $items = Qty_of($con, $row['order_id']);

        // Map status codes to string values
        $status_map = [
            0 => 'Package delivered to source hub',
            1 => 'Package in transit',
            2 => 'Package reached destination',
            3 => 'Package delivered to destination hub',
            4 => 'Package out for delivery',
            5 => 'Package delivered',
        ];

        // Get the status string from the map
        $status_text = $status_map[$row['status']] ?? 'Unknown status';

        // Display shipment and final amount in the table
        echo "
        <table class='table table-bordered table-hover table-striped'>
            <thead>
                <tr>
                    <th style='width:30%'>Order Id</th>
                    <th style='width:30%'>Items</th>
                    <th style='width:30%'>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#".$row['order_id']."</td>
                    <td>".$items."</td>
                    <td>₹".$final_amt."</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table class='table table-bordered table-hover table-striped'>
            <thead>
                <tr>
                    <th style='width:25%'>Tracking Number</th>
                    <th style='width:25%'>Courier</th>
                    <th style='width:25%'>Destination</th>
                    <th style='width:25%'>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>".$row['track_number']."</td>
                    <td>".htmlspecialchars($row['courier'])."</td>
                    <td>".htmlspecialchars($row['destination'])."</td>
                    <td>".$status_text."</td> <!-- Use status text instead of numeric value -->
                </tr>
            </tbody>
        </table>";
    } else {
        echo "<p>No shipment details found.</p>";
    }
}
?>
