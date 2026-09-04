<?php
/**
 * admin1/header.php
 * Premium Karuda Computers Admin — Navbar + Sidebar
 */
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/inc/config.php';

if (!isset($_SESSION['admin1_user'])) {
    header('Location: login.php');
    exit;
}

$admin1_user = $_SESSION['admin1_user'];
$user_name   = $admin1_user['full_name'] ?? 'Admin';
$user_photo  = $admin1_user['photo']     ?? 'no_image.png';
$cur_page    = basename($_SERVER['SCRIPT_NAME']);

// Session bridges
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id'        => $admin1_user['id']    ?? 1,
        'full_name' => $user_name,
        'email'     => $admin1_user['email'] ?? '',
        'photo'     => $user_photo,
    ];
}
if (!isset($_SESSION['adm_id'])) $_SESSION['adm_id'] = $admin1_user['id'] ?? 1;

// Stock check
$fof_1    = 0;
$firstDay = date('Y-m-01');
$date_is_new = date('d-m-Y', strtotime('last day of previous month'));
$check_stock = mysqli_query($con, "SELECT close_stk FROM stock_invent WHERE date_inv = '$date_is_new'");
if ($check_stock && mysqli_num_rows($check_stock)) {
    while ($cs = mysqli_fetch_array($check_stock)) $fof_1 += (float)$cs['close_stk'];
}

