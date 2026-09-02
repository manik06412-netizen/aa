<?php
ob_start();
session_start();
// error_reporting(0);
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

// Check if the user is logged in or not
if(!isset($_SESSION['user'])) {
	header('location: login.php');
	exit;
}
?>
<?php 
function Check_STATUS($con, $lastDate, $already)
{
    $Add_con = "";

    if (!empty($already)) {
        $Add_con = $already;
    }
    $checking = 0;
    $check_stock = mysqli_query($con, "SELECT close_stk FROM stock_invent WHERE date_inv = '$lastDate' $Add_con");
    if (mysqli_num_rows($check_stock)) {
        while ($close_stock = mysqli_fetch_array($check_stock)) {
            $close_stock_value = (float)$close_stock['close_stk'];
            $checking += $close_stock_value;
        }
    }
    return $checking;
}
// $lastDate = ;
$date_is_new = date('d-m-Y', strtotime('last day of previous month'));

$fof_1 = null;
$fof_1 = Check_STATUS($con, $date_is_new, '');
$firstDay = date('Y-m-01');

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Admin Panel</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
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
    <link rel="stylesheet" href="css/on-off-switch.css" />
    <link rel="stylesheet" href="css/summernote.css">
    <link rel="stylesheet" href="style.css">
    <style>
    .top_notification_set {
        font-size: 20px;
        position: relative;
    }

    .top_mgs_part {
        font-size: 13px;
        position: absolute;
        top: 1px;
        background-color: red;
        padding: 0px 5px;
        border-radius: 30px;
    }

    .btn {
        position: relative;
    }

    .btn-default {
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #ccc;
    }

    .btn-flat {
        box-shadow: none;
    }

    .btn:hover {
        background-color: #e0e0e0;
        color: #000;
    }

    .tooltip {
        visibility: hidden;
        width: 220px !important;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 5px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        margin-right: 20% !important;
        top: 43px !important;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .btn:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }
    </style>

</head>

