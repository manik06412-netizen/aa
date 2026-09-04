<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);
?>
<?php
// session_start();
include("../dbconnect.php");
//error_reporting(0);
if (isset($_POST['submit'])) {
    // Sanitize and validate input
    $dishName = mysqli_real_escape_string($con, $_POST['d_name']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $subCategory = mysqli_real_escape_string($con, $_POST['sc']);
    // $totalQuantity = intval($_POST['total_quantity']);
    // $actualPrice = floatval($_POST['oprice']);
    // $sellingPrice = floatval($_POST['price']);
    // $discount = intval($_POST['dis']);
    // $quantity = intval($_POST['qn']);
    // $weight = mysqli_real_escape_string($con, $_POST['weight']);
    $about = mysqli_real_escape_string($con, $_POST['about']);
    $stock = mysqli_real_escape_string($con, $_POST['stock']);
    $bname = mysqli_real_escape_string($con, $_POST['brand_name']);
    $boption = mysqli_real_escape_string($con, $_POST['brand_option']);
    $poption = mysqli_real_escape_string($con, $_POST['product_option']);
    $detailed_desc = mysqli_real_escape_string($con, $_POST['detailed_desc']);
    // Generate unique ID and date
    $ran = rand(0, 9999);
    $d = date('d/m/Y');

    // Prepare and execute query to check for existing dish
    $stmt = $con->prepare("SELECT dish_name FROM dishes WHERE dish_name = ? ");
    $stmt->bind_param("s", $dishName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      echo "<script>alert('product already registered');window.location.href='add_menu.php';</script>";
    } else {
        // Retrieve category ID
        $stmt = $con->prepare("SELECT c_id FROM res_category WHERE c_name = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $roww = $result->fetch_assoc();
        $c_id = $roww['c_id'] ?? null;

        // Determine if deals checkbox is checked
        $sta = isset($_POST['deals']) ? '1' : '0';

        // Function to handle multiple select values
        function Sled_values($values) {
            return implode(',', array_map('mysqli_real_escape_string', $values));
        }

       
        // Handle image uploads
        $imagePaths1 = uploadImage('images1');
        $imagePaths2 = uploadImage('images2');
        $imagePaths3 = uploadImage('images3');

        // Prepare and execute query to insert dish
        $stmt = $con->prepare("INSERT INTO dishes (rs_id, dish_name,  img, img2, img3, category, date_of_adding, subcate, cateid,description,stock,brand_name,brand_option,product_option,detailed_desc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?)");
        $stmt->bind_param("isssssssissssss", $ran, $dishName, $imagePaths1, $imagePaths2, $imagePaths3, $category, $d, $subCategory, $c_id,$about,$stock,$bname,$boption,$poption,$detailed_desc);

        if ($stmt->execute() ) {
            echo "<script>alert('Product Registered Successfully');</script>";
        } else {
            echo "<script>window.location.href='add_menu.php';</script>" . $con->error;
        }
    }
}

function uploadImage($inputName) {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileName = $_FILES[$inputName]['name'];
    $fileTmp = $_FILES[$inputName]['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = uniqid() . '.' . $fileExt;
    $uploadDir = "../admin/Res_img/dishes/";
    $store = $uploadDir . basename($newFileName);

    if (in_array($fileExt, $allowedExtensions) && move_uploaded_file($fileTmp, $store)) {
        return $store;
    } else {
        echo "<script>alert('only png and jpg images are allowed');window.location.href='add_menu.php';</script>";
    }
}

function MultiSelect($con, $table, $value) {
    $find = ''; 
    $i = 0;    
    $select = $con->query("SELECT * FROM $table");
    if ($select->num_rows > 0) {
        while ($row = $select->fetch_array()) {
            $find .= '<div class="col-3">
                <input type="checkbox" onchange="SelectPart(\'' . $value . '\')" class="' . $value . '" name="' . $value . '[]" value="' . $row[$value] . '" id="' . $value . $i . '" required>
                <label for="' . $value . $i . '">' . $row[$value] . '</label>
            </div>';
            $i++;
        }
    } else {
        $find = '<div class="col-12">
            <h6>No Data</h6>
        </div>';
    }
    echo '<div class="row">' . $find . '</div>';
}
?>

<html lang="en">
<?php 
include "head.php"; ?>
<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-5.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.4.1/js/bootstrap.min.js"></script>

</head>

<body class="fix-header fix-sidebar">
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><b>Add Product</b></h4>
                                <div class="row">
                                    <div class="col-md-12 add_top_30">
                                        <form action='' method='post' id="myForm" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Product Name</label>
                                                        <input type="text" name="d_name" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Brand Name</label>
                                                        <input type="text" name="brand_name" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Select Category</label>
            <select name="category" class="form-control custom-select" required data-placeholder="Choose a Category">
                <option value="" disabled selected>--Select Category--</option>
                <?php
                $ssql = "SELECT * FROM res_category";
                $res = $con->query($ssql);
                while ($row = $res->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['c_name']) . '">' . htmlspecialchars($row['c_name']) . '</option>';
                }
                ?>
            </select>
        </div>
    </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Sub Category</label>
                                                        <input type="text" name="sc" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Enter product description (minimum 500 characters)</label>
                                                        <textarea name="about" class="form-control form-control-danger" rows="5" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Additional Information (minimum 500 characters)</label>
                                                        <textarea name="stock" rows="7" class="form-control form-control-danger" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Upload Image1</label>
                                                        <input type="file" class="form-control" id="imageUpload" name="images1" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Upload Image2</label>
                                                        <input type="file" class="form-control" id="imageUpload" name="images2" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Upload Image3</label>
                                                        <input type="file" class="form-control" id="imageUpload" name="images3" required>
                                                    </div>
                                                </div>
                                               <div class="col-md-3">
    <div class="form-group">
        <label class="control-label">Is Brand</label><br>
        <input type="radio" name="brand_option" value="active" required> Active &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="brand_option" value="inactive"> Inactive
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label class="control-label">Is Product</label><br>
        <input type="radio" name="product_option" value="active" required> Active &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="product_option" value="inactive"> Inactive
    </div>
</div>
                                            </div>
                                            <div class="row">
                                               <div class="col-lg-12" required>
                                               <label class="control-label">Detailed Description</label>
                                               <textarea id="summernote" name="detailed_desc"></textarea>
                                               </div>
                                            </div>
                                            <div class="form-actions">
                                                <input type="submit" name="submit" class="btn btn-success" value="Save">
                                                <button type="button" class="btn btn-inverse">Cancel</button>
                                            </div>
                                        </form>
                                        <?php echo $error ?? ''; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
            </div>
            <footer class="footer"> © All rights reserved. </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0-beta/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
    <!-- All Jquery -->
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
  $(document).ready(function() {
  $('#summernote').summernote({
    placeholder: 'Enter detailed description',
    tabsize: 2,
    height: 300,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'hr']],
      ['help', ['help']]
    ],
    callbacks: {
      onImageUpload: function(files) {
        uploadImage(files[0]);
      }
    }
  });

  function uploadImage(file) {
    var data = new FormData();
    data.append("file", file);
    $.ajax({
      url: 'upload_image.php', // point this to your image upload PHP script
      cache: false,
      contentType: false,
      processData: false,
      data: data,
      type: "POST",
      success: function(url) {
        $('#summernote').summernote('insertImage', url);
      },
      error: function(data) {
        console.log(data);
      }
    });
  }
});

      
    </script>
     
    </div>
    </div>
    <footer class="footer"> © All rights reserved. </footer>
    <!-- End footer -->
    </div>
    <!-- End Page wrapper  -->
    </div>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</body>

</html>


