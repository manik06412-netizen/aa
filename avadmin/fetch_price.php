<?php
include '../dbconnect.php'; // Include your database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Prepare and execute the SQL query
    $stmt = $con->prepare("SELECT * FROM price WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Return data as JSON
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
