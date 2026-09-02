<div class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <style>
        .sidebar-nav {
            height: 100%;
            /* Set the height of the sidebar */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        #sidebarnav {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        #sidebarnav li {
            position: relative;
        }

        #sidebarnav li a {
            display: block;
            padding: 10px 15px;
            color: #000;
            text-decoration: none;
        }

        /* Add other styles as needed */
        </style>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>

                <li>
                    <a href="dashboard.php" aria-expanded="false">
                        <i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span>
                    </a>
                    <!-- <ul aria-expanded="false" class="collapse">
                        <li><a href="dashboard.php">Dashboard</a></li>
                    </ul> -->
                </li>
<!-- 
                <li>
                    <a class="has-arrow" href="allusers.php" aria-expanded="false">
                        <i class="fa fa-user f-s-20"></i><span class="hide-menu">User's List</span>
                    </a>
                </li> -->
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-user f-s-20" aria-hidden="false"></i><span class="hide-menu">User Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse1">
                        <li><a href="allusers.php">User's List</a></li>
                        <li><a href="allusers.php">User's Order's History</a></li>
                        <li><a href="user_rev.php">User's Feedback</a></li>
                        <li><a href="user_feed.php">Contacted user's</a></li>
                        <li><a href="newsletter.php">News letter</a></li>
                        <!-- <li><a href="user_complaint.php">User's Complaint</a></li> -->
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-pencil-square" aria-hidden="false"></i><span class="hide-menu">Product settings</span>
                    </a>
                    <ul aria-expanded="false" class="collapse1">
                        <li><a href="add_category.php">Category</a></li>
                        <li><a href="add_btype.php">Measurements</a></li>
                        <!-- <li><a href="user_feed.php">User's Feedback</a></li> -->
                        <!-- <li><a href="user_complaint.php">User's Complaint</a></li> -->
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Product Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="add_menu.php">Add Product</a></li>
                        <li><a href="all_menu.php">Edit Product</a></li>
                        <li><a href="update_price.php">Update Product Prize</a></li>
                        <li><a href="update_product_image.php">Update Product Image</a></li>
                        <li><a href="productreview.php">Manage Product Review's</a></li>
                    </ul>
                </li>
               
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-dropbox" aria-hidden="true"></i><span class="hide-menu">Inventory Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="..">..</a></li>

                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="hide-menu">Order Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="order.php">All Orders</a></li>
                        <li><a href="confirm.php">Confirmed orders</a></li>
                        <li><a href="#">Pending orders</a></li>
                        <li><a href="#">Shipped orders</a></li>
                        <li><a href="#">Delivered orders</a></li>
                        <li><a href="#">Cancelled orders</a></li>
                        <li><a href="#">Return and Refunds</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-money" aria-hidden="true"></i><span class="hide-menu">Payment Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="transaction.php">All Transaction</a></li>
                        <!-- <li><a href="transaction.php">Payment gateway Details</a></li> -->
                        <li><a href="order_reports.php">Transaction Reports</a></li> 
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="pinsave.php" aria-expanded="false">
                        <i class="fa fa-ship" aria-hidden="true"></i><span class="hide-menu">Shipping Management</span>
                    </a>
                    
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-ticket" aria-hidden="true"></i><span class="hide-menu">Promotions and Discounts</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="coupon.php">Coupon's</a></li>
                        <li><a href="#">Discounts</a></li>
                        <!-- <li><a href="order_reports.php">Reports</a></li> -->
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="web_settings.php" aria-expanded="false">
                        <i class="fa fa-mouse-pointer" aria-hidden="true"></i><span class="hide-menu">Website settings</span>
                    </a>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-align-right" aria-hidden="true"></i><span class="hide-menu">Content Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                    <li><a href="content_setting.php">About</a></li>
                        <li><a href="content_banner.php">Banner</a></li>
                        <li><a href="qa.php">FAQ</a></li>
                        <li><a href="testimonial.php">Testimonial's</a></li>
                        <!-- <li><a href="order_reports.php">Reports</a></li> -->
                    </ul>
                </li>


                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-clipboard" aria-hidden="true"></i><span class="hide-menu">Reports</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="#">daily</a></li>
                        <li><a href="qa.php">monthly</a></li>
                        <!-- <li><a href="order_reports.php">Reports</a></li> -->
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-window-restore " aria-hidden="true"></i><span class="hide-menu">Backup and Security</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="#">Backup Data's</a></li>
                        <li><a href="#">Change Admin password</a></li>
                        <!-- <li><a href="order_reports.php">Reports</a></li> -->
                    </ul>
                </li>
                 <!-- <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-money " aria-hidden="true"></i><span class="hide-menu">SET Weight/Price</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="add_pp.php">Add Price</a></li>
                        <li><a href="update_pp.php">Update Price</a></li>
                    </ul>
                </li> -->
                <!-- <li>
                    <a class="has-arrow" href="" aria-expanded="false">
                        <i class="fa fa-question-circle "></i><span class="hide-menu">FAQ</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a class="has-arrow" href="add_category.php" aria-expanded="false">
                        <i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Category</span>
                    </a>
                </li> -->
                    <!-- <li>
                    <a class="has-arrow" href="vendor_list.php" aria-expanded="false">
                        <i class="fa fa-balance-scale" aria-hidden="true"></i><span class="hide-menu">Vendor List</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a class="has-arrow" href="add_btype.php" aria-expanded="false">
                        <i class="fa fa-briefcase" aria-hidden="true"></i><span class="hide-menu">Add business
                            type</span>
                    </a>
                </li> -->
                 <!-- <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-bullhorn" aria-hidden="true"></i><span class="hide-menu">Product utility</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                         <li><a href="main_market.php">Main Market Distribution</a></li> 
                        <li><a href="delivery_portals.php">Delivery Portals</a></li>
                        
                        <li><a href="accepted_currency.php">Accepted Currency</a></li>
                        <li><a href="spoken_lang.php">Spoken Languages</a></li> 
                    </ul>
                </li>  -->
                <!-- <li>
                    <a class="has-arrow" href="delivery_portals.php" aria-expanded="false">
                        <i class="fa fa-bullhorn"></i><span class="hide-menu">Delivery Portals</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a class="has-arrow" href="pinsave.php" aria-expanded="false">
                        <i class="fa fa-ship"></i><span class="hide-menu">Shipping Charge</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a class="has-arrow" href="reqrec.php" aria-expanded="false">
                        <i class="fa fa-repeat"></i><span class="hide-menu">Requirements Received</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a class="has-arrow" href="coupon.php" aria-expanded="false">
                        <i class="fa fa-birthday-cake"></i><span class="hide-menu">Add Coupon</span>
                    </a>
                </li> -->
               
              
                <!-- <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Product</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="add_menu.php">Add</a></li>
                        <li><a href="all_menu.php">View</a></li>
                    </ul>
                </li> -->
                <!-- <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-money " aria-hidden="true"></i><span class="hide-menu">SET Weight/Price</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="add_pp.php">Add Price</a></li>
                        <li><a href="update_pp.php">Update Price</a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a class="has-arrow" href="popup.php" aria-expanded="false">
                        <i class="fa fa-bell-o  " aria-hidden="true"></i><span class="hide-menu"> Popup</span>
                    </a> -->
                <!-- <ul aria-expanded="false" class="collapse">
                        <li><a href="popup.php">Add</a></li>
                        <li><a href="popupview.php">View</a></li>
                    </ul> -->
                <!-- </li> -->

                <!-- <li>
                    <a class="has-arrow" href="popup.php" aria-expanded="false">
                        <i class="fa fa-ship"></i><span class="hide-menu">Popup</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a class="has-arrow" href="adds.php" aria-expanded="false">
                        <i class="fa fa-smile-o"></i><span class="hide-menu">Adds Image</span>
                    </a>
                </li>
                <li>
                    <a class="has-arrow" href="promocode.php" aria-expanded="false">
                        <i class="fa fa-pencil-square-o "></i><span class="hide-menu">Promo Code</span>
                    </a>
                </li>
                
                <li>
                    <a class="has-arrow" href="testimonial.php" aria-expanded="false">
                        <i class="fa fa-eye "></i><span class="hide-menu">Testimonial</span>
                    </a>
                </li> -->
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</div>