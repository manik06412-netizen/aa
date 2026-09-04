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
include("../dbconnect.php");
//error_reporting(0);
if(isset($_POST['submit'])) 
{
	
	
		if(empty($_POST['d_name'])||empty($_POST['about'])||$_POST['price']==''||$_POST['qn']==''||$_POST['weight']=='')
		{	
								echo"<script>alert('All fields must be filled');window.location.href='update_pp.php';</script>";	
					
		}
	else
		{
			$prd=$_POST['about'];
			$check_cat = mysqli_query($con, "SELECT * FROM price WHERE wg = '".$_POST['weight']."'  AND qn='".$_POST['qn']."' AND pcode='".$_POST['about']."' AND id!='".$_POST['d']."' ");

			if(mysqli_num_rows($check_cat) > 0)
			{
			   echo "<script>alert('Price Already registered in this quantity And item!'); window.location.href='add_pp.php';</script>";
			  
			}else{
		
            $sql = "update price set oprice='$_POST[oprice]', pp='$_POST[price]',qn='$_POST[qn]',wg='$_POST[weight]',gst='$_POST[gst]' ,discount='$_POST[dis]',total_stock='$_POST[tprice]' where id='$_POST[d]' ";  
					mysqli_query($con, $sql); 
					
			  
					echo "<script>alert('updated successfully');window.location.href='add_pp1.php?menu_del=" . $prd . "';</script>";

	
										} }
					}
	   
	   


?>

<!DOCTYPE html>
<html lang="en">
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
                            <h4 class="m-b-0 text-white">Update Product Price</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <?php $qml ="select * from price where id='$_GET[menu_upd]'";
													$rest=mysqli_query($con, $qml); 
													$roww=mysqli_fetch_array($rest);
														?>
                                <hr>
                                <h3 style="text-align:center"><b>Product Name:</b> <?php echo $roww['pname']; ?></h3>
                                <input type="hidden" name="d" value="<?php echo $roww['id'];?>" class="form-control"
                                    placeholder="Product name" readonly>
                                <input type="hidden" name="d_name" value="<?php echo $roww['pname'];?>"
                                    class="form-control" placeholder="Product name" readonly>
                                <input type="hidden" name="about" value="<?php echo $roww['pcode'];?>"
                                    class="form-control form-control-danger" placeholder="Product code" readonly>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Quantity </label>
                                            <input type="number" min="1" name="qn" value="<?php echo $roww['qn'];?>"
                                                class="form-control" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Weight</label>
                                            <select class="form-control" name="weight" required>

                                                <option value="<?php echo $roww['wg']; ?>" selected>

                                                    <?php echo $roww['wg']; ?></option>


                                                <?php
                                                   $ssql = "SELECT * FROM btype";
                                                   $res = $con->query($ssql);
                                                   while ($row = $res->fetch_assoc()) {
                                                         echo '<option value="' . htmlspecialchars($row['bname']) . '">' . htmlspecialchars($row['bname']) . '</option>';
                                                   }
                                                   ?>

                                            </select>
                                        </div>
                                    </div>
                                    <script>
                                    function calculateSellingPrice() {
                                        // Get values from input fields
                                        var actualPrice = parseFloat(document.querySelector('input[name="oprice"]')
                                            .value) || 0;
                                        var discount = parseFloat(document.querySelector('input[name="dis"]').value) ||
                                            0;

                                        // Calculate selling price
                                        var sellingPrice = actualPrice - (actualPrice * discount / 100);

                                        // Update the selling price input field with rounded value
                                        document.querySelector('input[name="price"]').value = Math.round(sellingPrice);
                                    }

                                    // Add event listeners to input fields
                                    window.onload = function() {
                                        document.querySelector('input[name="oprice"]').addEventListener('input',
                                            calculateSellingPrice);
                                        document.querySelector('input[name="dis"]').addEventListener('input',
                                            calculateSellingPrice);
                                    };
                                    </script>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price</label>
                                            <input type="text" name="oprice"
                                                value="<?php echo htmlspecialchars($roww['oprice']); ?>"
                                                class="form-control" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Discount (%) </label>
                                            <input type="number" min="1" name="dis"
                                                value="<?php echo htmlspecialchars($roww['discount']); ?>"
                                                class="form-control" placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Selling Price (With Discount)</label>
                                            <input type="text" name="price"
                                                value="<?php echo htmlspecialchars($roww['pp']); ?>"
                                                class="form-control" placeholder="" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">GST % (Optional) </label>
                                            <input type="number" min="1" name="gst" value="<?php echo $roww['gst'];?>"
                                                class="form-control" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Available Quantity</label>
                                            <input type="text" min="1" name="tprice"
                                                value="<?php echo $roww['total_stock'];?>" class="form-control"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                                <!-- /row-->
                                <div class="row">





                                </div>
                                <div class="form-actions ml-3">

                                    <input type="submit" name="submit" class="btn btn-success" value="UPDATE">

                                </div>
                                <!-- /row-->
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