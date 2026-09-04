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
session_start();
?>
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

$allowed_extensions = array('jpg', 'jpeg', 'png');  // Define allowed extensions

// Check if the file is empty
if (!empty($fname)) {
    // File is not empty, proceed with uploading
    $store = "Res_img/dishes/" . basename($fnew);

    if (in_array($extension, $allowed_extensions)) {
        if (move_uploaded_file($temp, $store)) {
            
        } else {
            // Handle upload failure
            //echo "Error uploading file.";
        }
    } else {
        // Handle invalid file type
    echo '<script>alert("Invalid file type. Allowed file types are jpg, jpeg, and png.");</script>';
    }
} else {
    // File is empty, use the value of $row['fpath']
    $store = $_SESSION['f'];
    
}
$fname = $_FILES['images1']['name'];
$temp = $_FILES['images1']['tmp_name'];
$fsize = $_FILES['images1']['size'];
$extension = pathinfo($fname, PATHINFO_EXTENSION);  // Get the file extension
$fnew = uniqid() . '.' . $extension;

$allowed_extensions = array('jpg', 'jpeg', 'png');  // Define allowed extensions

// Check if the file is empty
if (!empty($fname)) {
    // File is not empty, proceed with uploading
    $store1 = "Res_img/dishes/" . basename($fnew);

    if (in_array($extension, $allowed_extensions)) {
        if (move_uploaded_file($temp, $store1)) {
            
        } else {
            // Handle upload failure
            //echo "Error uploading file.";
        }
    } else {
        // Handle invalid file type
    echo '<script>alert("Invalid file type. Allowed file types are jpg, jpeg, and png.");</script>';
    }
} else {
    // File is empty, use the value of $row['fpath']
    $store1 = $_SESSION['f1'];
    
}
$check_cat = mysqli_query($con, "SELECT c_name FROM res_category WHERE c_name = '" . $_POST['c_name'] . "' AND c_id != '" . $_GET['cat_upd'] . "'");


	
	
	if(mysqli_num_rows($check_cat) > 0)
     {
    	$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Category already exist!</strong>
															</div>';
     }
	else{
    	
	$mql = "update res_category set c_name ='$_POST[c_name]',orderr='$_POST[order]',k1='$_POST[k1]',k2='$_POST[k2]',fpath='$store',icon='$store1' where c_id='$_GET[cat_upd]'";
	mysqli_query($con, $mql);
//     $success = '<div class="alert alert-success alert-dismissible fade show">
//     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
//     <strong>Updated!</strong> Successfully.<br>
//   </div>';

echo '<script>alert("Category updated");window.location.href = "add_category.php";</script>';

    
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
        <div class="page-wrapper">

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
                                            <?php $ssql ="select * from res_category where c_id='$_GET[cat_upd]'";
													$res=mysqli_query($con, $ssql); 
													$row=mysqli_fetch_array($res);
                                                    $_SESSION['f']=$row['fpath'];
                                                    $_SESSION['f1']=$row['icon'];
                                                    ?>
                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Category</label>
                                                        <input type="text" name="c_name"
                                                            value="<?php echo $row['c_name'];  ?>" class="form-control"
                                                            placeholder="Update Category Name">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label">Keyword 1</label>
                                                        <input type="text" name="k1" value="<?php echo $row['k1'];  ?>"
                                                            class="form-control" placeholder="Update Keyword">
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Order</label>
                                                        <input type="text" name="order"
                                                            value="<?php echo $row['orderr'];  ?>" class="form-control"
                                                            placeholder="Update Keyword">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label">Keyword 2</label>
                                                        <input type="text" name="k2" value="<?php echo $row['k2'];  ?>"
                                                            class="form-control" placeholder="Update Keyword">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Update Image</label>
                                                        <input type="file" name="images" class="form-control"
                                                            accept="image/*" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <img src="<?php echo $row['fpath']; ?>"
                                                            class="img-responsive radius"
                                                            style="max-height:100px;max-width:150px;" />

                                                    </div>
                                                </div>
                                                <div class="col-md-6">


                                                    <div class="form-group">

                                                      
                                                        <label>Update Image</label>
                                                        <input type="file" name="images1" class="form-control"
                                                            accept="image/*" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">


                                                    <div class="form-group">

                                                        <img src="<?php echo $row['icon']; ?>"
                                                            class="img-responsive radius"
                                                            style="max-height:100px;max-width:150px;" />
                                                       
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