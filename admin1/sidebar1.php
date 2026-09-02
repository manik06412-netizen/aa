<?php
/**
 * admin1/sidebar1.php
 * Unified minimalist sidebar matching header.php exactly
 */
$cur_page = basename($_SERVER['SCRIPT_NAME']);
?>
<aside class="main-sidebar">
    <section class="sidebar">

        <ul class="sidebar-menu">

            <!-- Dashboard -->
            <li class="<?php if ($cur_page == 'index.php' || $cur_page == 'dashboard.php') echo 'active'; ?>">
                <a href="index.php">
                    <i class="fa fa-th-large"></i> <span>Dashboard</span>
                </a>
            </li>

            <!-- ════════════════════════════════════════ -->
            <!-- E-COMMERCE MANAGEMENT                   -->
            <!-- ════════════════════════════════════════ -->
            <li class="sidebar-heading">E-Commerce</li>

            <!-- Products -->
            <li class="treeview <?php if (in_array($cur_page, ['products_list.php', 'add_products.php', 'category_lists.php', 'reviews.php', 'product_update.php', 'add_price.php', 'upd_stock.php'])) echo 'active menu-open'; ?>">
                <a href="#">
                    <i class="fa fa-leaf"></i>
                    <span>Products</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="add_products.php"><i class="fa fa-circle"></i> Add Product</a></li>
                    <li><a href="products_list.php"><i class="fa fa-circle"></i> Product List</a></li>
                    <li><a href="category_lists.php"><i class="fa fa-circle"></i> Categories</a></li>
                    <li><a href="reviews.php"><i class="fa fa-circle"></i> Reviews</a></li>
                </ul>
            </li>

            <!-- Orders -->
            <li class="treeview <?php if (in_array($cur_page, ['my_orders.php', 'sales_list.php', 'refund_orders.php', 'all_orders.php', 'view_order.php'])) echo 'active menu-open'; ?>">
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

            <!-- Inventory & Stock -->
            <li class="treeview <?php if (in_array($cur_page, ['stock.php', 'product_inventory.php', 'shipment.php', 'add_coupon.php', 'shipping_costs.php'])) echo 'active menu-open'; ?>">
                <a href="#">
                    <i class="fa fa-cubes"></i>
                    <span>Inventory</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="stock.php"><i class="fa fa-circle"></i> Adjust Stock</a></li>
                    <li><a href="product_inventory.php"><i class="fa fa-circle"></i> Stock Inventory</a></li>
                    <li><a href="shipment.php"><i class="fa fa-circle"></i> Shipments</a></li>
                    <li><a href="shipping_costs.php"><i class="fa fa-circle"></i> Shipping Costs</a></li>
                    <li><a href="add_coupon.php"><i class="fa fa-circle"></i> Coupons</a></li>
                </ul>
            </li>

            <!-- Customers -->
            <li class="treeview <?php if (in_array($cur_page, ['customer.php', 'allusers.php', 'add_users.php', 'vendor_list.php', 'subscriber.php'])) echo 'active menu-open'; ?>">
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
            <li class="treeview <?php if (in_array($cur_page, ['reports_list.php', 'report_customer.php', 'report_products.php', 'report_order_item.php'])) echo 'active menu-open'; ?>">
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

            <!-- ════════════════════════════════════════ -->
            <!-- SYSTEM & CONFIGURATION                  -->
            <!-- ════════════════════════════════════════ -->
            <li class="sidebar-heading">System</li>

            <!-- Restaurants & Food Menu -->
            <li class="treeview <?php if (in_array($cur_page, ['allrestraunt.php', 'add_restraunt.php', 'all_menu.php', 'add_menu.php'])) echo 'active menu-open'; ?>">
                <a href="#">
                    <i class="fa fa-cutlery"></i>
                    <span>Restaurants</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="allrestraunt.php"><i class="fa fa-circle"></i> All Restaurants</a></li>
                    <li><a href="add_restraunt.php"><i class="fa fa-circle"></i> Add Restaurant</a></li>
                    <li><a href="all_menu.php"><i class="fa fa-circle"></i> Menus</a></li>
                    <li><a href="add_menu.php"><i class="fa fa-circle"></i> Add Menu</a></li>
                </ul>
            </li>

            <!-- Shop Settings -->
            <li class="treeview <?php if (in_array($cur_page, ['add_category.php', 'add_measurements.php', 'banner.php', 'promocode.php', 'pin1.php', 'web_settings.php'])) echo 'active menu-open'; ?>">
                <a href="#">
                    <i class="fa fa-sliders"></i>
                    <span>Settings</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="add_category.php"><i class="fa fa-circle"></i> Categories</a></li>
                    <li><a href="add_measurements.php"><i class="fa fa-circle"></i> Measurements</a></li>
                    <li><a href="banner.php"><i class="fa fa-circle"></i> Banners & Ads</a></li>
                    <li><a href="promocode.php"><i class="fa fa-circle"></i> Promo Codes</a></li>
                    <li><a href="pin1.php"><i class="fa fa-circle"></i> Delivery Pincodes</a></li>
                    <li><a href="web_settings.php"><i class="fa fa-circle"></i> Web Settings</a></li>
                </ul>
            </li>

            <!-- Content & Notification -->
            <li class="treeview <?php if (in_array($cur_page, ['page.php', 'sliders.php', 'add_info.php', 'faq.php', 'contact.php', 'feedback.php', 'testimonial.php'])) echo 'active menu-open'; ?>">
                <a href="#">
                    <i class="fa fa-file-text-o"></i>
                    <span>Pages & Inquiries</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php"><i class="fa fa-circle"></i> Page Content</a></li>
                    <li><a href="sliders.php"><i class="fa fa-circle"></i> Sliders</a></li>
                    <li><a href="add_info.php"><i class="fa fa-circle"></i> Latest Info</a></li>
                    <li><a href="faq.php"><i class="fa fa-circle"></i> FAQ</a></li>
                    <li><a href="contact.php"><i class="fa fa-circle"></i> Contact Messages</a></li>
                    <li><a href="feedback.php"><i class="fa fa-circle"></i> Feedback</a></li>
                    <li><a href="testimonial.php"><i class="fa fa-circle"></i> Testimonials</a></li>
                </ul>
            </li>

            <!-- Logout -->
            <li style="margin-top: 16px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 8px;">
                <a href="logout.php" style="color: #ef4444 !important;">
                    <i class="fa fa-power-off" style="color: #ef4444 !important;"></i>
                    <span>Sign Out</span>
                </a>
            </li>

        </ul>
    </section>
</aside>
