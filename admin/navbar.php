<?php
/**
 * admin1/navbar.php — Standalone navbar (used on pages that don't need sidebar)
 * Matches the premium header.php design exactly
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/inc/config.php';

$admin1_user = $_SESSION['admin1_user'] ?? ['full_name' => 'Admin', 'photo' => 'no_image.png'];
$user_name   = $admin1_user['full_name'] ?? 'Admin';
$user_photo  = $admin1_user['photo']     ?? 'no_image.png';

$fof_1       = 0;
$firstDay    = date('Y-m-01');
$date_is_new = date('d-m-Y', strtotime('last day of previous month'));
$check_stock = mysqli_query($con, "SELECT close_stk FROM stock_invent WHERE date_inv = '$date_is_new'");
if ($check_stock && mysqli_num_rows($check_stock)) {
    while ($cs = mysqli_fetch_array($check_stock)) $fof_1 += (float)$cs['close_stk'];
}
?>
<header class="main-header">

    <!-- Brand Logo -->
    <a href="index.php" class="logo">
        <?php
        // Build a reliable absolute URL for the logo
        $logoProtocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $logoHost     = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $logoPath     = rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME']))), '/');
        $logoUrl      = $logoProtocol . '://' . $logoHost . $logoPath . '/admin1/img/karuda_logo.png';
        ?>
        <img src="<?php echo htmlspecialchars($logoUrl); ?>"
             alt="Karuda Computers"
             class="sidebar-brand-logo"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
        <span class="logo-fallback-icon" style="display:none;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#0070F3,#00BCD4);align-items:center;justify-content:center;flex-shrink:0;">
            <i class='fa fa-leaf' style='color:#fff;font-size:18px;'></i>
        </span>
        <span class="brand-title">Admin Panel</span>
    </a>

    <!-- Top Nav -->
    <nav class="navbar navbar-static-top" role="navigation">

        <div style="display:flex;align-items:center;">
            <span class="nav-portal-label">Admin Portal</span>
        </div>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav" style="display:flex;align-items:center;gap:6px;">

                <!-- Stock Alert -->
                <?php if ($firstDay == date('Y-m-d') && $fof_1 === 0): ?>
                <li>
                    <a href="javascript:void(0);" onclick="status_Change()" class="nav-stock-alert">
                        <i class="fa fa-bell"></i>
                        <span class="hidden-xs">Stock Update</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Live Website -->
                <li>
                    <a href="../index.php" target="_blank" class="nav-site-btn">
                        <i class="fa fa-globe"></i>
                        <span class="hidden-xs">Live Website</span>
                    </a>
                </li>

                <!-- User Dropdown -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle user-menu-toggle" data-toggle="dropdown">
                        <img src="../assets/uploads/<?php echo htmlspecialchars($user_photo); ?>"
                             class="user-image" alt="Avatar"
                             onerror="this.src='no_image.png'">
                        <span class="user-name hidden-xs"><?php echo htmlspecialchars($user_name); ?></span>
                        <i class="fa fa-chevron-down" style="font-size:10px;color:#9ca3af;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right user-dropdown">
                        <li>
                            <a href="profile-edit.php">
                                <i class="fa fa-user-circle-o" style="color:#6b7280;width:16px;"></i>
                                My Profile
                            </a>
                        </li>
                        <li class="divider" style="margin:4px 0;border-color:#f3f4f6;"></li>
                        <li class="danger">
                            <a href="logout.php">
                                <i class="fa fa-sign-out" style="width:16px;"></i>
                                Sign Out
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
