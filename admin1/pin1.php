<!DOCTYPE html>
<html lang="en">
<?php
include ("../config.php");
session_start();

if (isset($_POST['submit'])) {
    $price = $_POST["price"];
    $id = $_POST["id"];

    // Perform additional validation if needed
    if (empty($price)) {
        echo "Shipping amount is required.";
    } else {
        // Escape variables to prevent SQL injection
        $d_name = mysqli_real_escape_string($con, $_POST['d_name']);
        $about = mysqli_real_escape_string($con, $_POST['about']);
        $state = mysqli_real_escape_string($con, $_POST['state']);
        $circle = mysqli_real_escape_string($con, $_POST['circle']);
        $taluk = mysqli_real_escape_string($con, $_POST['taluk']);
        $division = mysqli_real_escape_string($con, $_POST['division']);
        $price = mysqli_real_escape_string($con, $_POST['price']);

        // Update data in the `pin` table
        $sql = "UPDATE `pin` SET 
                `pin`='$d_name', 
                `dist`='$about', 
                `state`='$state', 
                `circle`='$circle', 
                `taluk`='$taluk', 
                `division`='$division', 
                `price`='$price' 
                WHERE `id`='$id'";

        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Shipping Amount updated');window.location.href='pinsave.php';</script>";
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    }
}
?>
<?php include "head.php"; ?>

<body class="fix-header">
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>

        <div class="page-wrapper" style="height:1200px;">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-primary">Dashboard</h3>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Update Shipping Amount</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <?php
                                    // Fetch data from `pin` table based on `id` from GET parameter
                                    $pinId = $_GET['pin'];
                                    $sql = "SELECT * FROM `pin` WHERE `id`='$pinId'";
                                    $result = mysqli_query($con, $sql);
                                    $row = mysqli_fetch_array($result);
                                    ?>

                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>"
                                                class="form-control" readonly>
                                            <div class="form-group">
                                                <label class="control-label">Pincode</label>
                                                <input type="text" name="d_name" value="<?php echo $row['pin']; ?>"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Country</label>
                                                <input type="text" name="taluk" value="<?php echo $row['taluk']; ?>"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">State</label>
                                                <input type="text" name="state" value="<?php echo $row['state']; ?>"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Sub state</label>
                                                <input type="text" name="about" value="<?php echo $row['dist']; ?>"
                                                    class="form-control form-control-danger" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Region</label>
                                                <input type="text" name="circle" value="<?php echo $row['circle']; ?>"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">City</label>
                                                <input type="text" name="division"
                                                    value="<?php echo $row['division']; ?>" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Amount</label>
                                                <input type="text" name="price" value="<?php echo $row['price']; ?>"
                                                    class="form-control" placeholder="Enter Amount" required>
                                            </div>
                                        </div>
                                    </div>

                                </div>
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
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>