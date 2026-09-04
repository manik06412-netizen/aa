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
include("../config.php");
error_reporting(0);
session_start();
function No_of($con,$table,$contusions){
    $count = mysqli_query($con,"SELECT * FROM $table  $contusions");
    $count = mysqli_num_rows($count);
    return $count;
}
?>
<?php include "head.php"; ?>

<body class="fix-header fix-sidebar">

    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>

        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 mt-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-primary" href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">General</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-12 mt-4">
                        <!-- <h4 class="card-title ml-2">General</h4> -->
                        <!-- Icon Cards-->
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 mb-3">
                                <a href="allusers.php">
                                    <div class="card dashboard text-white o-hidden h-75">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-1 text-end">
                                                    <div class="card-body-icon mt-1">

                                                    </div>
                                                </div>
                                                <div class="col-10">
                                                    <div class="card-body-icon mt-2">
                                                        <h4><i class="fa fa-user text-dark f-s-20 mr-2"></i> Users</h4>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-center position-relative top-20">
                                                    <h5 class="mt-3 " style="margin-bottom:15px; font-wight:bold;font-size:30px;">
                                                        <?=No_of($con,'user',"WHERE fname!='Guest'"); ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white clearfix small z-1">
                                            <a href="allusers.php">
                                                <span class="float-left">View Details</span>
                                                <span class="float-right">
                                                    <i class="fa fa-angle-right"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-sm-6 mb-3">
                                <a href="vendor_list.php">
                                    <div class="card dashboard text-white  o-hidden h-75">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-1 text-end">
                                                    <div class="card-body-icon mt-1">
                                                    </div>
                                                </div>
                                                <div class="col-10">
                                                    <div class="card-body-icon mt-2">


                                                        <h5><i class="fa fa-balance-scale text-dark  mr-2 "
                                                                aria-hidden="true"></i> Vendors</h5>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-center position-relative top-20">
                                                    <h4 class="mt-3 text-dark" style="margin-bottom:15px;  font-wight:bold;font-size:30px;">
                                                        <?=No_of($con,'vendor',""); ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white clearfix small z-1">
                                            <a href="vendor_list.php">
                                                <span class="float-left">View Details</span>
                                                <span class="float-right">
                                                    <i class="fa fa-angle-right"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-sm-6 mb-3">
                                <a href="add_btype.php">
                                    <div class="card dashboard text-white  o-hidden h-75">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-1 text-end">
                                                    <div class="card-body-icon mt-1">
                                                    </div>
                                                </div>
                                                <div class="col-10">
                                                    <div class="card-body-icon mt-2">
                                                        <h5> <i class="fa fa-briefcase mr-2" aria-hidden="true"></i>
                                                            Business Type</h5>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-center position-relative top-20">
                                                    <h4 class="mt-3 " style="margin-bottom:15px; font-wight:bold;font-size:30px;">
                                                        <?=No_of($con,'btype',""); ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white clearfix small z-1">
                                            <a href="add_btype.php">
                                                <span class="float-left">View Details</span>
                                                <span class="float-right">
                                                    <i class="fa fa-angle-right"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-sm-6 mb-3">
                                <a href="add_category.php">
                                    <div class="card dashboard text-white o-hidden h-75">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-1 text-end">
                                                    <div class="card-body-icon mt-1">
                                                    </div>
                                                </div>
                                                <div class="col-10">
                                                    <div class="card-body-icon mt-2">
                                                        <h5> <i class="fa fa-archive f-s-20 mr-2"></i> Categories</h5>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-center position-relative top-20">
                                                    <h4 class="mt-3 " style="margin-bottom:15px; font-wight:bold;font-size:30px;">
                                                        <?=No_of($con,'res_category',""); ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white clearfix small z-1">
                                            <a href="add_category.php">
                                                <span class="float-left">View Details</span>
                                                <span class="float-right">
                                                    <i class="fa fa-angle-right"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- </div> -->
                        <!-- </div> -->
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