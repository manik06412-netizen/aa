<?php
include("../dbconnect.php");
session_start();
error_reporting(0);

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['c_name'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Field Required!</strong>
                  </div>';
    } else {
        $c_name = mysqli_real_escape_string($con, $_POST['c_name']);
        $check_cat = mysqli_query($con, "SELECT c_name FROM res_category WHERE c_name = '$c_name'");

        if (mysqli_num_rows($check_cat) > 0) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Category already exists!</strong>
                      </div>';
        } else {
            $allowed_extensions = array('jpg', 'jpeg', 'png');

            // Handle first image upload
            if (isset($_FILES['images']) && $_FILES['images']['error'] == 0) {
                $fname = $_FILES['images']['name'];
                $temp = $_FILES['images']['tmp_name'];
                $extension = pathinfo($fname, PATHINFO_EXTENSION);
                $fnew = uniqid() . '.' . $extension;
                $store = "Res_img/dishes/" . basename($fnew);

                if (in_array($extension, $allowed_extensions) && move_uploaded_file($temp, $store)) {
                    $store1 = $store;
                } else {
                    $error = '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Invalid file type or error uploading first image.</strong>
                              </div>';
                }
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>No file uploaded for the first image.</strong>
                          </div>';
            }

            // Handle second image upload
            if (isset($_FILES['images1']) && $_FILES['images1']['error'] == 0) {
                $fname1 = $_FILES['images1']['name'];
                $temp1 = $_FILES['images1']['tmp_name'];
                $extension1 = pathinfo($fname1, PATHINFO_EXTENSION);
                $fnew1 = uniqid() . '.' . $extension1;
                $store1 = "Res_img/dishes/" . basename($fnew1);

                if (in_array($extension1, $allowed_extensions) && move_uploaded_file($temp1, $store1)) {
                    // Second image uploaded successfully
                } else {
                    $error = '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Invalid file type or error uploading second image.</strong>
                              </div>';
                }
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>No file uploaded for the second image.</strong>
                          </div>';
            }

            // Insert data into the database if there are no errors
            if (empty($error)) {
                $mql = "INSERT INTO res_category(c_name, k1, k2, fpath, icon, orderr) VALUES('$c_name', '" . mysqli_real_escape_string($con, $_POST['k1']) . "', '" . mysqli_real_escape_string($con, $_POST['k3']) . "', '$store', '$store1', '" . mysqli_real_escape_string($con, $_POST['order']) . "')";
                if (mysqli_query($con, $mql)) {
                    $success = '<div class="alert alert-success alert-dismissible fade show">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                                  <strong>Congrats!</strong> New Category Added Successfully.
                                </div>';
                } else {
                    $error = '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Error adding category to the database.</strong>
                              </div>';
                }
            }
        }
    }
}

include("head.php");
?>

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
                                    <h4 class="m-b-0 text-white">Add Category</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">

                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Category</label>
                                                        <input type="text" name="c_name" class="form-control"
                                                            placeholder="Category Name" required>
                                                    </div>
                                                   
                                                    <div class="form-group">
                                                        <label class="control-label">Keyword 1</label>
                                                        <input type="text" name="k1" class="form-control"
                                                            placeholder="Keyword" required>

                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-group">
                                                        <label class="control-label">Order</label>
                                                        <input type="number" name="order" class="form-control" min="1"
                                                            placeholder="List Order" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="control-label">Keyword 2</label>
                                                        <input type="text" name="k3" class="form-control"
                                                            placeholder="Keyword" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Upload Image</label>
                                                        <input type="file" name="images" class="form-control"
                                                            placeholder="images" required>
                                                    </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Upload Icon</label>
                                                        <input type="file" name="images1" class="form-control"
                                                            required>
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
                                <h4 class="card-title">Listed Categories</h4>

                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Category Name</th>
                                                <th>Image</th>
                                                <th>Date</th>

                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>


                                            <?php
												$sql="SELECT * FROM res_category order by c_id desc";
												$query=mysqli_query($con,$sql);
												
													if(!mysqli_num_rows($query) > 0 )
														{
															echo '<td colspan="7"><center>No Categories-Data!</center></td>';
														}
													else
														{				
																	while($rows=mysqli_fetch_array($query))
																		{
																					
																				
																				
                                                                            echo '<tr>
                                                                            <td>' . $rows['c_id'] . '</td>
                                                                            <td>' . $rows['c_name'] . '</td>
                                                                            <td>
                                                                                <div class="col-md-3 col-lg-8 m-b-10">
                                                                                    <center>
                                                                                        <img src="' . $rows['fpath'] . '" class="img-responsive radius" style="max-height:100px;max-width:150px;" />
                                                                                    </center>
                                                                                </div>
                                                                            </td>
                                                                            <td>' . $rows['date'] . '</td>
                                                                            <td>
                                                                                <a href="#" onclick="confirmDelete(' . $rows['c_id'] . ')"
                                                                                   class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                                    <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                                </a>
                                                                                <a href="update_category.php?cat_upd=' . $rows['c_id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
                                                                                    <i class="ti-settings"></i>
                                                                                </a>
                                                                            </td>
                                                                          </tr>';
                                                                    
																					 
																						
																						
																		}	
														}
												
											
											?>


<script>
    function confirmDelete(categoryId) {
        var confirmDelete = confirm("Are you sure you want to delete this category?");
        if (confirmDelete) {
            window.location.href = 'delete_category.php?cat_del=' + categoryId;
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
            <footer class="footer"> ©  All rights reserved. </footer>
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