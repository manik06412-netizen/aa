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


if(isset($_POST['submit'] ))
{
    if(empty($_POST['code']))
		{
			$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>field Required!</strong>
															</div>';
		}
	else
	{
		
	$check_cat= mysqli_query($con, "SELECT code FROM promo where code = '".$_POST['code']."' ");

	
	
	if(mysqli_num_rows($check_cat) > 0)
     {
    	$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Promocode already exist!</strong>
															</div>';
     }
	else{
                          
        $date = date("D M d Y");               
	$mql = "INSERT INTO promo VALUES(null,'".$_POST['code']."','".$_POST['pur']."','".$_POST['sdat']."','".$_POST['edat']."','".$_POST['dis']."','0','" . $date . "')";
	mysqli_query($con, $mql);
			$success = 	'<div class="alert alert-success alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Congrats!</strong> New Promocode Added Successfully.</br></div>';
	
    }
	}

}


?>
<?php include "head.php"; ?>

<body class="fix-header">

    <div id="main-wrapper">
        <!-- header header  -->
        <?php include "navbar.php"; ?>

        <?php include "sidebar1.php"; ?>
        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
        <div class="page-wrapper" style="height:1200px;">
            <!-- Bread crumb -->

            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->

                <div class="row">

                    <div class="container-fluid">
                        <!-- Start Page Content -->


                        <?php  
									        echo $error;
									        echo $success; ?>




                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Add Promo code</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">

                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Promo Code</label>
                                                        <input type="text" name="code" id="promoCode" class="form-control" maxlength="8" placeholder="Click To Generate PromoCode">

                                                        <button type="button" class="btn btn-info"
                                                            onclick="generatePromoCode()">Generate</button>
                                                    </div>
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
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Discount%</label>

                                                        <select name="dis" class="form-control" required>
                                                            <option value="" disabled selected>Select Discount %
                                                            </option>
                                                            <option value="5">5</option>
                                                            <option value="10">10</option>
                                                            <option value="10">20</option>
                                                            <option value="10">30</option>
                                                            <option value="10">40</option>
                                                            <option value="10">50</option>
                                                            <!-- Add more options as needed -->
                                                        </select>


                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row p-t-20">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <?php $date=date("Y-m-d"); ?>
                                                        <label class="control-label">Purpose</label>
                                                        <textarea name="pur" class="form-control" placeholder="Enter Purpose" required></textarea>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">

                                                    <div class="form-group">
                                                        <label class="control-label">Start Date</label>
                                                        <input type="date" name="sdat" class="form-control"
                                                        min="<?php echo date('Y-m-d'); ?>" placeholder="Enter Validy days" value="<?php echo $date; ?>">
                                                    </div>


                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label"> End Date</label>
                                                        <input type="date" name="edat" class="form-control"
                                                        min="<?php echo date('Y-m-d'); ?>" placeholder="Enter Validy days" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--/span-->

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

                    <div class="col-12">


                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Listed Promocode</h4>

                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>PromoCode</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Modify Date</th>

                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>


                                            <?php
												$sql="SELECT * FROM promo order by id desc";
												$query=mysqli_query($con,$sql);
												
													if(!mysqli_num_rows($query) > 0 )
														{
															echo '<td colspan="7"><center>No Promo Code-Data!</center></td>';
														}
													else
														{				
																	while($rows=mysqli_fetch_array($query))
																		{
																					
																			
                                                                         
                                                                            // Assuming $rows['status'] contains the status value (0 or 1)
                                                                            
                                                                            $status = $rows['status'];
                                                                            
                                                                            if ($status == 0) {
                                                                                $buttonClass = 'btn btn-success btn-flat btn-addon btn-sm m-b-10 m-l-5';
                                                                                $buttonText = 'Activate';
                                                                                $buttonLink = 'update_status.php?cat_upd=' . $rows['id'] . '&status=1';
                                                                            } else {
                                                                                $buttonClass = 'btn btn-danger btn-flat btn-addon btn-sm m-b-10 m-l-5';
                                                                                $buttonText = 'Inactivate';
                                                                                $buttonLink = 'update_status.php?cat_upd=' . $rows['id'] . '&status=0';
                                                                            }
                                                                            
                                                                            echo '<tr>
                                                                                <td>' . htmlspecialchars($rows['id']) . '</td>
                                                                                <td>' . htmlspecialchars($rows['code']) . '</td>
                                                                                <td>' . htmlspecialchars($rows['sdat']) . '</td>
                                                                                <td>' . htmlspecialchars($rows['edat']) . '</td>
                                                                                <td>' . htmlspecialchars($rows['dat']) . '</td>
                                                                                <td>
                                                                                    <a href="#" onclick="confirmDelete(' . $rows['id'] . ')" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                                        <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                                    </a>
                                                                                    <a href="update_promo.php?cat_upd=' . htmlspecialchars($rows['id']) . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
                                                                                        <i class="ti-settings"></i>
                                                                                    </a>
                                                                                    <a href="' . htmlspecialchars($buttonLink) . '" class="' . htmlspecialchars($buttonClass) . '">
                                                                                        ' . $buttonText . '
                                                                                    </a>
                                                                                </td>
                                                                            </tr>';
                                                                        }
                                                                    }
                                                                    $current=$rows['sdat'];
                                                                    $end=$rows['edat'];
                                                                            ?>



                                            <script>
                                            function confirmDelete(categoryId) {
                                                var confirmDelete = confirm(
                                                    "Are you sure you want to delete this Promo Code?");
                                                if (confirmDelete) {
                                                    window.location.href = 'delete_promo.php?cat_del=' + categoryId;
                                                } else {
                                                    // Do nothing or handle cancellation
                                                }
                                            }
                                            </script>
                                        </tbody>
                                    </table>
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
    <footer class="footer"> © All rights reserved. </footer>
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