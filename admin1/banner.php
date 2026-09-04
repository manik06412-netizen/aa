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
		
	$check_cat= mysqli_query($con, "SELECT k1 FROM banner where k1 = '".$_POST['c_name']."' ");

	
	
	if(mysqli_num_rows($check_cat) > 0)
     {
    	$error = '<div class="alert alert-danger alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Category already exist!</strong>
															</div>';
     }
	else{
       
        
        $fname = $_FILES['images']['name'];
        $temp = $_FILES['images']['tmp_name'];
        $fsize = $_FILES['images']['size'];
        $extension = pathinfo($fname, PATHINFO_EXTENSION);  // Get the file extension
        $fnew = uniqid() . '.' . $extension;
        
        
        
        $store = "Res_img/dishes/" . basename($fnew);
        
        
            if (move_uploaded_file($temp, $store)) {
                // File uploaded successfully
                //echo "File uploaded successfully.";
            } else {
                // Handle upload failure
                //echo "Error uploading file.";
            }
        } 
    
                          
	$mql = "INSERT INTO banner VALUES(null,'".$_POST['c_name']."','".$_POST['k1']."','".$_POST['k2']."','" . $store . "','".$_POST['k3']."')";
	mysqli_query($con, $mql);
			$success = 	'<div class="alert alert-success alert-dismissible fade show">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<strong>Congrats!</strong> New Banner Added Successfully.</br></div>';
	
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
                                    <h4 class="m-b-0 text-white">Add Banner</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">

                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Title</label>
                                                        <input type="text" name="c_name" class="form-control"
                                                            placeholder="Enter Title" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Heading</label>
                                                        <input type="text" name="k1" class="form-control"
                                                            placeholder="Enter Heading" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Slogan</label>
                                                        <input type="text" name="k2" class="form-control"
                                                            placeholder="Enter Slogan" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Link</label>
                                                        <input type="text" name="k3" class="form-control"
                                                            placeholder="Link" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Upload Image</label>
                                                        <input type="file" name="images" class="form-control"
                                                            placeholder="images" required>
                                                    </div>
                                                </div>


                                                <!--/span-->

                                            </div>
                                            <div class="form-actions">
                                                <input type="submit" name="submit" class="btn btn-success" value="Save">
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
                                <h4 class="card-title">Listed Banner</h4>

                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Title</th>
                                                <th>Heading</th>
                                                <th>Slogan</th>
                                                <th>image</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>


                                            <?php
												$sql="SELECT * FROM banner order by id desc";
												$query=mysqli_query($con,$sql);
												
													if(!mysqli_num_rows($query) > 0 )
														{
															echo '<td colspan="7"><center>No Banner-Data!</center></td>';
														}
													else
														{				
																	while($rows=mysqli_fetch_array($query))
																		{
																					
																				
																				
                                                                            echo '<tr>
                                                                            <td>' . $rows['id'] . '</td>
                                                                            <td>' . $rows['k1'] . '</td>
                                                                            <td>' . $rows['k2'] . '</td>
                                                                            <td>' . $rows['k3'] . '</td>
                                                                            <td>
                                                                                <div class="col-md-3 col-lg-8 m-b-10">
                                                                                    <center>
                                                                                        <img src="' . $rows['fpath'] . '" class="img-responsive radius" style="max-height:100px;max-width:150px;" />
                                                                                    </center>
                                                                                </div>
                                                                            </td>
                                                                           
                                                                            <td>
                                                                                <a href="#" onclick="confirmDelete(' . $rows['id'] . ')"
                                                                                   class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                                    <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                                </a>
                                                                                <a href="update_banner.php?cat_upd=' . $rows['id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
                                                                                    <i class="ti-settings"></i>
                                                                                </a>
                                                                            </td>
                                                                          </tr>';
                                                                    
																					 
																						
																						
																		}	
														}
												
											
											?>


                                            <script>
                                            function confirmDelete(categoryId) {
                                                var confirmDelete = confirm(
                                                    "Are you sure you want to delete this category?");
                                                if (confirmDelete) {
                                                    window.location.href = 'delete_banner.php?cat_del=' + categoryId;
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