// Helper: is page active?
function av_active(string $cur, array $pages): string {
    return in_array($cur, $pages) ? 'active' : '';
}
function av_open(string $cur, array $pages): string {
    return in_array($cur, $pages) ? 'active menu-open' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Karuda Computers – Admin</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" href="../img/karuda_logo.png">

    <!-- Core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/datepicker3.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="css/jquery.fancybox.css">
    <link rel="stylesheet" href="css/AdminLTE.min.css">
    <link rel="stylesheet" href="css/_all-skins.min.css">
    <link rel="stylesheet" href="css/on-off-switch.css">
    <link rel="stylesheet" href="css/summernote.css">
    <link rel="stylesheet" href="style.css">
    <!-- Premium Theme -->
    <link rel="stylesheet" href="css/admin1-theme.css?v=<?php echo filemtime(__DIR__.'/css/admin1-theme.css'); ?>">
</head>

<body class="hold-transition fixed skin-blue sidebar-mini">
<div class="wrapper">

<!-- ══════════════════════════════════════════════════
     TOP NAVBAR
     ══════════════════════════════════════════════════ -->
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

        <!-- Left: Portal Label and Mobile Toggle -->
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <i class="fa fa-bars"></i>
            </a>
            <span class="nav-portal-label">Admin Portal</span>
        </div>

        <!-- Right: Actions -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav" style="display:flex;align-items:center;gap:6px;">

                <!-- Stock Update Alert -->
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
                             onerror="this.onerror=null; this.src='Res_img/no_image.png';">
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

<!-- ══════════════════════════════════════════════════
     SIDEBAR
     ══════════════════════════════════════════════════ -->
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">

            <!-- Dashboard -->
            <li class="<?php echo av_active($cur_page, ['index.php','dashboard.php']); ?>">
                <a href="index.php">
                    <i class="fa fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Categories (Moved from Settings) -->
            <li class="<?php echo av_active($cur_page, ['add_category.php']); ?>">
                <a href="add_category.php">
                    <i class="fa fa-list-alt"></i>
                    <span>Categories</span>
                </a>
            </li>

            <!-- ── E-COMMERCE ── -->
            <li class="sidebar-heading">E-Commerce</li>

            <!-- Products -->
            <li class="treeview <?php echo av_open($cur_page, ['products_list.php','add_products.php','category_lists.php','reviews.php','product_update.php','add_price.php','upd_stock.php','add_measurements.php']); ?>">
                <a href="#">
                    <i class="fa fa-leaf"></i>
                    <span>Products</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="add_products.php"><i class="fa fa-circle"></i> Add Product</a></li>
                    <li><a href="products_list.php"><i class="fa fa-circle"></i> Product List</a></li>
                    <li><a href="category_lists.php"><i class="fa fa-circle"></i> Categories</a></li>
                    <li><a href="add_measurements.php"><i class="fa fa-circle"></i> Measurements</a></li>
                    <li><a href="reviews.php"><i class="fa fa-circle"></i> Reviews</a></li>
                </ul>
            </li>

            <!-- Orders -->
            <li class="treeview <?php echo av_open($cur_page, ['my_orders.php','sales_list.php','refund_orders.php','all_orders.php','view_order.php']); ?>">
                <a href="#">
                    <i class="fa fa-shopping-bag"></i>
                    <span>Orders</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="my_orders.php"><i class="fa fa-circle"></i> All Orders</a></li>
                    <li><a href="sales_list.php"><i class="fa fa-circle"></i> Sales List</a></li>
                    <li><a href="refund_orders.php"><i class="fa fa-circle"></i> Refund Orders</a></li>
                    <li><a href="all_orders.php"><i class="fa fa-circle"></i> Delivery Orders</a></li>
                </ul>
            </li>

            <!-- Inventory & Shipping -->
            <li class="treeview <?php echo av_open($cur_page, ['stock.php','product_inventory.php','shipment.php','add_coupon.php','shipping_costs.php','promocode.php','pin1.php']); ?>">
                <a href="#">
                    <i class="fa fa-cubes"></i>
                    <span>Inventory &amp; Shipping</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="stock.php"><i class="fa fa-circle"></i> Adjust Stock</a></li>
                    <li><a href="product_inventory.php"><i class="fa fa-circle"></i> Stock Inventory</a></li>
                    <li><a href="shipment.php"><i class="fa fa-circle"></i> Shipments</a></li>
                    <li><a href="pin1.php"><i class="fa fa-circle"></i> Delivery Pincodes</a></li>
                    <li><a href="shipping_costs.php"><i class="fa fa-circle"></i> Shipping Costs</a></li>
                    <li><a href="add_coupon.php"><i class="fa fa-circle"></i> Coupons</a></li>
                    <li><a href="promocode.php"><i class="fa fa-circle"></i> Promo Codes</a></li>
                </ul>
            </li>

            <!-- Customers -->
            <li class="treeview <?php echo av_open($cur_page, ['customer.php','allusers.php','add_users.php','vendor_list.php','subscriber.php']); ?>">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Customers</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="customer.php"><i class="fa fa-circle"></i> Customers</a></li>
                    <li><a href="allusers.php"><i class="fa fa-circle"></i> All Users</a></li>
                    <li><a href="add_users.php"><i class="fa fa-circle"></i> Add User</a></li>
                    <li><a href="vendor_list.php"><i class="fa fa-circle"></i> Vendors</a></li>
                    <li><a href="subscriber.php"><i class="fa fa-circle"></i> Subscribers</a></li>
                </ul>
            </li>

            <!-- Reports -->
            <li class="treeview <?php echo av_open($cur_page, ['reports_list.php','report_customer.php','report_products.php','report_order_item.php','reports.php']); ?>">
                <a href="#">
                    <i class="fa fa-bar-chart"></i>
                    <span>Reports</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="reports_list.php"><i class="fa fa-circle"></i> Reports Hub</a></li>
                    <li><a href="report_customer.php"><i class="fa fa-circle"></i> Customer Reports</a></li>
                    <li><a href="report_products.php"><i class="fa fa-circle"></i> Product Reports</a></li>
                    <li><a href="report_order_item.php"><i class="fa fa-circle"></i> Order Items</a></li>
                </ul>
            </li>

            <!-- ── SYSTEM ── -->
            <li class="sidebar-heading">System</li>


            <!-- System Settings -->
            <li class="treeview <?php echo av_open($cur_page, ['web_settings.php']); ?>">
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span>System Settings</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="web_settings.php"><i class="fa fa-circle"></i> Web Settings</a></li>
                </ul>
            </li>

            <!-- Pages & Inquiries -->
            <li class="treeview <?php echo av_open($cur_page, ['page.php','banner.php','sliders.php','add_info.php','faq.php','contact.php','feedback.php','testimonial.php']); ?>">
                <a href="#">
                    <i class="fa fa-file-text-o"></i>
                    <span>Pages &amp; Inquiries</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php"><i class="fa fa-circle"></i> Page Content</a></li>
                    <li><a href="banner.php"><i class="fa fa-circle"></i> Banners &amp; Ads</a></li>
                    <li><a href="sliders.php"><i class="fa fa-circle"></i> Sliders</a></li>
                    <li><a href="add_info.php"><i class="fa fa-circle"></i> Latest Info</a></li>
                    <li><a href="faq.php"><i class="fa fa-circle"></i> FAQ</a></li>
                    <li><a href="contact.php"><i class="fa fa-circle"></i> Contact Messages</a></li>
                    <li><a href="feedback.php"><i class="fa fa-circle"></i> Feedback</a></li>
                    <li><a href="testimonial.php"><i class="fa fa-circle"></i> Testimonials</a></li>
                </ul>
            </li>

            <!-- Sign Out -->
            <li class="sidebar-logout">
                <a href="logout.php">
                    <i class="fa fa-sign-out"></i>
                    <span>Sign Out</span>
                </a>
            </li>

        </ul>
    </section>
</aside>

<!-- Content Wrapper -->
<div class="content-wrapper">
