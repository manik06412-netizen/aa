<!DOCTYPE html>
<html lang="en">
<?php
include ("../dbconnect.php");
 error_reporting(0);
session_start();

include("../dbconnect.php");
session_start();

if (isset($_POST['submit'])) {
    if (empty($_POST['c_name'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>Field Required!</strong>
                  </div>';
    } else {
        $c_name = mysqli_real_escape_string($con, $_POST['c_name']);
        $images1 = mysqli_real_escape_string($con, $_POST['images1']);
        
        // Check if category already exists
        // Uncomment and adjust the query if needed
        // $check_cat = mysqli_query($con, "SELECT bname FROM btype WHERE bname = '$c_name'");
        // if (mysqli_num_rows($check_cat) > 0) {
        //     $error = '<div class="alert alert-danger alert-dismissible fade show">
        //                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        //                   <strong>Category already exists!</strong>
        //               </div>';
        // } else {
            $mql = "INSERT INTO faq(qns, ans) VALUES('$c_name', '$images1')";
            mysqli_query($con, $mql);

          
            echo "<script>alert('New Question Added Successfully');window.location.href='qa.php';</script>";
            exit;
        // }
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
        <div class="page-wrapper" >
            <!-- Bread crumb -->

            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->

                <div class="row">

                    <div class="container-fluid">
                        <!-- Start Page Content -->


                       




                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Add Frequently Asked Question</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">

                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Question</label>
                                                        <input type="text" name="c_name" class="form-control"                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Answer</label>
                                                        <textarea name="images1" rows="3" class="form-control" style="height:auto;" required></textarea>

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
                                <h4 class="card-title">FAQ List</h4>

                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Question</th>
                                                <th>Answer</th>
                                               <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>


                                            <?php
												$sql="SELECT * FROM faq order by id desc";
												$query=mysqli_query($con,$sql);
												
													if(!mysqli_num_rows($query) > 0 )
														{
															echo '<td colspan="7"><center>No-Data!</center></td>';
														}
													else
														{				
																	while($rows=mysqli_fetch_array($query))
																		{
																					
																				
																				
                                                                            echo '<tr>
                                                                            <td>' . $rows['id'] . '</td>
                                                                            <td>' . $rows['qns'] . '</td>
                                                                            <td>' . $rows['ans'] . '</td>
                                                                          
                                                                            <td>
                                                                             <a href="update_qa.php?menu_upd='.$rows['id'].'" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5"><i class="ti-settings"></i></a>
																								
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
                                                    "Are you sure you want to delete this FAQ?");
                                                if (confirmDelete) {
                                                    window.location.href = 'delete_qa.php?cat_del=' + categoryId;
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