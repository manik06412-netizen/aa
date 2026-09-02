<?php
include '../dbconnect.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pd_code = $_POST['pd_code'];

    // Prepare and execute the SQL query
    $stmt = $con->prepare("
        SELECT 
            dishes.*, 
            prd_stock.*, 
            price.* 
        FROM 
            dishes 
        INNER JOIN 
            prd_stock ON dishes.rs_id = prd_stock.pd_code 
        INNER JOIN 
            price ON prd_stock.price_id = price.id
        INNER JOIN (
            SELECT 
                price_id, 
                MAX(id) AS max_id
            FROM 
                prd_stock
            GROUP BY 
                price_id
        ) latest_price ON prd_stock.id = latest_price.max_id
        WHERE 
            dishes.rs_id = ?
    ");
    $stmt->bind_param("i", $pd_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);

    $stmt->close();
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}

$con->close();
?>
