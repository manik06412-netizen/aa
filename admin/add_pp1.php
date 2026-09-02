<!DOCTYPE html>
<html lang="en">
<?php
      include("../dbconnect.php");
      error_reporting(0);
      session_start();
      if(isset($_POST['submit'])) 
      {
      	
      	
      		if(empty($_POST['d_name'])||$_POST['price']==''||$_POST['oprice']==''||$_POST['qn']==''||$_POST['weight']==''||$_POST['dis']=='')
      		{	
      											$error = 	'<div class="alert alert-danger alert-dismissible fade show">
      																<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      																<strong>All fields Must be Fillup!</strong>
      															</div>';
      									
      					
      		}
      	else
      		{
      		
                  $check_cat = mysqli_query($con, "SELECT pcode,oprice,pp,qn,wg FROM price WHERE pcode = '".$_POST['about']."' AND oprice = '".$_POST['oprice']."' AND pp = '".$_POST['price']."' AND qn = '".$_POST['qn']."' AND wg = '".$_POST['weight']."' ");
      
      	         if(mysqli_num_rows($check_cat) > 0)
                   {
                      $error = '<div class="alert alert-danger alert-dismissible fade show">
                                                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                              <strong>Price Already registered in this quantity And product!</strong>
                                                                          </div>';
                   }
                  else{
      
      			   	$sql = "insert into price values (null,'$_POST[about]','$_POST[d_name]',$_POST[oprice],$_POST[price],'$_POST[qn]','$_POST[weight]','$_POST[gst]',$_POST[stock],$_POST[dis]) ";  // update the submited data ino the database :images
      					mysqli_query($con, $sql); 
      					move_uploaded_file($temp, $store);
      			  
      					$success = 	'<div class="alert alert-success alert-dismissible fade show">
      									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      									<strong>Record</strong>Inserted.
      								</div>';
      	
      										}
      					}}
      	   
      	   
      ?>
<?php include "head.php"; ?>
<script>
    function calculateSellingPrice() {
        // Get values from input fields
        var actualPrice = parseFloat(document.querySelector('input[name="oprice"]').value) || 0;
        var discount = parseFloat(document.querySelector('input[name="dis"]').value) || 0;

        // Calculate selling price
        var sellingPrice = actualPrice - (actualPrice * discount / 100);

        // Update the selling price input field with rounded value
        document.querySelector('input[name="price"]').value = Math.round(sellingPrice);
    }

    // Add event listeners to input fields
    window.onload = function() {
        document.querySelector('input[name="oprice"]').addEventListener('input', calculateSellingPrice);
        document.querySelector('input[name="dis"]').addEventListener('input', calculateSellingPrice);
    };
</script>

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
                            <h4 class="m-b-0 text-white">Add Product Price</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php $qml ="select * from dishes where rs_id='$_GET[menu_del]'";
                                 $rest=mysqli_query($con, $qml); 
                                 $roww=mysqli_fetch_array($rest);
                                $prd_code= $roww['rs_id'];
                                 	?>
                                    <hr>

                                    <input type="hidden" name="d_name" value="<?php echo $roww['dish_name'];?>"
                                        class="form-control" placeholder="Product name" readonly>
                                    <input type="hidden" name="about" value="<?php echo $roww['rs_id'];?>"
                                        class="form-control form-control-danger" placeholder="Product code" readonly>
                                    <h3 style="text-align:center"><b>Product Name:</b> <?php echo $roww['dish_name']; ?>
                                    </h3>

                                    <!--/row-->
                                    <div class="row p-t-20">
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Quantity Per Product</label>
                                                <input type="number" name="qn" min="1" class="form-control"
                                                    placeholder="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Weight</label>
                                                <select class="form-control" name="weight" required
                                                    oninvalid="this.setCustomValidity('Please select a weight')"
                                                    oninput="setCustomValidity('')">
                                                    <option value="">--Select Weight--</option>
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

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Actual Price </label>
                                                <input type="number" min="1" name="oprice" class="form-control"
                                                    placeholder="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Discount (%) </label>
                                                <input type="number" min="1" name="dis" value="0" class="form-control"
                                                    placeholder="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Selling Price (With discount) </label>
                                                <input type="number" min="1" name="price" class="form-control"
                                                    placeholder="" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">GST% (optional) </label>
                                                <input type="number" min="1" name="gst" class="form-control"
                                                    placeholder="">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Available Quantity </label>
                                                <input type="number" min="1" name="stock" class="form-control"
                                                    placeholder="" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!--/span-->
                                    <div class="row">
                                    </div>
                                </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" name="submit" class="btn btn-success" value="save">
                            <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="card card-outline-primary">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white"> Price List</h4>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Si.no</th>
                                        <th>Weight</th>
                                        <th>Actual Price (RS)</th>
                                        <th>Selling Price (RS)</th>
                                        <th>GST%</th>
                                        <th>Discount%</th>
                                        <th>Total Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
    $sql = "SELECT * FROM price WHERE pcode='$prd_code' ORDER BY id DESC";
    $query = mysqli_query($con, $sql);

    if (!mysqli_num_rows($query) > 0) {
        echo '<td colspan="11"><center>No product-Data!</center></td>';
    } else {
        $ik = 1;
        while ($rows = mysqli_fetch_array($query)) {
            echo '<tr>
                    <td>' . $ik++ . '</td>
                    <td>' . $rows['qn'] . $rows['wg'] . '</td>
                    <td>' . $rows['oprice'] . '</td>                                                                                      
                    <td>' . $rows['pp'] . '</td>
                    <td>' . $rows['gst'] . '</td>
                    <td>' . $rows['discount'] . '</td>
                    <td>' . $rows['total_stock'] . '</td>
                    <td>
                        <a href="update_pp1.php?menu_upd=' . $rows['id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">Edit PRICE</i></a>
                        <a href="#" onclick="confirmDelete(' . $rows['id'] . ')" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                            <i class="fa fa-trash-o" style="font-size:16px"></i>
                        </a>
                    </td>
                  </tr>';
        }
    }
    ?>
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
    <script>
    function confirmDelete(id) {
        const menu_del = "<?php echo $_GET['menu_del']; ?>"; // Get the menu_del parameter
        if (confirm("Are you sure you want to delete this product?")) {
            window.location.href = "delete_pp1.php?id=" + id + "&menu_del=" + menu_del;
        }
    }
    </script>

</body>

</html>