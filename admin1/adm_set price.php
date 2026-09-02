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

session_start();



echo "PHP script reached.";
if (isset($_POST['submit'])){

    echo "PHP script reached.";

 
        $additionalQuantity = isset($_POST['additional_input']) ? implode(',', $_POST['additional_input']) : '';
    echo $additionalQuantity;


       $imagePaths = [];

        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
            $fname = $_FILES['images']['name'][$i];
            $temp = $_FILES['images']['tmp_name'][$i];
            $fsize = $_FILES['images']['size'][$i];
            $extension = explode('.', $fname);
            $extension = strtolower(end($extension));
            $fnew = uniqid() . '.' . $extension;
        
            $store = "Res_img/dishes/" . basename($fnew);
        
            if (move_uploaded_file($temp, $store)) {
                array_push($imagePaths, $store);
            } else {
                echo $error='<div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Congrass!</strong> New Dish Added Successfully.
            </div>';
            }
        }                  





                
     
        $sql = "INSERT INTO dishes(
            dish_name,
        category,
        description,
        price,
        discount,

        Total_Quantity,
        Additional_quantity,
        stock,
        best_before,
        food_type,
        keywords,
        img,
        img2,
        img3,
        img4,
        img5) VALUE('" . $_POST['d_name'] . "',
        '" . $_POST['category'] . "',
        '" . $_POST['price'] . "',
        '" . $_POST['discount'] . "',
              '" . $_POST['about'] . "', 
            '" . $_POST['total_quantity'] . "', 
              '" . $additionalQuantity . "' , 
                '" . $_POST['stock'] . "'       , 
                  '" . $_POST['exp_date'] . "'        , 
                    '" . $_POST['food_type'] . "'        , 
                      '" . $_POST['keywords'] . "',
                      '" . $imagePaths[0] . "', 
                      '" . $imagePaths[1] . "',
                       '" . $imagePaths[2] . "',
                        '" . $imagePaths[3] . "', 
                        '" . $imagePaths[4] . "')"; 
               if( mysqli_query($con, $sql)){
                
                   $success =     '<div class="alert alert-success alert-dismissible fade show">
                                                                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                   <strong>Congrass!</strong> New Dish Added Successfully.
                                                               </div>';
               }else{
                echo "error";
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
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3>
                </div>

            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->


                <?php
                //  echo $error;
                // echo $success;
                 ?>




                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Add Snacks</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">

                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Snack Name</label>
                                                <input type="text" name="d_name" class="form-control" placeholder="Enter snackname">
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Select Category</label>
                                                <select name="category" class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1">
                                                    <option>--Select category--</option>
                                                    <?php $ssql = "select * from res_category";
                                                    $res = mysqli_query($con, $ssql);
                                                    while ($row = mysqli_fetch_array($res)) {
                                                        echo ' <option value="' . $row['c_name'] . '">' . $row['c_name'] . '</option>';;
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">price </label>
                                            <input type="text" name="price" class="form-control" placeholder="inr">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-danger">
                                            <label class="control-label">Discount</label>
                                            <input type="text" name="discount" id="discount" class="form-control form-control-danger" placeholder="Enter Discount price
                                                     ">
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">

                                    <div class="col-md-6">

                                        <div class="form-group has-danger">
                                            <label class="control-label">Enter description</label>
                                            <textarea name="about" class="form-control form-control-danger" rows="8" placeholder="Enter product Description">Enter </textarea>
                                        </div>
                                    </div>


                                    <div class="col">
                                        <div class="form-group">
                                            <label class="control-label">Total Quantity</label>
                                            <input type="text" name="total_quantity" class="form-control" placeholder="Enter Total Quantity">
                                        </div>
                                    </div>
                                                    <div class="col">
                                                        <label for="additional Options">Additional Options</label><br>
                                                    <div class="form-check form-check-inline">

  <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="kg" name="additional_input[]">
  <label class="form-check-label" for="inlineCheckbox1">Kg</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="gm"  name="additional_input[]">
  <label class="form-check-label" for="inlineCheckbox2">Gm</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="pieces"  name="additional_input[]">
  <label class="form-check-label" for="inlineCheckbox3">Pieces</label>
</div>
                                                    </div>



                                </div>
                                    <!--/span-->
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">stock</label>
                                                <input type="stock" name="stock" class="form-control form-control-danger" placeholder="Enter info about stock">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Expiry date</label>
                                                <input type="date" name="exp_date" class="form-control form-control-danger" placeholder="Best Before">
                                            </div>
                                        </div>
                                    </div>
                                                    <div class="row">

                                                        <div class="col">
                                                            <label for="food_type">Food Type</label>
                                                            <div class="form-group has-danger">
                                                                <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="food_type" id="inlineRadio1" value="veg">
                                                <label class="form-check-label" for="inlineRadio1" style="color: green;">Veg</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="food_type" id="inlineRadio2" value="NON-Veg">
                                                <label class="form-check-label" for="inlineRadio2" style="color: red;">NON-Veg</label>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    <div class="col">
                                        <label for="keywordInput">Keywords:</label>
                                        <input type="text" class="form-control" id="keywordInput" name="keywords" placeholder="Enter keywords separated by commas">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                            <div class="col">
                                <label for="imageUpload">Select images:</label>
                                <div class="form-group has-danger">
                                    <input class='m-2' type="file" class="form-control-file" id="imageUpload" name="images[]" >
                                    <input class='m-2' type="file" class="form-control-file" id="imageUpload" name="images[]" >
                                    <input class='m-2' type="file" class="form-control-file" id="imageUpload" name="images[]" >
                                    <input class='m-2' type="file" class="form-control-file" id="imageUpload" name="images[]" >

                                    <input class='m-2' type="file" class="form-control-file" id="imageUpload" name="images[]" >
                                </div>
                            </div>
                        </div>

                        </div>
                        <!--/span-->
                       

                        <div class="row">

                        </div>
                        <!--/row-->

                    </div>
            



                    <div class="form-actions ml-3">
                        <input type="submit" name="submit" class="btn btn-success" value="save">
                        <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                    </div>
                 

                    <div class="col">


                    </div>

                </div>
            </div>
            </form>
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