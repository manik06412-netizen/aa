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
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
<?php
include("../config.php");
error_reporting(0);
session_start();

if(isset($_POST['submit']))           //if upload btn is pressed
{
	
	
		if(empty($_POST['d_name']))
		{	
											$error = 	'<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>All fields Must be Fillup!</strong>
															</div>';
		}
	else
		{
            
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
                    echo "Error uploading file.";
                }
            } else {
                // Handle invalid file type
                 echo "<script>alert('Invalid file type. Allowed file types are jpg, jpeg, and png.');window.location.href='all_menu.php';</script>";
            }
        } else {
            // File is empty, use the value of $row['fpath']
            $store1 = $_SESSION['f'];
            
        }
        
        $fname = $_FILES['images2']['name'];
$temp = $_FILES['images2']['tmp_name'];
$fsize = $_FILES['images2']['size'];
$extension = pathinfo($fname, PATHINFO_EXTENSION);  // Get the file extension
$fnew = uniqid() . '.' . $extension;

$allowed_extensions = array('jpg', 'jpeg', 'png');  // Define allowed extensions

// Check if the file is empty
if (!empty($fname)) {
    // File is not empty, proceed with uploading
    $store2 = "Res_img/dishes/" . basename($fnew);

    if (in_array($extension, $allowed_extensions)) {
        if (move_uploaded_file($temp, $store2)) {
            
        } else {
            // Handle upload failure
            echo "Error uploading file.";
        }
    } else {
        // Handle invalid file type
        echo "<script>alert('Invalid file type. Allowed file types are jpg, jpeg, and png.');window.location.href='all_menu.php';</script>";
    }
} else {
    // File is empty, use the value of $row['fpath']
    $store2 = $_SESSION['f1'];
    
}

$fname = $_FILES['images3']['name'];
$temp = $_FILES['images3']['tmp_name'];
$fsize = $_FILES['images3']['size'];
$extension = pathinfo($fname, PATHINFO_EXTENSION);  // Get the file extension
$fnew = uniqid() . '.' . $extension;

$allowed_extensions = array('jpg', 'jpeg', 'png');  // Define allowed extensions

// Check if the file is empty
if (!empty($fname)) {
    // File is not empty, proceed with uploading
    $store3 = "Res_img/dishes/" . basename($fnew);

    if (in_array($extension, $allowed_extensions)) {
        if (move_uploaded_file($temp, $store3)) {
            
        } else {
            // Handle upload failure
            echo "Error uploading file.";
        }
    } else {
        // Handle invalid file type
        echo "<script>alert('Invalid file type. Allowed file types are jpg, jpeg, and png.');window.location.href='all_menu.php';</script>";
    }
} else {
    // File is empty, use the value of $row['fpath']
    $store3 = $_SESSION['f2'];
    
}

	  $check_cat = mysqli_query($con, "SELECT dish_name FROM dishes WHERE dish_name = '" . $_POST['d_name'] . "' AND d_id != '" . $_POST['id'] . "'");


		if(mysqli_num_rows($check_cat) > 0)
     {
    	$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Product already exist!</strong>
															</div>';
     }
	else{
        $ss = $_POST['ss'];
		$cat_query = "SELECT * FROM res_category WHERE c_name='$ss'";
                                            $result = mysqli_query($con, $cat_query);
                                            if ( $roww = mysqli_fetch_assoc($result)) {
                                                
                                                $c_id = $roww['c_id'];
                                            }
		                                    $id = $_POST['id'];
                                            $code = $_POST['code'];
                                            $d_name = $_POST['d_name'];
                                            $sc = $_POST['sc'];
                                            $total_quantity = $_POST['total_quantity'];
                                            $about = $_POST['about'];
                                            $stock = $_POST['stock'];
                                            $subcate = $_POST['sc'];
                                             $wg = $_POST['wg'];
                                            $qn = $_POST['qn'];
                                            $pp = $_POST['pp'];
                                            $oprice = $_POST['oprice'];
                                            $discount = $_POST['discount'];
                                            $ss = $_POST['category'];
                                            $brand_name=$_POST['brand_name'];
                                            $brand_option=$_POST['brand_option'];
                                            $product_option=$_POST['product_option'];
                                            $detailed_desc=$_POST['detailed_desc'];
                                            
                                            $sql = "UPDATE dishes SET
                                            
                                            dish_name = '$d_name',
                                            subcate = '$sc',
                                            category = '$ss',
                                            Total_Quantity = '$total_quantity',
                                            description = '$about',
                                            stock = '$stock',
                                            -- oprice = '$oprice',
                                            -- qn = '$qn',
                                            -- wg = '$wg',
                                            -- pp = '$pp',
                                            -- discount = '$discount',
                                            img = '$store1',
                                            img2 = '$store2',
                                            img3 = '$store3',
                                            brand_name = '$brand_name',
                                            brand_option = '$brand_option',
                                            product_option = '$product_option',
                                            detailed_desc = '$detailed_desc'
                                        WHERE d_id = '$id'";
                                        
                                        // Check if the query executed successfully
                                        if (mysqli_query($con, $sql)) {
                                            echo "<script>alert('Product Updated Successfully');window.location.href='all_menu.php';</script>";
                                        } else {
                                            echo "Error updating record: " . mysqli_error($con);
                                        }
										}}
                                    }
					
			
	   

