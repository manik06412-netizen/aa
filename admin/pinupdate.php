<!DOCTYPE html>
<html lang="en">

<?php
include("../config.php");
error_reporting(0);
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
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">View Pincode data</h4>
                                <div class="table-responsive m-t-40">
                                <table id="example23" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>State</th>
                                                <th>District</th>
                                                <th>Taluk</th>
                                                <th>PIN</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM pin";
                                            $query = mysqli_query($con, $sql);

                                            if (!$query || mysqli_num_rows($query) <= 0) {
                                                echo '<td colspan="5"><center>No pincode-Data!</center></td>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    $status = $rows['status'];
                                                                            
                                                    if ($status == 0) {
                                                        $buttonClass = 'btn btn-success btn-flat btn-addon btn-sm m-b-10 m-l-5';
                                                        $buttonText = 'Activate';
                                                        $buttonLink = 'update_pinstatus.php?cat_upd=' . $rows['id'] . '&status=1';
                                                    } else {
                                                        $buttonClass = 'btn btn-danger btn-flat btn-addon btn-sm m-b-10 m-l-5';
                                                        $buttonText = 'Inactivate';
                                                        $buttonLink = 'update_pinstatus.php?cat_upd=' . $rows['id'] . '&status=0';
                                                    }
                                                    
                                                    echo '<tr>
                                                            <td>' . $rows['state'] . '</td>
                                                            <td>' . $rows['dist'] . '</td>
                                                            <td>' . $rows['taluk'] . '</td>
                                                            <td>' . $rows['pin'] . '</td>
                                                            <td>
                                                                <a href="#" onclick="confirmDelete(' . $rows['id'] . ')" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                    <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                </a>
                                                                <a href="pin2.php?pin=' . $rows['id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
                                                                    <i class="ti-settings"></i>
                                                                </a>
                                                                <a href="' . htmlspecialchars($buttonLink) . '" class="' . htmlspecialchars($buttonClass) . '">
                                                                ' . $buttonText . '
                                                            </a>
                                                            </td>
                                                          </tr>';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer"> © All rights reserved. </footer>
    </div>
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
    <!-- DataTables -->
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>

    <script>
        function confirmDelete(categoryId) {
            var confirmDelete = confirm("Are you sure you want to delete this category?");
            if (confirmDelete) {
                window.location.href = 'delete_pin.php?cat_del=' + categoryId;
            } else {
                // Do nothing or handle cancellation
            }
        }
    </script>
</body>

</html>
