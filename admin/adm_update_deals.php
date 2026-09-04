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
   include("../dbconnect.php");
   $pr_id=$_GET['menu_upd'];
   $sel=mysqli_query($con,"SELECT * FROM dishes inner join deals_prot on dishes.rs_id=deals_prot.d_id where deals_prot.d_id='$pr_id'");
   // $sel=mysqli_query($con,"SELECT * FROM deals_prot where d_id='$pr_id'");
   if($sel1=mysqli_fetch_array($sel)){
    $pr_name=$sel1['dish_name'];
    $pr_cat=$sel1['category'];
    $actual_pri=$sel1['actual_pri'];
    $dis_pri=$sel1['dis_pri'];
    $discount=$sel1['discount'];
    $exp_date=$sel1['exp_date'];
    $quantity=$sel1['quantity'];
    $wg=$sel1['wg'];
    $gst=$sel1['gst'];
    $gd_id=$sel1['d_id'];
    $deals_id=$sel1['deals_id'];
    $current_time=$sel1['current_time'];
    $modify_time=$sel1['modify_time'];
   }
   //error_reporting(0);
   ?>
<!DOCTYPE html>
<html lang="en">
   <?php include "head.php"; ?>
   <body class="fix-header">
      <!-- Main wrapper  -->
      <div id="main-wrapper">
         <?php include "navbar.php"; ?>
         <?php include "sidebar1.php"; ?>
         <div class="page-wrapper" style="height:1200px;">
            <!-- Bread crumb -->
            <div class="container-fluid">
               <div class="col-lg-12">
                  <div class="card card-outline-primary">
                     <div class="card-header">
                        <h4 class="m-b-0 text-white">Add Product</h4>
                     </div>
                     <div class="card-body">
                        <form action='update_deals1.php' method='POST' enctype="multipart/form-data">
                           <input type="hidden" name="dd_id" value="<?php echo $deals_id; ?>">
                           <div class="form-body">
                              <hr>
                              <div class="row p-t-20">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Select product</label>
                                       <select name="dishe" class="form-control custom-select" required
                                          data-placeholder="Choose a Category" tabindex="1">
                                          <option value="<?php echo $gd_id; ?>"><?php echo $pr_name;  ?></option>
                                          <?php $ssql = "select * from dishes where status='1'";
                                             $res = mysqli_query($con, $ssql);
                                             while ($row = mysqli_fetch_array($res)) {
                                                 echo ' <option value="' . $row['rs_id'] . '">' . $row['dish_name'] . '</option>';;
                                             }
                                             ?>
                                       </select>
                                    </div>
                                 </div>
                                 <!--/span-->
                                 <div class="col-md-6">
                                    <div class="form-group">
                                    <input type='hidden' name='current_date' value='<?php echo $current_time; ?>'>
                                    <input type='hidden' name='modify_date' value='<?php echo $modify_time; ?>' >
                                    <label class="control-label">Expired On</label>
                                   <select class="form-control" name='exp_date' onchange="updateDates()" required>
                                   <option value='<?php echo $exp_date; ?>'><?php echo $exp_date; ?> day's</option>
                                    <option value='1'>1 day's</option>
                                    <option value='2'>2 day's</option>
                                    <option value='3'>3 day's</option>
                                    <option value='4'>4 day's</option>
                                    <option value='5'>5 day's</option>
                                   </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Actual Price</label>
                                       
                                       <input type="text" name="ac_price" class="form-control"
                                          placeholder="Enter Actual Price" value='<?php echo $actual_pri; ?>'  oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label class="control-label">Discount Price</label>
                                       <input type="text" name="dis_price" value='<?php echo $dis_pri; ?>' class="form-control"
                                          placeholder="Enter Discount Price" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                    </div>
                                 </div>
                              </div>
                              <!--/span-->
                           </div>
                           <div class="row">
                              
                              <div class="col-md-6">
                                 <div class="form-group has-danger">
                                    <label class="control-label">Discount (%)</label>
                                    <input name="discount" value='<?php echo $discount; ?>' type='text' oninput="this.value = this.value.replace(/[^0-9]/g, '');"  class="form-control form-control-danger"
                                       placeholder="Enter Discount Percentage"
                                       required>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group has-danger">
                                    <label class="control-label">GST (%)</label>
                                    <input name="gst" type='number' oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?php echo  $gst; ?>"  class="form-control form-control-danger"
                                       placeholder="Enter GST  Percentage"
                                       required>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group has-danger">
                                            <label class="control-label">Quantity</label>
                                            <input name="qty" type='number'value="<?php echo  $quantity; ?>"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                class="form-control form-control-danger" placeholder="Enter Quantity"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-danger">
                                            <label class="control-label">Weight</label>
                                            <select class="form-control" name="weight" required>
                                            <option value="<?php echo $wg; ?>"><?php echo $wg; ?></option>
                                                <option value="gram">Gram</option>
                                                <option value="kg">Kilogram</option>
                                                <option value="packet">Packet</option>
                                                <option value="packet">Box</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                     </div>
                  </div>
                  <!--/span-->
                  <div class="form-actions ml-3">
                  <input type="submit" name="submit" class="btn btn-success" value="Update">
                  <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                  </div>
               </div>
            </div>
         </div>
         </form>
      </div>
      </div>
      </div>
      </div>
      <script>
        function updateDates() {
            var currentDateInput = document.getElementsByName("current_date")[0];
            var modifyDateInput = document.getElementsByName("modify_date")[0];
            var expDateSelect = document.getElementsByName("exp_date")[0];

            var currentDate = new Date();
            currentDateInput.value = currentDate.toLocaleString();

            var modifyDate = new Date(currentDate);
            var selectedOptionValue = parseInt(expDateSelect.value);

            if (!isNaN(selectedOptionValue) && selectedOptionValue > 0) {
                modifyDate.setDate(modifyDate.getDate() + selectedOptionValue);
                modifyDateInput.value = modifyDate.toLocaleString();
            } else {
                modifyDateInput.value = "Invalid option selected";
            }
        }
    </script>
      <!-- End PAge Content -->
      </div>
      <!-- End Page wrapper  -->
      </div>
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