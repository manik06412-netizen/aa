<?php 
     // error_reporting(0);
       session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
include("../config.php");
error_reporting(0);
session_start();
if(isset($_POST['submit'])) {
    $id = $_POST['id'];
    $qns = $_POST['cname'];
    $ans = $_POST['cname1'];
       $sql = "UPDATE faq SET ans = '$ans', qns = '$qns' WHERE id = '$id'";
        if(mysqli_query($con, $sql)) {
       
            echo "<script>alert('Product Updated Successfully');window.location.href='qa.php';</script>";
      
    } else {
        echo "<script>alert('Error updating product');window.location.href='qa.php';</script>";
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
                                    <h4 class="m-b-0 text-white">FAQ</h4>
                                </div>
                                <?php $qml ="select * from faq where id='$_GET[menu_upd]'";
													$rest=mysqli_query($con, $qml); 
													$roww=mysqli_fetch_array($rest);
                                                                                                       														?>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <hr>
                                            <div class="row p-t-20">
                                                <input type="hidden" name="id" value="<?php echo $roww['id']; ?>">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Question</label>
                                                        <input type="text" name="cname"                                  value="<?php echo $roww['qns']; ?>" class="form-control"
                                                          required>
                                                    </div>

                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Answer</label>
                                                        <textarea name="cname1" id="exampleFormControlSection"
                                                          class="form-control"
                                                            required><?php echo $roww['ans']; ?></textarea>
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