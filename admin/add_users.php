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
    $fname = mysqli_real_escape_string($con, trim($_POST['fname'] ?? ''));
    $lname = mysqli_real_escape_string($con, trim($_POST['lname'] ?? ''));
    $email = mysqli_real_escape_string($con, trim($_POST['email'] ?? ''));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    if (empty($fname) || empty($email) || empty($phone) || empty($password)) {
        $error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $check_email = mysqli_query($con, "SELECT id FROM user WHERE email = '$email'");
        if ($check_email && mysqli_num_rows($check_email) > 0) {
            $error = 'A user with this email already exists!';
        } else {
            $hashed_pass = md5($password);
            $date = date("d/m/Y");
            $user_id_gen = 'USR' . rand(1000, 9999) . time();

            $sql = "INSERT INTO user (user_id, fname, lname, email, mobile, password, date, status) 
                    VALUES ('$user_id_gen', '$fname', '$lname', '$email', '$phone', '$hashed_pass', '$date', 1)";
            
            if (mysqli_query($con, $sql)) {
                $_SESSION['flash_success'] = 'User added successfully!';
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
        <h1>Add New User</h1>
    </div>
    <div class="content-header-right">
        <a href="allusers.php" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> View All Users</a>
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
                <form action="" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">First Name <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="text" autocomplete="off" class="form-control" name="fname" placeholder="Enter First Name" required value="<?php echo htmlspecialchars($_POST['fname'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Last Name</label>
                            <div class="col-sm-6">
                                <input type="text" autocomplete="off" class="form-control" name="lname" placeholder="Enter Last Name" value="<?php echo htmlspecialchars($_POST['lname'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email Address <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="email" autocomplete="off" class="form-control" name="email" placeholder="user@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Phone / Mobile <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="text" autocomplete="off" class="form-control" name="phone" placeholder="10-digit mobile number" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="password" autocomplete="off" class="form-control" name="password" placeholder="Minimum 6 characters" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success pull-left" name="submit" style="border-radius: 6px; font-weight: 600; padding: 8px 24px;">
                                    <i class="fa fa-plus"></i> Add User
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