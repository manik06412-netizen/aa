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
<!DOCTYPE html>
<html lang="en">
<?php
include("../dbconnect.php");
//error_reporting(0);
session_start();

?>

<?php include "head.php"; ?>

<body class="fix-header fix-sidebar">

    <div id="main-wrapper">
        <!-- header header  -->

        <?php include "navbar.php"; ?>

        <?php include "sidebar1.php"; ?>
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Confirmed Orders</h3>
                </div>

            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <?php

include '../dbconnect.php';

// Fetch orders
$query = "SELECT * FROM final INNER JOIN chekout ON final.refid = chekout.ref_id  where final.status='2' group by order_id order by final.id desc ";
$sql = mysqli_query($con, $query);

while ($ree = mysqli_fetch_array($sql)) {
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>"; // Start of the card
    echo "<div class='row'>";
echo "<div class='col-lg-6'>";
    //echo "Reference id: " . $ree['refid'] . "<br/>";
      echo "<strong>Date:</strong> " . $ree['date'] ."<br/> ";
    echo "<strong>Order Id:</strong> " . $ree['order_id'] . "<br/> ";

    echo "<strong>User Id: </strong>" . $ree['user_id'] . "<br/> ";
  
     echo "<strong>Name: </strong>" . $ree['fname'] . "<br/>";
     echo "<strong>Address:</strong> " . $ree['address1'] .  $ree['address2'] ."<br/>" . $ree['town'] ."<br/>" . $ree['state'] ."<br/>" . $ree['country']."-". $ree['pincode']."<br/>";
echo"</div>";
    // Fetch items related to the order
    $itemQuery = "SELECT * FROM chekout WHERE ref_id = " . $ree['refid'];
    $itemSql = mysqli_query($con, $itemQuery);
    echo "<div class='col-lg-6'>";
    echo "<table border='1' style='margin: 2 auto;'>";

    echo "<tr><th>Item Name</th><th>Item Price</th><th>Item Code</th></tr>";
    while ($item = mysqli_fetch_array($itemSql)) {
        echo "<tr>";
        echo "<td>" . $item['p_name']  . "</td>";
        echo "<td>" . $item['ct_py'] . "</td>";
        echo "<td>" . $item['pr_id'] . "</td>";
        echo "</tr>";
      
    }
    echo"<td></td>";
    echo "<td> <strong>sub_total:</strong> </td>";
    echo "<td>". $ree['sub_tol'] . "</td>";
    echo "<tr><td></td><td> <strong>total:</strong></td>";
    echo "<td>". $ree['total'] . "</td></tr>";
    echo "</table><br/>";
    echo '<a href="c_order1.php?id=' . $ree['order_id'] . '" class="btn btn-success">Dispatch Order</a>';

    echo "</div>"; 
    echo "</div>"; 
    echo "</div>"; // End of the card
}


                                ?>
                            </div>
                        </div>



                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 All rights reserved. </footer>
    <!-- End footer -->
    </div>
    <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
    <!-- All Jquery -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>


    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
</body>

</html>