<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}
?>
<?php
session_start();
error_reporting(0);

require_once('header.php'); 

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Check if code is empty
    if(empty($_POST['code'])) {
        $_SESSION['error'] = 'Field required!';
    } else {
        // Check if the coupon already exists
        $check_cat = mysqli_query($con, "SELECT code FROM promo WHERE code = '".$_POST['code']."'");

        if(mysqli_num_rows($check_cat) > 0) {
            $_SESSION['error'] = 'Coupon already exists!';
        } else {
            // Insert new coupon
            $date = date("D M d Y");
            $mql = "INSERT INTO promo VALUES(null, '".$_POST['code']."', '".$_POST['pur']."', '".$_POST['dis']."', '0', '".$date."')";
            mysqli_query($con, $mql);
            $_SESSION['success'] = 'New coupon added successfully!';
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!-- Toastr CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add Coupon</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info" style="padding:25px">
                <div class="card-body">
                    <form action='' method='post' enctype='multipart/form-data'>
                        <div class="form-body">
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Coupon Code *</label>
                                        <input type="text" name="code" id="promoCode" class="form-control" maxlength="8" placeholder="Click To Generate PromoCode"><br>
                                        <button type="button" class="btn btn-info" onclick="generatePromoCode()">Generate</button>
                                    </div>
                                    <script>
                                        function generatePromoCode() {
                                            var codeLength = 8;
                                            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                                            var generatedCode = '';

                                            for (var i = 0; i < codeLength; i++) {
                                                var randomIndex = Math.floor(Math.random() * characters.length);
                                                generatedCode += characters.charAt(randomIndex);
                                            }

                                            document.getElementById('promoCode').value = generatedCode;
                                        }
                                    </script>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Discount(%) *</label>
                                        <input type="number" name="dis" class="form-control" min="1" placeholder="Enter Discount Amount" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Purpose</label>
                                        <textarea name="pur" class="form-control" placeholder="Enter Purpose" required></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-success" value="Save">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-header">
    <div class="content-header-left">
        <h1>Coupon Details</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="display:none;">ID#</th>
                                <th>Coupon Code</th>
                                <th>Discount(%)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM promo ORDER BY id DESC";
                            $query = mysqli_query($con, $sql);

                            if (!mysqli_num_rows($query) > 0) {
                                
                            } else {
                                while ($rows = mysqli_fetch_array($query)) {
                                    $status = $rows['status'];
                                    $buttonClass = ($status == 0) ? 'btn btn-success' : 'btn btn-danger';
                                    $buttonText = ($status == 0) ? 'Activate' : 'Inactivate';
                                    $buttonLink = 'update_status.php?cat_upd=' . $rows['id'] . '&status=' . (($status == 0) ? '1' : '0');

                                    echo '<tr>
                                        <td style="display:none;">' . htmlspecialchars($rows['id']) . '</td>
                                        <td>' . htmlspecialchars($rows['code']) . '</td>
                                        <td>' . htmlspecialchars($rows['discount']) . '</td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="confirmDeleteCode('.$rows['id'].')" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                          
                                            <a href="update_promo.php?cat_upd=' . htmlspecialchars($rows['id']) . '" class="btn btn-info btn-xs">
                                                <i class="fa fa-gear"></i>
                                            </a>
                                            <a href="' . htmlspecialchars($buttonLink) . '" class="' . htmlspecialchars($buttonClass) . '">
                                                ' . $buttonText . '
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
</section>

<!-- Toastr Notifications -->
<?php
if (isset($_SESSION['error'])) {
    echo "<script>
        $(document).ready(function() {
            toastr.error('".$_SESSION['error']."', 'Error', { timeOut: 5000 });
        });
    </script>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo "<script>
        $(document).ready(function() {
            toastr.success('".$_SESSION['success']."', 'Success', { timeOut: 5000 });
        });
    </script>";
    unset($_SESSION['success']);
}
?>

<!-- Confirm Delete Script -->
<script>
function confirmDeleteCode(categoryId) {
    console.log(categoryId);
    var confirmation = confirm("Are you sure you want to delete this Coupon Code?");
    if (confirmation) {
        window.location.href = 'delete_promo.php?cat_del=' + categoryId;
    }
}
</script>


<?php require_once('footer.php'); ?>
