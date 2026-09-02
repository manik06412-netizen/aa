<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(0);

if (!isset($con) || !$con) {
    if (class_exists('\\App\\Core\\Database')) {
        $con = \App\Core\Database::getInstance()->getConnection();
    } elseif (file_exists(__DIR__ . '/../dbconnect.php')) {
        require_once __DIR__ . '/../dbconnect.php';
    }
}

if (!isset($_SESSION['uid'])) {
    $tmpid = mysqli_query($con, "SELECT max(id) as tmpid FROM user");
    $rtem = mysqli_fetch_array($tmpid);
    $tid = $rtem ? (int)$rtem['tmpid'] : 0;
    $date = date("d/m/Y");
    $tpuser = rand(100,200) . "" . (time() + $tid);
    $_SESSION['uid'] = $tpuser;
    $intemp = mysqli_query($con, "INSERT INTO user values(null,'$tpuser','Guest','Guest','-','-','-','$date','1')");
}

$user_id = $_SESSION['uid'] ?? '';
$base = defined('BASE_URL') ? BASE_URL : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Karuda Computers — Premium Computers, Laptops & Accessories">
    <meta name="author" content="Karuda Computers">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Karuda Computers — Premium Computers, Laptops & Accessories'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $base; ?>img/karuda_logo.png">

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Toastr & jQuery -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Core CSS -->
    <link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo $base; ?>css/vendors.css" rel="stylesheet">
    <link href="<?php echo $base; ?>css/mobile-responsive.css" rel="stylesheet">

    <!-- Karuda Computers Theme -->
    <link href="<?php echo $base; ?>css/karuda-theme.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<a href="#sign-in-dialog" id="sign-in" class="login d-none" title="Sign In">Sign In</a>