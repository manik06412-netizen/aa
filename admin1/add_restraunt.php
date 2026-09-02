<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

$message = '';
$error = '';

if (isset($_POST['submit'])) {
    $c_id = mysqli_real_escape_string($con, $_POST['c_name'] ?? '');
    $res_name = mysqli_real_escape_string($con, $_POST['res_name'] ?? '');
    $email = mysqli_real_escape_string($con, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($con, $_POST['phone'] ?? '');
    $url = mysqli_real_escape_string($con, $_POST['url'] ?? '');
    $o_hr = mysqli_real_escape_string($con, $_POST['o_hr'] ?? '');
    $c_hr = mysqli_real_escape_string($con, $_POST['c_hr'] ?? '');
    $o_days = mysqli_real_escape_string($con, $_POST['o_days'] ?? '');
    $address = mysqli_real_escape_string($con, $_POST['address'] ?? '');

    if (empty($c_id) || empty($res_name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill all required fields.';
    } else {
        $fname = $_FILES['file']['name'] ?? '';
        $temp = $_FILES['file']['tmp_name'] ?? '';
        $fsize = $_FILES['file']['size'] ?? 0;
        
        $fnew = 'no_image.png';
        if (!empty($fname)) {
            $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                if ($fsize <= 2097152) { // 2MB
                    $fnew = uniqid() . '.' . $ext;
                    $store = __DIR__ . "/Res_img/" . $fnew;
                    move_uploaded_file($temp, $store);
                } else {
                    $error = 'Image size should be less than 2MB!';
                }
            } else {
                $error = 'Invalid image extension! JPG, PNG, GIF, WEBP allowed.';
            }
        }

        if (empty($error)) {
            $sql = "INSERT INTO restaurant (c_id, title, email, phone, url, o_hr, c_hr, o_days, address, image) 
                    VALUES ('$c_id', '$res_name', '$email', '$phone', '$url', '$o_hr', '$c_hr', '$o_days', '$address', '$fnew')";
            if (mysqli_query($con, $sql)) {
                $_SESSION['flash_success'] = 'Restaurant / Store added successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } else {
                $error = 'Database error: ' . mysqli_error($con);
            }
        }
    }
}

require_once('header.php');
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Add New Restaurant / Store</h1>
    </div>
    <div class="content-header-right">
        <a href="allrestraunt.php" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> View All Stores</a>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-ban"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php $message = $_SESSION['flash_success'] ?? ''; unset($_SESSION['flash_success']); ?>
            <?php if (!empty($message)): ?>
                <div class="alert alert-success alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i> <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="box box-info" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 20px;">
                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category <span>*</span></label>
                            <div class="col-sm-6">
                                <select name="c_name" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    <?php
                                    $res_cat = mysqli_query($con, "SELECT * FROM res_category ORDER BY c_name ASC");
                                    while ($rc = mysqli_fetch_array($res_cat)) {
                                        echo '<option value="' . $rc['c_id'] . '">' . htmlspecialchars($rc['c_name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Store / Restaurant Name <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="res_name" placeholder="Store Name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" name="email" placeholder="store@example.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Phone <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Website URL</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="url" placeholder="https://...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Opening & Closing Hours</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="o_hr" placeholder="e.g. 9:00 AM">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="c_hr" placeholder="e.g. 10:00 PM">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Open Days</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="o_days" placeholder="e.g. Mon-Sat">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Address <span>*</span></label>
                            <div class="col-sm-6">
                                <textarea name="address" class="form-control" rows="3" placeholder="Full Address" required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Store Image</label>
                            <div class="col-sm-6">
                                <input type="file" name="file" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success" name="submit" style="border-radius: 6px; font-weight: 600; padding: 8px 24px;">
                                    <i class="fa fa-plus"></i> Save Restaurant
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>