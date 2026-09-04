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
// Start session
session_start();
error_reporting(0);
include('./inc/config.php');

require '../pdf/vendor/autoload.php'; 
use Dompdf\Dompdf;

$dompdf = new Dompdf();

$filter_add_1 = '';
$product_name_ = '';
$current_sts = '';

if (!empty($_GET['startDate']) && !empty($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    $startDateTime = DateTime::createFromFormat('Y-m-d', $startDate);
    $endDateTime = DateTime::createFromFormat('Y-m-d', $endDate);

    $formattedStartDate = $startDateTime->format('d-m-Y');
    $formattedEndDate = $endDateTime->format('d-m-Y');

    $filter_add_1 = "AND dishes.date_of_adding BETWEEN '$formattedStartDate' AND '$formattedEndDate' ";
}

if (!empty($_GET['product_name'])) {
    $product_name_ = 'AND dishes.dish_name = "' . mysqli_real_escape_string($con, $_GET['product_name']) . '"';
}

if (!empty($_GET['current_status'])) {
    $current_sts = trim($_GET['current_status']);
}

function MEASUREMENT_LOOP($con, $product, $stock_status) {
    $measurement = [];
    $stmt = $con->prepare("SELECT price.qn, price.wg, price.id, price.pcode, prd_stock.total FROM price 
            INNER JOIN prd_stock ON price.pcode = prd_stock.pd_code WHERE price.pcode = ? GROUP BY price.id");
    $stmt->bind_param("s", $product);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $select = mysqli_query($con, "SELECT * FROM prd_stock WHERE pd_code = '{$row['pcode']}' AND price_id = {$row['id']} ORDER BY id DESC LIMIT 1");
        $count = mysqli_fetch_array($select);
        if ($count['total'] > 0 && $count['stk_status'] == $stock_status) {
            $measurement[] = [
                'quantity' => $row['qn'],
                'weight' => $row['wg'],
                'stock' => $count['total'],
                'product_code' => $row['pcode'],
                'price_id' => $row['id'],
            ];
        }
    }
    $stmt->close();
    return $measurement;
}

// Function to create measurement rows
function createMeasurementRows($measurements) {
    if (empty($measurements)) {
        return '<tr ><td border="0">----</td></tr>';
    }

    $rows = '';
    foreach ($measurements as $measurement) {
        $measurementDetails = htmlspecialchars($measurement['quantity'] . ' ' . $measurement['weight'] . ' - ' . $measurement['stock']);
        $rows .= '<tr><td>' . $measurementDetails . '</td></tr>';
    }
    return $rows;
}

$sql = "SELECT * FROM dishes INNER JOIN price ON dishes.rs_id = price.pcode WHERE dishes.status = '1' " . $product_name_ . $filter_add_1 . " GROUP BY price.pcode ORDER BY d_id DESC";
$category_list = mysqli_query($con, $sql);

$html = '<h1>Product List</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">';
$html .= '<thead>
            <tr>
                <th>Date of Adding</th>
                <th>Products Name</th>
                <th>In Stock</th>
                <th>Out of Stock</th>
                <th>Stop Selling</th>
            </tr>
          </thead>';
$html .= '<tbody>';

while ($results_of = mysqli_fetch_array($category_list)) {

    $measurement_list = MEASUREMENT_LOOP($con, $results_of['rs_id'], 'Instock');
    $measurement_list_1 = MEASUREMENT_LOOP($con, $results_of['rs_id'], 'Currently Unavailable');
    $measurement_list_2 = MEASUREMENT_LOOP($con, $results_of['rs_id'], 'Inactive');

    if ($current_sts == '1' && empty($measurement_list)) continue;
    if ($current_sts == '3' && empty($measurement_list_1)) continue;
    if ($current_sts == '2' && empty($measurement_list_2)) continue;




    $product_name = htmlspecialchars($results_of['dish_name']);
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($results_of['date_of_adding']) . '</td>';
    $html .= '<td>' . $product_name . '</td>';



    $html .= '<td><table border="0" style="border: none;">';
    $html .= createMeasurementRows($measurement_list);
    $html .= '</table></td>';
    $html .= '<td><table border="0" style="border: none;">';
    $html .= createMeasurementRows($measurement_list_2);
    $html .= '</table></td>';
    $html .= '<td><table border="0" style="border: none;">';
    $html .= createMeasurementRows($measurement_list_1);
    $html .= '</table></td>';

    $html .= '</tr>';
}

$html .= '</tbody></table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream('product_list.pdf', ['Attachment' => 1]);
exit;
?>
