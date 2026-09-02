<?php 
session_start();
error_reporting(0);
include('./inc/config.php');

if (isset($_POST['product_id'])) {
    $filter_add = '';
    $filter_add_1 = '';

    if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $startDateTime = DateTime::createFromFormat('Y-m-d', $startDate);
        $endDateTime = DateTime::createFromFormat('Y-m-d', $endDate);

        $formattedStartDate = $startDateTime->format('d-m-Y');
        $formattedEndDate = $endDateTime->format('d-m-Y');
    
            $filter_add_1 = "AND date BETWEEN '$formattedStartDate' AND '$formattedEndDate' ";
      
    }
    

    $response = '';
    $product = mysqli_real_escape_string($con, $_POST['product_id']); 
    $price = intval($_POST['price_id']); 

    $query = "SELECT * FROM prd_stock WHERE pd_code ='$product' AND price_id = $price $filter_add_1 ";
    $select = mysqli_query($con, $query);

    if ($select) {
        $response = '<table class="table table-bordered" id="export_modal_table"> 
         <thead>
        <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Total Quantity</th>
        </tr>
    </thead>
    <tbody>';

        while ($row = mysqli_fetch_array($select)) {
            $response .= '<tr>
                <td>' . $row['date'] . '</td>
                <td>' . htmlspecialchars($row['note']) . '</td>
                <td>' . htmlspecialchars($row['total']) . '</td>
            </tr>';
        }
        $response .= '</tbody></table>';

        echo json_encode(['status' => 1, 'response' => $response]);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Query failed: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Invalid input.']);
}
?>