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
error_reporting(0);
session_start();


if(isset($_POST['submit'] ))
{
    if(empty($_POST['c_name']))
		{
			$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>field Required!</strong>
															</div>';
		}
	else
	{
		
	
        $fname = $_FILES['images']['name'];
$temp = $_FILES['images']['tmp_name'];
$fsize = $_FILES['images']['size'];
$extension = pathinfo($fname, PATHINFO_EXTENSION);  // Get the file extension
$fnew = uniqid() . '.' . $extension;

// Define allowed extensions


if (!empty($fname)) {
    // File is not empty, proceed with uploading
    $store = "Res_img/dishes/" . basename($fnew);

   
        if (move_uploaded_file($temp, $store)) {
            
        } else {
         
        }
    
} else {
    // File is empty, use the value of $row['fpath']
    $store = $_SESSION['f'];
    
}

$check_cat = mysqli_query($con, "SELECT * FROM banner WHERE k1 = '" . $_POST['c_name'] . "',k2 = '" . $_POST['k1'] . "',k3 = '" . $_POST['k2'] . "' AND c_id != '" . $_GET['cat_upd'] . "'");


	
	
	if(mysqli_num_rows($check_cat) > 0)
     {
    	$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Category already exist!</strong>
															</div>';
     }
	else{
    	
	$mql = "update banner set k1 ='$_POST[c_name]',k2='$_POST[k1]',k3='$_POST[k2]',fpath='$store',link= '$_POST[k3]' where id='$_GET[cat_upd]'";
	mysqli_query($con, $mql);
//     $success = '<div class="alert alert-success alert-dismissible fade show">
//     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
//     <strong>Updated!</strong> Successfully.<br>
//   </div>';

echo '<script>alert("Banner updated");window.location.href = "banner.php";</script>';

    
	}
}

}


?>
<?php include "head.php"; ?>

<body class="fix-header">
    <!-- Preloader - style you can find in spinners.css -->

    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->

        <?php include "navbar.php"; ?>

        <?php include "sidebar1.php"; ?>

        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
        <div class="page-wrapper" style="height:1200px;">

            <div class="container-fluid">

                <div class="row">


                    <div class="container-fluid">
                        <!-- Start Page Content -->


                        <?php  
									        echo $error;
									        echo $success; ?>

                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Update Category</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <?php $ssql ="select * from banner where id='$_GET[cat_upd]'";
													$res=mysqli_query($con, $ssql); 
													$row=mysqli_fetch_array($res);
                                                    $_SESSION['f']=$row['fpath'];
                                                    $_SESSION['f1']=$row['icon'];
                                                    ?>
                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Title</label>
                                                        <input type="text" name="c_name"
                                                            value="<?php echo $row['k1'];  ?>" class="form-control"
                                                            placeholder="Update Category Name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Hading</label>
                                                        <input type="text" name="k1" value="<?php echo $row['k2'];  ?>"
                                                            class="form-control" placeholder="Update Keyword">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Slogan</label>
                                                        <input type="text" name="k2" value="<?php echo $row['k3'];  ?>"
                                                            class="form-control" placeholder="Update Keyword">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Link</label>
                                                        <input type="text" name="k3" value="<?php echo $row['link'];  ?>"
                                                            class="form-control" placeholder="Update Keyword">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">


                                                    <div class="form-group">

                                                        <img src="<?php echo $row['fpath']; ?>"
                                                            class="img-responsive radius"
                                                            style="max-height:100px;max-width:150px;" />
                                                        <label>Update Image</label>
                                                        <input type="file" name="images" class="form-control"
                                                            accept="image/*" />
                                                    </div>
                                                </div>
                                               

                                            </div>
                                            <!--/span-->

                                        </div>
                                        <div class="form-actions">
                                            <input type="submit" name="submit" class="btn btn-success" value="Save">
                                            <a href="dashboard.php" class="btn btn-inverse">Back</a>
                                        </div>
                                    </form>
                                </div>
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
        <footer class="footer"> © All rights reserved. </footer>
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