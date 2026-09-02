<?php
session_start();
include("../dbconnect.php");
error_reporting(E_ALL); 
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    $errors = [];
    $uploads = ['images1', 'images2', 'images3'];
    $uploadedFiles = [];

    // Retrieve old images from the database
    $id = $_POST['id'];
    $query = "SELECT img, img2, img3 FROM dishes WHERE d_id='$id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);

    // Prepare old image paths
    $oldImages = [
        'images1' => $row['img'],
        'images2' => $row['img2'],
        'images3' => $row['img3']
    ];

    foreach ($uploads as $key => $fileInput) {
        if (!empty($_FILES[$fileInput]['name'])) {
            $fname = $_FILES[$fileInput]['name'];
            $temp = $_FILES[$fileInput]['tmp_name'];
            $extension = pathinfo($fname, PATHINFO_EXTENSION);
            $fnew = uniqid() . '.' . $extension;
            $storePath = "Res_img/dishes/" . basename($fnew);

            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                if (move_uploaded_file($temp, $storePath)) {
                    $uploadedFiles[$fileInput] = $storePath;
                } else {
                    
                    $errors[] = "Error uploading file $fileInput.";
                }
            } else {
                $errors[] = "Invalid file type for $fileInput. Allowed types are jpg, jpeg, png.";
            }
        } else {
            // If no file is uploaded, retain the old image
            $uploadedFiles[$fileInput] = $oldImages[$fileInput] ?? null;
        }
    }

    if (empty($errors)) {
        $updateQuery = "UPDATE dishes SET
            img = '" . mysqli_real_escape_string($con, $uploadedFiles['images1']) . "',
            img2 = '" . mysqli_real_escape_string($con, $uploadedFiles['images2']) . "',
            img3 = '" . mysqli_real_escape_string($con, $uploadedFiles['images3']) . "'
            WHERE d_id = '$id'";

        if (mysqli_query($con, $updateQuery)) {
            echo "<script>alert('Product Updated Successfully');window.location.href='update_product_image.php';</script>";
        } else {
            $errors[] = "Error updating record: " . mysqli_error($con);
        }
    }

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
    <title>Update Product</title>
    <?php include "head.php"; ?>
</head>
<body class="fix-header fix-sidebar">
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <?php
                    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>' . htmlspecialchars($error) . '</strong>
                </div>';
        }
    }
    ?>
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Update Product</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <?php
                                $id = $_GET['menu_upd'] ?? '';
                                $query = "SELECT * FROM dishes WHERE d_id='$id'";
                                $result = mysqli_query($con, $query);
                                $row = mysqli_fetch_array($result);
                                $_SESSION['f3'] = $row['img'];
                                $_SESSION['f1'] = $row['img2'];
                                $_SESSION['f2'] = $row['img3'];
                                ?>
                                
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['d_id']); ?>">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['dish_name']); ?>" readonly>
                                </div>
                                <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label>Upload Image1</label>
                                    <input type="file" class="form-control" name="images1">
                                   
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                   
                                    <?php if ($row['img']) echo "<img src='{$row['img']}' width='80px' height='80px'>"; ?>
                                </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label>Upload Image2</label>
                                    <input type="file" class="form-control" name="images2">
                                  
                                </div></div>

                                <div class="col-md-6">
                                <div class="form-group">
                                   
                                    <?php if ($row['img2']) echo "<img src='{$row['img2']}' width='80px' height='80px'>"; ?>
                                </div></div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label>Upload Image3</label>
                                    <input type="file" class="form-control" name="images3">
                                   
                                </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                   
                                    <?php if ($row['img3']) echo "<img src='{$row['img3']}' width='80px' height='80px'>"; ?>
                                </div>
                                </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" name="submit" class="btn btn-success" value="Save">
                                    <button type="button" class="btn btn-inverse">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
    </div>
</body>
</html>