<body class="hold-transition fixed skin-blue sidebar-mini">

    <div class="wrapper">

        <header class="main-header">

            <a href="index.php" class="logo">
                <span class="logo-lg">UK Admin</span>
            </a>

            <nav class="navbar navbar-static-top">

                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <span style="float:left;line-height:50px;color:#fff;padding-left:15px;font-size:18px;">Admin
                    Panel</span>
                <!-- Top Bar ... User Inforamtion .. Login/Log out Area -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="top_notification_set"> <i class="fa fa-bell" aria-hidden="true"></i>
                    <?php   if($firstDay == date('Y-m-d')){ if($fof_1 === 0){ ?><span class="top_mgs_part">1</span> <?php } } ?></span>
                            </a>
                            <?php if($firstDay == date('Y-m-d')){ if($fof_1 === 0){ ?>
                            <ul class="dropdown-menu">
                                <li class="user-footer">
                                    <!-- <div> -->
                                    <a href="javascript:void(0);" onclick="status_Change()"
                                        class="btn btn-default btn-flat"
                                        data-tooltip="If you click to update the preview month’s stock close, it will move to the current month’s opening stock in the balance.">Update
                                        Stock</a>

                                    <!-- </div> -->
                                </li>
                            </ul>
                            <?php } } ?>
                        </li>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>"
                                    class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo $_SESSION['user']['full_name']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-footer">
                                    <div>
                                        <a href="profile-edit.php" class="btn btn-default btn-flat">Edit Profile</a>
                                    </div>
                                    <div>
                                        <a href="logout.php" class="btn btn-default btn-flat">Log out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </nav>
        </header>

        <?php $cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); ?>
        <!-- Side Bar to Manage Shop Activities -->
        <aside class="main-sidebar">
            <section class="sidebar">

                <ul class="sidebar-menu">

                    <li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
                        <a href="index.php">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>


                    <!-- <li class="treeview <?php if( ($cur_page == 'settings.php') ) {echo 'active';} ?>">
			          <a href="settings.php">
			            <i class="fa fa-sliders"></i> <span>Website Settings</span>
			          </a>
			        </li> -->

                    <li
                        class="treeview <?php if( ($cur_page == 'add_category.php') || ($cur_page == 'add_measurement.php')|| ($cur_page == 'shipping_costs.php') ) {echo 'active';} ?>">
                        <a href="#">
                            <i class="fa fa-cogs"></i>
                            <span>Shop Settings</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="add_category.php"><i class="fa fa-circle-o"></i> Category</a></li>
                            <li><a href="add_measurements.php"><i class="fa fa-circle-o"></i> Measurements</a></li>
                            <li><a href="shipping_costs.php"><i class="fa fa-circle-o"></i> Shipping Costs</a></li>

                        </ul>
                    </li>


                    <!-- <li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') ) {echo 'active';} ?>">
                        <a href="product.php">
                            <i class="fa fa-shopping-bag"></i> <span>Product Management</span>
                        </a>
                    </li>


                    <li class="treeview <?php if( ($cur_page == 'order.php') ) {echo 'active';} ?>">
                        <a href="order.php">
                            <i class="fa fa-sticky-note"></i> <span>Order Management</span>
                        </a>
                    </li>


                     <li class="treeview <?php if( ($cur_page == 'slider.php') ) {echo 'active';} ?>">
			          <a href="slider.php">
			            <i class="fa fa-picture-o"></i> <span>Manage Sliders</span>
			          </a>
			        </li>
                
			        <li class="treeview <?php if( ($cur_page == 'service.php') ) {echo 'active';} ?>">
			          <a href="service.php">
			            <i class="fa fa-list-ol"></i> <span>Services</span>
			          </a>
			        </li> -->


                    <li
                        class="treeview <?php if( ($cur_page == 'customer.php') || ($cur_page == 'detailpage.php')  ) {echo 'active';} ?>">
                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span>Customers</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="customer.php"><i class="fa fa-circle-o"></i> Registered Customers</a></li>
                            <li><a href="detailpage.php"><i class="fa fa-circle-o"></i> Details</a></li>

                        </ul>
                    </li>
                    <li class="treeview ">
                        <a href="#">
                            <i class="fa fa-leaf"></i>
                            <span>Products</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="add_products.php"><i class="fa fa-circle-o"></i>Add</a></li>
                            <li><a href="products_list.php"><i class="fa fa-circle-o"></i> List</a></li>
                            <li><a href="category_lists.php"><i class="fa fa-circle-o"></i> Category</a></li>
                            <li><a href="reviews.php"><i class="fa fa-circle-o"></i> Reviews</a></li>
                        </ul>
                    </li>
                    <li class="treeview ">
                        <a href="#">
                            <i class="fa fa-list" aria-hidden="true"></i>
                            <span>Orders</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="my_orders.php"><i class="fa fa-circle-o"></i>My Orders</a></li>
                             <li><a href="sales_list.php"><i class="fa fa-circle-o"></i> Sales List</a></li>
                             <li><a href="refund_orders.php"><i class="fa fa-circle-o"></i> Refund orders</a></li>
							<!--<li><a href="category_lists.php"><i class="fa fa-circle-o"></i> Category</a></li>
							<li><a href="reviews.php"><i class="fa fa-circle-o"></i> Reviews</a></li> -->
                        </ul>
                    </li>

                    <li class="treeview ">
                        <a href="#">
                            <i class="fa fa-check "></i>
                            <span>Product status</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="stock.php"><i class="fa fa-circle-o"></i>Adjust Stock</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i>Inventory</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> Shipment</a></li>
                            <!-- <li><a href="#"><i class="fa fa-circle-o"></i> Collection</a></li> -->
                            <li><a href="add_coupon.php"><i class="fa fa-circle-o"></i> Coupons</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> Analytics</a></li>



                        </ul>
                    </li>
                    <li class="treeview <?php if( ($cur_page == 'reports.php') ) {echo 'active';} ?>">
                        <a href="reports_list.php">
                            <i class="fa fa-globe"></i> <span>Reports</span>
                        </a>
                    </li>



                    <li class="treeview ">
                        <a href="#">
                            <i class="fa fa-bell"></i>
                            <span> Settings</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="page.php"><i class="fa fa-circle-o"></i>Page Settings</a></li>
                            <li><a href="sliders.php"><i class="fa fa-circle-o"></i>Manage Sliders</a></li>
                            <li><a href="add_info.php"><i class="fa fa-circle-o"></i> Latest Information</a></li>
                            <li><a href="faq.php"><i class="fa fa-circle-o"></i> FAQ</a></li>

                        </ul>
                    </li>

                    <li class="treeview ">
                        <a href="#">
                            <i class="fa fa-bell"></i>
                            <span>Notification</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">

                            <li><a href="contact.php"><i class="fa fa-circle-o"></i>Contacts</a></li>
                            <li><a href="feedback.php"><i class="fa fa-circle-o"></i> Feedback</a></li>
                            <li><a href="social-media.php"><i class="fa fa-circle-o"></i>Social Media</a></li>
                            <li><a href="subscriber.php"><i class="fa fa-circle-o"></i>Subscribers</a></li>
                            <li><a href="testimonial.php"><i class="fa fa-circle-o"></i> Testimonials</a></li>
                            <li><a href="notification.php"><i class="fa fa-circle-o"></i> Notifications</a></li>

                        </ul>
                    </li>


                    <!-- <li class="treeview <?php if( ($cur_page == 'social-media.php') ) {echo 'active';} ?>">
                        <a href="social-media.php">
                            <i class="fa fa-globe"></i> <span>Social Media</span>
                        </a>
                    </li>

                    <li
                        class="treeview <?php if( ($cur_page == 'subscriber.php')||($cur_page == 'subscriber.php') ) {echo 'active';} ?>">
                        <a href="subscriber.php">
                            <i class="fa fa-hand-o-right"></i> <span>Subscribers</span>
                        </a>
                    </li>
                    <li
                        class="treeview <?php if( ($cur_page == 'testimonial.php')||($cur_page == 'testimonial.php') ) {echo 'active';} ?>">
                        <a href="testimonial.php">
                            <i class="fa fa-hand-o-right"></i> <span>Testimonials</span>
                        </a>
                    </li>
                    <li class="treeview <?php if( ($cur_page == 'faq.php') ) {echo 'active';} ?>">
                        <a href="faq.php">
                            <i class="fa fa-question-circle"></i> <span>FAQ</span>
                        </a>
                    </li> -->
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">

            <script>
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    const tooltipText = this.getAttribute('data-tooltip');
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.innerText = tooltipText;
                    this.appendChild(tooltip);
                });

                button.addEventListener('mouseleave', function() {
                    const tooltip = this.querySelector('.tooltip');
                    if (tooltip) {
                        tooltip.remove();
                    }
                });
            });
            </script>


            <script>
            async function status_Change() {
                try {
                    let response = await fetch('stock_cron.php');
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    let result = await response.json();

                    if (result.status === 1) {
                        alert('Stock Updated');
                        location.reload();
                    }
                } catch (e) {
                    alert('Something went wrong: ' + e.message);
                }
            }
            </script>