?>
<?php include "head.php"; ?>

<body class="fix-header fix-sidebar">
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <!-- Start Page Content -->


              

                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Update Product</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php $qml ="select * from dishes where d_id='$_GET[menu_upd]'";
													$rest=mysqli_query($con, $qml); 
													$roww=mysqli_fetch_array($rest);
                                                    $_SESSION['f']=$roww['img'];
                                                    $_SESSION['f1']=$roww['img2'];
                                                    $_SESSION['f2']=$roww['img3'];
                                                    

														?>


<div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-12 add_top_30">
                                    <form action='' method='post' id="myForm" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $roww['d_id']; ?>">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Product Name</label>
                <input type="text" name="d_name" class="form-control" value="<?php echo $roww['dish_name']; ?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Brand Name</label>
                <input type="text" name="brand_name" class="form-control" value="<?php echo $roww['brand_name']; ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Select Category</label>
                <select name="category" class="form-control custom-select" required>
                    <option> <?php echo $roww['category']; ?></option>
                    <?php
                    $ssql = "SELECT * FROM res_category";
                    $res = $con->query($ssql);
                    while ($row = $res->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['c_name']) . '">' . htmlspecialchars($row['c_name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Sub Category</label>
                <input type="text" name="sc" class="form-control" value="<?php echo $roww['subcate']; ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Enter product description (minimum 500 characters)</label>
                <textarea name="about" class="form-control form-control-danger" rows="5" required><?php echo $roww['description']; ?></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Additional Information (minimum 500 characters)</label>
                <textarea name="stock" rows="7" class="form-control form-control-danger" required><?php echo $roww['stock'];?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
    <div class="form-group">
        <label class="control-label">Upload Image1</label>
        <input type="file" class="form-control" id="imageUpload" name="images1">
        <img src="<?php echo $roww['img']; ?>" width="80px" height="80px">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="control-label">Upload Image2</label>
        <input type="file" class="form-control" id="imageUpload" name="images2">
        <img src="<?php echo $roww['img2']; ?>" width="80px" height="80px">
    </div>
</div>
</div>
<div class="row">
<div class="col-md-6">
    <div class="form-group">
        <label class="control-label">Upload Image3</label>
        <input type="file" class="form-control" id="imageUpload" name="images3">
        <img src="<?php echo $roww['img3']; ?>" width="80px" height="80px">
    </div>

    </div>
   
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Is Brand</label><br>
                <input type="radio" name="brand_option" value="active" <?php echo ($roww['brand_option'] == 'active') ? 'checked' : ''; ?>> Active &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="brand_option" value="inactive" <?php echo ($roww['brand_option'] == 'inactive') ? 'checked' : ''; ?>> Inactive
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Is Product</label><br>
                <input type="radio" name="product_option" value="active" <?php echo ($roww['product_option'] == 'active') ? 'checked' : ''; ?>> Active &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="product_option" value="inactive" <?php echo ($roww['product_option'] == 'inactive') ? 'checked' : ''; ?>> Inactive
            </div>
        </div>
    </div>
    <div class="row">
       <div class="col-lg-12">
           <label class="control-label">Detailed Description</label>
           <textarea id="summernote" name="detailed_desc"><?php echo $roww['detailed_desc']; ?></textarea>
       </div>
    </div>
    <div class="form-actions">
        <input type="submit" name="submit" class="btn btn-success" value="Save">
        <button type="button" class="btn btn-inverse">Cancel</button>
    </div>
</form>
<?php echo $error ?? ''; ?>

                                    </div>
                                </div>
                            </div>



        </div>
     
    </div>
   

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

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
    <script>
        $('textarea#summernote').summernote({
            placeholder: 'Hello bootstrap 4',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                //['fontname', ['fontname']],
                // ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                //['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ],
        });
    </script>
<footer class="footer"> © All rights reserved. </footer>
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