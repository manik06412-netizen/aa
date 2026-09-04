<!DOCTYPE html>
<html lang="en">
<?php
include("../config.php");
error_reporting(0);
session_start();
$error = ''; // Initialize $error
$success = ''; // Initialize $success

function getCountries($con) {
    $countries = [];
    $query = "SELECT id, country_name FROM countries";
    $result = mysqli_query($con, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $countries[] = $row;
        }
    }
    return $countries;
}

if (isset($_POST['submit'])) {
    // Validate form data
    if (empty($_POST['d_name']) || empty($_POST['div']) || empty($_POST['cir']) || empty($_POST['country']) || empty($_POST['about']) || empty($_POST['state']) || empty($_POST['price'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>All fields must be filled!</strong>
                  </div>';
    } else {
        // Sanitize inputs (example using prepared statements)
        $d_name = $_POST['d_name'];
        $div = $_POST['div'];
        $cir = $_POST['cir'];
        $country = $_POST['country'];
        $about = $_POST['about'];
        $state = $_POST['state'];
        $price = $_POST['price'];

        $stmt = $con->prepare("INSERT INTO pin (pin, division, circle, taluk, dist, state, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssd", $d_name, $div, $cir, $country, $about, $state, $price);

        if ($stmt->execute()) {
            $success = "<script>alert('Pincode Added');window.location.href='pinreg.php';</script>";
        } else {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Error adding pincode!</strong>
                      </div>';
        }
        $stmt->close();
    }
}

$countries = getCountries($con); // Fetch countries to populate the dropdown
?>

<?php include "head.php"; ?>

<body class="fix-header">
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <!-- Page wrapper  -->
        <div class="page-wrapper" style="height:1200px;">
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <?php echo $error; echo $success; ?>
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Register Pincode</h4>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Pincode</label>
                                                <input type="number" name="d_name" class="form-control" placeholder="Enter pincode" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Country</label>
                                                <select name="country" class="form-control" required>
                                                    <option value="" disabled selected>Select country</option>
                                                    <?php foreach ($countries as $country): ?>
                                                        <option value="<?php echo $country['country_name']; ?>"><?php echo $country['country_name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">State</label>
                                                <input type="text" name="state" class="form-control" placeholder="Enter state" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Sub state</label>
                                                <input type="text" name="about" class="form-control form-control-danger" placeholder="Enter sub state" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Region</label>
                                                <input type="text" name="cir" class="form-control" placeholder="Enter region" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">City</label>
                                                <input type="text" name="div" class="form-control" placeholder="Enter city" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Price</label>
                                                <input type="text" name="price" class="form-control" placeholder="Enter price" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Additional rows can be added here -->
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
            <!-- End Container fluid  -->
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
