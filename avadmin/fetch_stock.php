<?php
include '../dbconnect.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = $_POST['id'];
    $stmt = $con->prepare("SELECT * FROM prd_stock WHERE pd_code=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
         echo json_encode(array('error' => 'No data found'));
    }

    $stmt->close();
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}

$con->close();
?>
