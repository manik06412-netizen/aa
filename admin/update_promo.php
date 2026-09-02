<!DOCTYPE html>
<html lang="en">
<?php
include("../config.php");
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
        $date = date("D M d Y");
	
	$mql = "update promo set code ='$_POST[c_name]',discount='$_POST[k1]',sdat='$_POST[k2]',edat='$_POST[k3]',purpose='$_POST[pur]',
    dat='$date' where id='$_GET[cat_upd]'";
	mysqli_query($con, $mql);
			

// Redirect to addcategory.php using JavaScript
echo '<script>alert("updated successfully");window.location.href = "coupon.php";</script>';
    
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
                                    <h4 class="m-b-0 text-white">Update Coupon code</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <?php $ssql ="select * from promo where id='$_GET[cat_upd]'";
													$res=mysqli_query($con, $ssql); 
													$row=mysqli_fetch_array($res);
                                                    //$_SESSION['f']=$row['fpath'];?>
                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Coupon Code</label>
                                                        <input type="text" name="c_name" id="promoCode"
                                                            class="form-control" maxlength="8" value="<?php echo $row['code'];  ?>" 
                                                            placeholder="Click To Generate PromoCode">

                                                        <button type="button" class="btn btn-info"
                                                            onclick="generatePromoCode()">Generate</button>
                                                        
                                                    </div>
                                                    <script>
                                                function generatePromoCode() {
                                                    // Length of the generated code
                                                    var codeLength = 8;

                                                    // Characters to be used in the code
                                                    var characters =
                                                        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                                                    var generatedCode = '';

                                                    for (var i = 0; i < codeLength; i++) {
                                                        var randomIndex = Math.floor(Math.random() * characters.length);
                                                        generatedCode += characters.charAt(randomIndex);
                                                    }

                                                    // Set the generated code to the input field
                                                    document.getElementById('promoCode').value = generatedCode;
                                                }
                                                </script>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Discount</label>
                                                        <input type="text"  name="k1" class="form-control" value="<?php echo $row['discount'];  ?>" maxlength="8" min="1" placeholder="Enter Discount Amount" required>
                                                       


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-t-20">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Purpose</label>
                                                        <textarea name="pur" class="form-control" placeholder="Purpose"><?php echo $row['purpose']; ?></textarea>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">start date</label>
                                                        <input type="date" name="k2" value="<?php echo $row['sdat']; ?>"
                                                            class="form-control" placeholder="Update ValidityDate"
                                                            >
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">End date</label>
                                                        <input type="date" name="k3" value="<?php echo $row['edat']; ?>"
                                                            min="<?php echo date('Y-m-d'); ?>" class="form-control"
                                                            placeholder="Update Validity Date">
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-12">



                                            </div>

                                        </div>
                                        <!--/span-->

                                </div>
                                <div class="form-actions">
                                    <input type="submit" name="submit" class="btn btn-success" value="save">
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