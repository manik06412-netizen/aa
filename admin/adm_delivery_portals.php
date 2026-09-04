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
      include ("../dbconnect.php");
       error_reporting(0);
      session_start();
    // insert query
    if(isset($_POST['submit'])){
        $c_name = $_POST['c_name'];
        $check = mysqli_query($con,"SELECT * FROM pordels_list where name = '{$c_name}'");
        if(mysqli_num_rows( $check) > 0){
            echo"<script>alert('ALREADY REGISTERED');window.location.href='delivery_portals.php';</script>";
        }else{
            $insert = mysqli_query($con,"INSERT INTO pordels_list VALUES (null,'{$c_name}')");
            if($insert){
                echo"<script>alert('REGISTERED SUCCESSFULLY');window.location.href='delivery_portals.php';</script>";
            }else{
                echo"<script>alert('ERROR');window.location.href='delivery_portals.php';</script>";
            }
        }
    }
    ?>
<?php include "head.php"; ?>

<body class="fix-header">
    <div id="main-wrapper">
        <!-- header header  -->
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper" style="height:1200px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Delivery Portals</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <hr>
                                            <div class="row p-t-20">

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Enter Delivery Portals</label>
                                                        <input type="text" name="c_name" id="exampleFormControlSection"
                                                            class="form-control" placeholder="Enter Delivery Portals"
                                                            required>
                                                    </div>

                                                </div>
                                                <!--/span-->
                                            </div>
                                            <div class="form-actions">
                                                <input type="submit" name="submit" class="btn btn-success" value="Save">
                                                <input type="reset" class="btn btn-inverse" value="Cancel">
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Listed Main Market Distribution</h4>
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Delivery Portals Name</th>
                                                <!-- <th>Country Code</th> -->
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $k =1;
                                       $sql="SELECT * FROM pordels_list ";
                                       $query=mysqli_query($con,$sql);
                                        if(!mysqli_num_rows($query) > 0 ){
                                       		echo '<td colspan="7"><center>No Categories-Data!</center></td>';
                                       	}else{				
                                       	  while($rows=mysqli_fetch_array($query)){ ?>
                                            <tr>
                                                <td><?= $k; ?></td>
                                                <td><?= $rows['name']; ?></td>
                                                <td>
                                                    <a href="#" onclick="confirmDelete(<?=$rows['id']; ?>,'pordels_list','delivery_portals')"
                                                        class="btn btn-danger btn-flat btn-addon btn-xs m-b-10"><i
                                                            class="fa fa-trash-o" style="font-size:16px"></i> </a>

                                                    <a href="update_delivery.php?cat_upd=<?=$rows['id']; ?>"
                                                        class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5"><i
                                                            class="ti-settings"></i></a>
                                                </td>
                                            </tr>
                                            <?php $k++; }	} ?>
                                            <script>
                                            function confirmDelete(categoryId, table, path) {
                                                var confirmDelete = confirm(
                                                    "Are you sure you want to delete this category?");
                                                if (confirmDelete) {
                                                    window.location.href = 'delete_main.php?cat_del=' + categoryId +
                                                        '&table=' + table + '&path=' + path;
                                                }
                                            }
                                            </script>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer"> © All rights reserved. </footer>
        </div>
    </div>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
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