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
include("../config.php");

session_start();
error_reporting(0);

require_once('header.php');

// Initialize error and success variables
$error = '';
$success = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['c_name'])) {
        $error = 'field Required!';
    } else {
        $date = date("D M d Y");

        $mql = "UPDATE promo SET code ='$_POST[c_name]', discount='$_POST[k1]', purpose='$_POST[pur]', dat='$date' WHERE id='$_GET[cat_upd]'";
        mysqli_query($con, $mql);

        // Store success message in a session to display it after redirect
        $_SESSION['success'] = "Updated successfully";
        
        // Redirect to the same page to avoid form resubmission
        header("Location: update_promo.php?cat_upd=" . $_GET['cat_upd']);
        exit;
    }
}

?>

<!-- Include jQuery and Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>

<?php
// Show success message using Toastr
if (isset($_SESSION['success'])) {
    echo '<script>toastr.success("' . $_SESSION['success'] . '");</script>';
    // Unset the success session variable after displaying the message
    unset($_SESSION['success']);
}

// Display errors if any
if (!empty($error)) {
    echo '<script>toastr.error("' . $error . '");</script>';
}
?>

<!-- Your HTML Form Here -->
<section class="content-header">
    <div class="content-header-left">
        <h1>Update Coupon Code</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info" style="padding:25px">
                <div class="card-body">
                    <form action='' method='post' enctype='multipart/form-data'>
                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php
                                        $ssql = "SELECT * FROM promo WHERE id='$_GET[cat_upd]'";
                                        $res = mysqli_query($con, $ssql);
                                        $row = mysqli_fetch_array($res);
                                        ?>
                                        <label class="control-label">Coupon Code *</label>
                                        <input type="text" name="c_name" id="promoCode" class="form-control" maxlength="8" value="<?php echo $row['code']; ?>" placeholder="Click To Generate PromoCode"><br>
                                        <button type="button" class="btn btn-info" onclick="generatePromoCode()">Generate</button>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Discount(%) *</label>
                                        <input type="text" name="k1" class="form-control" value="<?php echo $row['discount']; ?>" maxlength="8" min="1" placeholder="Enter Discount Amount" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Purpose</label>
                                        <textarea name="pur" class="form-control" placeholder="Purpose"><?php echo $row['purpose']; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" name="submit" class="btn btn-success" value="Save">
                                    <a href="add_coupon.php" class="btn btn-warning">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

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

<?php require_once('footer.php'); ?>
