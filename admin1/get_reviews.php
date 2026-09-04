<?php
include "../dbconnect.php";

$product_id = $_GET['cid'];

$sql = "SELECT * FROM cust_reviews WHERE cid='$product_id' ORDER BY createdat DESC";
$query = mysqli_query($con, $sql);

if (mysqli_num_rows($query) > 0) {
    $ik = 1;
    echo '<div class="table-responsive">';
    echo '<table class="table table-hover table-striped table-bordered" style="text-align:center;">';
    echo '<thead>
            <tr>
                <th style="text-align:center;">Si.no</th>
                <th style="text-align:center;">Customer Name</th>
                <th style="text-align:center;">Rating</th>
                <th style="text-align:center;">Review</th>
                <th style="text-align:center;">Date</th>
                <th style="text-align:center;">Action</th>
            </tr>
          </thead>
          <tbody>';
    while ($rows = mysqli_fetch_array($query)) {
        echo '<tr>
                <td style="text-align:center;">' . $ik++ . '</td>
                <td style="text-align:center;">' . htmlspecialchars($rows['uname']) . '</td>
                <td style="text-align:center;">' . htmlspecialchars($rows['uratings']) . '</td>
                <td style="text-align:center;">' . htmlspecialchars($rows['ureview']) . '</td>
                <td style="text-align:center;">' . htmlspecialchars($rows['createdat']) . '</td>
                <td style="text-align:center;">
                    <a href="delete_review.php?id=' . $rows['uid'] . '" class="btn btn-danger">Delete</a>
                </td>
              </tr>';
    }
    echo '</tbody></table></div>';
} else {
    echo '<p>No reviews found for this product.</p>';
}

mysqli_close($con);
?>
