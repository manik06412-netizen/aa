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
<?php 
       error_reporting(0);
       session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
      include ("../dbconnect.php");

      $cat_upd = $_GET['cat_upd'];
      $select_details = mysqli_query($con,"SELECT * FROM acc_currency where id={$cat_upd}");
$fetch_results = mysqli_fetch_array($select_details);
    // insert query
    if(isset($_POST['submit'])){
        $c_name = $_POST['c_name'];
        $table_id = $_POST['table_id'];
        $update_connect = null;
        $check = mysqli_query($con,"SELECT * FROM acc_currency");
        while($row = mysqli_fetch_array($check)){
              if($row["id"] == $table_id ){
                continue;
              }
              if ($row["currency"] == $c_name) {
                $update_connect = 4; 
                break; 
            }
        }
        if (!isset($update_connect)){
            $update = mysqli_query($con,"UPDATE acc_currency SET currency = '{$c_name}' WHERE id={$table_id}");
            if($update){
                echo"<script>alert('UPDATED SUCCESSFULLY');window.location.href='accepted_currency.php';</script>";
            }else{
                echo"<script>alert('ERROR');window.location.href='accepted_currency.php';</script>";
            }
        }else{
            echo"<script>alert('ALREADY REGISTERED');window.location.href='accepted_currency.php';</script>";
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
                                    <h4 class="m-b-0 text-white">Accepted Currency</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <hr>
                                            <div class="row p-t-20">
                                                <input type="hidden" name="table_id" value="<?=$cat_upd; ?>">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Enter Currency</label>
                                                        <input type="text" name="c_name" id="exampleFormControlSection"
                                                            value="<?=$fetch_results['currency']; ?>" class="form-control"
                                                            placeholder="Enter Currency" required>
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