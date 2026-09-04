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
if(isset($_POST['submit'])) 
{
	
	
		if(empty($_POST['about'])||$_POST['oprice']=='')
		{	
											$error = 	'<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>All fields Must be Fillup!</strong>
															</div>';
									
					
		}
	else
		{
		$price=$_POST['state'];
$pp = strtoupper($price);
			   	$sql ="update pin set circle='$_POST[cir]',state='$pp',pin='$_POST[d_name]', dist='$_POST[about]',taluk='$_POST[oprice]' where id='$_POST[d]' "; // update the submited data ino the database :images
					mysqli_query($con, $sql); 
					move_uploaded_file($temp, $store);
			  
					echo "<script>alert('pin amount updated');window.location.href='pinupdate.php';</script>";
                                
	
										}
					}
                    ?>
	   
                  

<?php include "head.php"; ?>

<body class="fix-header">
   
    <!-- Main wrapper  -->
    <div id="main-wrapper">
               
      <?php include "navbar.php"; ?>
      <?php include "sidebar1.php"; ?>
       
        <!-- Page wrapper  -->
        <div class="page-wrapper" style="height:1200px;">
            <!-- Bread crumb -->
           
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->


                <?php  echo $error;
				      echo $success; ?>
            <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Update Pincode</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php $qml ="select * from pin where id='$_GET[pin]'";
													$rest=mysqli_query($con, $qml); 
													$roww=mysqli_fetch_array($rest);
														?>
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                        <input type="hidden" name="d" value="<?php echo $roww['id'];?>"
                                                    class="form-control" placeholder="Product name" readonly>
                                            <div class="form-group">
                                                <label class="control-label">Pincode</label>
                                                <input type="text" name="d_name" value="<?php echo $roww['pin'];?>"
                                                    class="form-control" placeholder="Update Pincode" >
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Circle</label>
                                                <input type="text" name="cir" value="<?php echo $roww['circle'];?>"
                                                    class="form-control form-control-danger" placeholder="Update Circle Name" >
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Taluk </label>
                                                <input type="text" name="oprice" value="<?php echo $roww['taluk'];?>"
                                                    class="form-control" placeholder="Update Taluk Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">District</label>
                                                <input type="text" name="about" value="<?php echo $roww['dist'];?>"
                                                    class="form-control form-control-danger" placeholder="Update District Name" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">State </label>
                                                <input type="text" name="state" value="<?php echo $roww['state'];?>"
                                                    class="form-control" placeholder="Update State Name">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        
                                    </div>
                                    <!--/row-->

                                    <!--/span-->
                                    <div class="row">
                                    </div>

                                </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" name="submit" class="btn btn-success" value="save">
                            <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->

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

</body>

</html>