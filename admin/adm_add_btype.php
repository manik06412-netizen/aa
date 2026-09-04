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
		
	$check_cat= mysqli_query($con, "SELECT bname FROM btype where bname = '".$_POST['c_name']."' ");

	
	
	if(mysqli_num_rows($check_cat) > 0)
     {
    	$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Category already exist!</strong>
															</div>';
     }
	else{
       
      
                                  
	$mql = "INSERT INTO btype(bname) VALUES('".$_POST['c_name']."')";
	mysqli_query($con, $mql);
			$success = 	'<div class="alert alert-success alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Congrats!</strong> New Type Added Successfully.</br></div>';
	
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
                                    <h4 class="m-b-0 text-white">Add Measurement's</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">

                                            <hr>
                                            <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group">
                <label for="c_name">Measurement Type:</label>
                <input type="text" id="c_name" name="c_name"  required>&nbsp;&nbsp;
                
                    <input type="submit" name="submit" class="btn btn-success btn-small" value="Add">
                
            </div>
        </div>
    </div>
</div>

                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-12">


                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Listed Measurement</h4>

                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Business Type</th>
                                                                                             <th>Action</th>

                                            </tr>
                                        </thead>                            <tbody>


                                            <?php
												$sql="SELECT * FROM btype order by id desc";
												$query=mysqli_query($con,$sql);
												
													if(!mysqli_num_rows($query) > 0 )
														{
															echo '<td colspan="7"><center>No Categories-Data!</center></td>';
														}
													else
														{		
                                                            $i=1;		
																	while($rows=mysqli_fetch_array($query))
																		{
																					
																				
																				
                                                                            echo '<tr>
                                                                            <td>' . $i++ . '</td>
                                                                            <td>' . $rows['bname'] . '</td>
                                                                           
                                                                          
                                                                            <td>
                                                                                <a href="#" onclick="confirmDelete(' . $rows['id'] . ')"
                                                                                   class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                                    <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                                </a>
                                                                                
                                                                            </td>
                                                                          </tr>';
                                                                    
																					 
																						
																						
																		}	
														}
												
											
											?>


                                            <script>
                                            function confirmDelete(categoryId) {
                                                var confirmDelete = confirm(
                                                    "Are you sure you want to delete this Measurement?");
                                                if (confirmDelete) {
                                                    window.location.href = 'delete_btype.php?cat_del=' + categoryId;
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