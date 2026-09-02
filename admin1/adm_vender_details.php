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
      function CallSelect($con, $value, $table) {
        $options = ''; 
        $query = "SELECT DISTINCT $value FROM $table";
        $result = mysqli_query($con, $query);
        if ($result) {
            
            while ($row = mysqli_fetch_array($result)) {
                $options .= "<option value='" . $row[$value] . "'>" . $row[$value] . "</option>";
            }
        } else {
            $options .= "<option value=''>Error retrieving data</option>";
        }
        return $options; 
    }
    // insert query
        $vendor_id = $_GET['id'];
        $check = mysqli_query($con,"SELECT * FROM vendor where id = '{$vendor_id}'");
        
        
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
                                    <h4 class="m-b-0 text-white">Vendor details</h4>
                                </div>
                                <div class="card-body">
                                    <?php if(mysqli_num_rows($check)>0){ 
                                        $row = mysqli_fetch_array($check);
                                    ?>
                                    <div class="form-body">
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Business Type</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['business_type']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Company Name</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['company_name']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Owner Name</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['owner_name']; ?>">
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Contact Number</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['contact_number']; ?>">
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Email id</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['email']; ?>">
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Year of establishment</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['year_of_establishment']; ?>">
                                                </div>
                                            </div>
                                            <!--  -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Company Address</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['company_address']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Country</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['country']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Company Website</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['company_website']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Registration Date</label>
                                                    <input type="text" class="form-control border-0" readonly
                                                        value="<?=$row['registration_date']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
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