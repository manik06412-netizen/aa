<?php 
   session_start();
   error_reporting(0);  

   $userid=$_SESSION['uid'];
   if($_SESSION['compledorder'] == 1){
    unset($_SESSION['compledorder']);
    header('location: index.php');
        exit;
}
    function decrypt_checkout_refid($data, $key = '23232322114432') {
        $method = 'AES-256-CBC';
        if (empty($data)) return '';
        try {
            $decoded = base64_decode($data);
            if ($decoded !== false && strpos($decoded, '::') !== false) {
                $parts = explode('::', $decoded, 2);
                if (count($parts) === 2) {
                    $decrypted = openssl_decrypt($parts[0], $method, $key, 0, $parts[1]);
                    if ($decrypted !== false && !empty($decrypted)) {
                        return $decrypted;
                    }
                }
            }
        } catch (\Throwable $e) {}
        return $data;
    }

    $raw_ref_id = isset($_GET['refid']) ? $_GET['refid'] : (isset($_SESSION['raw_refid']) ? $_SESSION['raw_refid'] : (isset($_SESSION['refidd']) ? $_SESSION['refidd'] : ''));
    $new_ref_id = decrypt_checkout_refid($raw_ref_id);

    $_SESSION['refidd'] = $new_ref_id;
    $_SESSION['raw_refid'] = $raw_ref_id;
?>
<?php include('include/header.php'); ?>

<body>
    <div id="page">
        <?php include('include/scroller_navbar.php') ?>
        <header class="header">
            <?php  include('include/navbar.php'); ?>
        </header>
        <!-- /header -->
        <div class="sub_header_in sticky_header">
            <div class="container text-center">
                <h1>Checkout Products</h1>
            </div>
            <!-- /container -->
        </div>
        <!-- /sub_header -->
        <?php  
         $userid=$_SESSION['uid'];
         function check_address(){
           include('dbconnect.php');
           $userid=$_SESSION['uid'];
           $result=mysqli_query($con,"SELECT * FROM deliver_address where user_id='$userid'");
           $row=mysqli_num_rows($result);
           return $row ;
         }
           function checklogin(){
              include('dbconnect.php');
           $userid=$_SESSION['uid'];
           $check_login=mysqli_query($con,"SELECT * FROM user where user_id='$userid'");
           $row=mysqli_fetch_array($check_login);
           $result=$row['email'];
           return $result;
           }
         
           function checkaddress(){
           include('dbconnect.php');
           $userid=$_SESSION['uid'];
           $check_contion=mysqli_query($con,"SELECT * FROM address where userid='$userid'");
           $numrow=mysqli_num_rows($check_contion);
           return $numrow;
           }
           ?>


        <?php
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        include("dbconnect.php");

        $redirect_checkout_url = "checkout.php?refid=" . urlencode($raw_ref_id);

        // Helper function for resolving image path
        function resolve_checkout_image($img, $prd_id = '', $con = null) {
            if ((empty($img) || $img === 'no_image.png') && !empty($prd_id) && $con) {
                $dq = mysqli_query($con, "SELECT img FROM dishes WHERE rs_id='$prd_id' OR d_id='$prd_id'");
                if ($dq && ($drow = mysqli_fetch_assoc($dq))) {
                    $img = $drow['img'];
                }
            }
            if (empty($img)) return 'ovi-logo.png';

            $candidates = [
                $img,
                'admin1/' . $img,
                'admin/' . $img,
                'avadmin/' . $img,
                'admin1/Res_img/dishes/' . $img,
                'admin/Res_img/dishes/' . $img,
                'avadmin/Res_img/dishes/' . $img,
            ];

            foreach ($candidates as $cand) {
                if (file_exists($cand) || file_exists(__DIR__ . '/../../' . $cand) || file_exists(__DIR__ . '/../' . $cand)) {
                    return $cand;
                }
            }

            if (strpos($img, 'http') === 0 || strpos($img, '/') === 0) {
                return $img;
            }

            return 'admin1/' . ltrim($img, '/');
        }

        // Handle Add New Address POST submission directly
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_checkout_address'])) {
            $fname = mysqli_real_escape_string($con, trim($_POST['fname'] ?? ''));
            $email = mysqli_real_escape_string($con, trim($_POST['email'] ?? ''));
            $mob = mysqli_real_escape_string($con, trim($_POST['mob'] ?? ''));
            $shipping = mysqli_real_escape_string($con, trim($_POST['shipping'] ?? ''));
            $district = mysqli_real_escape_string($con, trim($_POST['district'] ?? ''));
            $state = mysqli_real_escape_string($con, trim($_POST['state'] ?? ''));
            $country = mysqli_real_escape_string($con, trim($_POST['country'] ?? 'India'));
            $pin = mysqli_real_escape_string($con, trim($_POST['pin'] ?? ''));
            $usrid = $_SESSION['uid'] ?? 0;

            if (!empty($fname) && !empty($mob) && !empty($shipping) && !empty($pin)) {
                $ins_addr = mysqli_query($con, "INSERT INTO address (userid, fname, email, mobile, flat, district, state, country, pin, status) VALUES ('$usrid', '$fname', '$email', '$mob', '$shipping', '$district', '$state', '$country', '$pin', 1)");
                if ($ins_addr) {
                    $new_addr_id = mysqli_insert_id($con);
                    mysqli_query($con, "DELETE FROM deliver_address WHERE user_id='$usrid'");
                    mysqli_query($con, "INSERT INTO deliver_address (user_id, address_id, pincode, status) VALUES ('$usrid', '$new_addr_id', '$pin', 1)");
                    $_SESSION['address_pin'] = $pin;
                    echo "<script>window.location.href = '" . $redirect_checkout_url . "';</script>";
                    exit;
                }
            }
        }

        // Handle Select Existing Address POST submission directly
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_active_address_btn'])) {
            $sel_addr_id = (int)($_POST['addressset'] ?? 0);
            $usrid = $_SESSION['uid'] ?? 0;
            if ($sel_addr_id > 0) {
                $addr_q = mysqli_query($con, "SELECT * FROM address WHERE id='$sel_addr_id' AND userid='$usrid'");
                if ($addr_q && ($addr_row = mysqli_fetch_assoc($addr_q))) {
                    mysqli_query($con, "DELETE FROM deliver_address WHERE user_id='$usrid'");
                    mysqli_query($con, "INSERT INTO deliver_address (user_id, address_id, pincode, status) VALUES ('$usrid', '$sel_addr_id', '{$addr_row['pin']}', 1)");
                    $_SESSION['address_pin'] = $addr_row['pin'];
                    echo "<script>window.location.href = '" . $redirect_checkout_url . "';</script>";
                    exit;
                }
            }
        }

        // Fetch User and Address Details & Prefills
        $is_user_logged_in = false;
        $user_info = null;
        $prefill_name = '';
        $prefill_email = '';
        $prefill_mobile = '';

        if (!empty($_SESSION['uid'])) {
            $u_chk = mysqli_query($con, "SELECT * FROM user WHERE user_id='{$_SESSION['uid']}'");
            if ($u_chk && ($user_info = mysqli_fetch_assoc($u_chk))) {
                $is_user_logged_in = true;
                $prefill_name = trim(($user_info['fname'] ?? '') . ' ' . ($user_info['lname'] ?? ''));
                if (empty($prefill_name)) {
                    $prefill_name = $_SESSION['uname'] ?? '';
                }
                $prefill_email = $user_info['email'] ?? '';
                $prefill_mobile = $user_info['mobile'] ?? '';
            }
        }

        $user_addresses = [];
        $active_delivery_address = null;
        $pin_get_amount_corrent = 0;

        if ($is_user_logged_in) {
            $addr_res = mysqli_query($con, "SELECT * FROM address WHERE userid='$userid' ORDER BY id DESC");
            if ($addr_res) {
                while ($arow = mysqli_fetch_assoc($addr_res)) {
                    $user_addresses[] = $arow;
                }
            }

            $deliv_res = mysqli_query($con, "SELECT * FROM deliver_address WHERE user_id='$userid' ORDER BY id DESC LIMIT 1");
            if ($deliv_res && mysqli_num_rows($deliv_res) > 0) {
                $deliv_row = mysqli_fetch_assoc($deliv_res);
                $cur_addr_id = (int)$deliv_row['address_id'];
                $cur_addr_q = mysqli_query($con, "SELECT * FROM address WHERE id='$cur_addr_id'");
                if ($cur_addr_q && mysqli_num_rows($cur_addr_q) > 0) {
                    $active_delivery_address = mysqli_fetch_assoc($cur_addr_q);
                }
            }

            if (!$active_delivery_address && !empty($user_addresses)) {
                $active_delivery_address = $user_addresses[0];
                $fst_id = (int)$active_delivery_address['id'];
                $fst_pin = $active_delivery_address['pin'];
                mysqli_query($con, "INSERT INTO deliver_address (user_id, address_id, pincode, status) VALUES ('$userid', '$fst_id', '$fst_pin', 1)");
            }

            if ($active_delivery_address) {
                $act_pin = $active_delivery_address['pin'];
                $_SESSION['address_pin'] = $act_pin;
                $fetch_pin_amt = mysqli_query($con, "SELECT * FROM pinamount WHERE pincode='$act_pin'");
                if ($fetch_pin_amt && mysqli_num_rows($fetch_pin_amt) > 0) {
                    $pin_row = mysqli_fetch_assoc($fetch_pin_amt);
                    $pin_get_amount_corrent = (float)($pin_row['price'] ?? 0);
                } else {
                    $pin_get_amount_corrent = 10;
                }
            }
        }
        ?>

        <main>
            <div class="container margin_60">
                <div class="row">
                    <!-- address details start -->
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="step first" style="background: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
                            <h3 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;">
                                1. User Info & Billing Address
                            </h3>

                            <?php if (!$is_user_logged_in): ?>
                            <ul class="nav nav-tabs" id="tab_checkout" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="login-tab" data-toggle="tab" href="#tab_2" role="tab" aria-controls="tab_2" aria-selected="true">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="register-tab" data-toggle="tab" href="#tab_1" role="tab" aria-controls="tab_1" aria-selected="false">Register</a>
                                </li>
                            </ul>
                            <div class="tab-content checkout p-3 border border-top-0 rounded-bottom">
                                <div class="tab-pane fade show active" id="tab_2" role="tabpanel" aria-labelledby="login-tab">
                                    <form id="loginForm">
                                        <div class="form-group mb-3">
                                            <label style="font-weight: 600; color: #475569;">Email Address</label>
                                            <input type="email" class="form-control" placeholder="Enter your email" name="uemail" id="uemail" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label style="font-weight: 600; color: #475569;">Password</label>
                                            <input type="password" class="form-control" placeholder="Enter password" name="password_in" id="password_in" required>
                                        </div>
                                        <button type="submit" class="btn_1 full-width" style="border-radius: 8px;">Login to Continue</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="tab_1" role="tabpanel" aria-labelledby="register-tab">
                                    <form action="checkout.php" method="POST">
                                        <div class="row">
                                            <div class="col-6 form-group mb-3">
                                                <input type="text" name="fname" class="form-control" placeholder="First Name" required>
                                            </div>
                                            <div class="col-6 form-group mb-3">
                                                <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="password" name="pwd" class="form-control" placeholder="Password" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" name="mobile" minlength="10" maxlength="10" class="form-control" placeholder="Mobile Number" required>
                                        </div>
                                        <input type="submit" name="reg" class="btn_1 full-width" value="Register & Continue" style="border-radius: 8px;">
                                    </form>
                                </div>
                            </div>

                            <?php else: ?>
                            <!-- Logged In User Address View -->
                            
                            <?php if ($active_delivery_address): ?>
                            <!-- Active Selected Address Card -->
                            <div id="active_address_box" class="p-3 mb-3" style="background: #f8fafc; border: 1.5px solid #22c55e; border-radius: 10px;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                    <div>
                                        <span class="badge bg-success" style="font-size: 12px; padding: 4px 8px; margin-bottom: 6px; display: inline-block;">Delivering to</span>
                                        <h5 style="margin: 0; font-weight: 700; color: #1e293b;"><?php echo htmlspecialchars($active_delivery_address['fname']); ?></h5>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="$('#address_list_box').slideToggle();" style="border-radius: 6px; font-weight: 600;">
                                        <i class="fa fa-pencil"></i> Change
                                    </button>
                                </div>
                                <p style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0;">
                                    <?php echo htmlspecialchars($active_delivery_address['flat']); ?>, <br>
                                    <?php echo htmlspecialchars($active_delivery_address['district']); ?>, <?php echo htmlspecialchars($active_delivery_address['state']); ?> - <strong><?php echo htmlspecialchars($active_delivery_address['pin']); ?></strong><br>
                                    <span style="color: #64748b;"><i class="fa fa-phone"></i> <?php echo htmlspecialchars($active_delivery_address['mobile']); ?></span>
                                </p>
                            </div>
                            <?php endif; ?>

                            <!-- Saved Addresses List (Collapsible / If Changing) -->
                            <div id="address_list_box" style="<?php echo ($active_delivery_address ? 'display: none;' : 'display: block;'); ?>">
                                <?php if (!empty($user_addresses)): ?>
                                <h6 style="font-weight: 700; color: #334155; margin-bottom: 12px;">Select Delivery Address:</h6>
                                <form method="POST" action="checkout.php?refid=<?php echo urlencode($raw_ref_id); ?>">
                                    <?php foreach ($user_addresses as $addr_item): 
                                        $is_chk = ($active_delivery_address && $active_delivery_address['id'] == $addr_item['id']) ? 'checked' : '';
                                    ?>
                                    <div class="card p-3 mb-2" style="border: 1px solid #cbd5e1; border-radius: 8px; cursor: pointer;">
                                        <label style="display: flex; gap: 12px; align-items: flex-start; margin: 0; cursor: pointer;">
                                            <input type="radio" name="addressset" value="<?php echo $addr_item['id']; ?>" <?php echo $is_chk; ?> style="margin-top: 4px; transform: scale(1.2);">
                                            <div style="flex-grow: 1;">
                                                <strong style="color: #0f172a;"><?php echo htmlspecialchars($addr_item['fname']); ?></strong> - <span style="color: #64748b;"><?php echo htmlspecialchars($addr_item['mobile']); ?></span><br>
                                                <small style="color: #475569;"><?php echo htmlspecialchars($addr_item['flat'] . ', ' . $addr_item['district'] . ', ' . $addr_item['state'] . ' - ' . $addr_item['pin']); ?></small>
                                            </div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                    <div style="display: flex; gap: 10px; margin-top: 14px;">
                                        <button type="submit" name="set_active_address_btn" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; flex: 1;">
                                            Use Selected Address
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="$('#new_address_form_box').slideToggle();" style="border-radius: 8px; font-weight: 600;">
                                            <i class="fa fa-plus"></i> Add New
                                        </button>
                                    </div>
                                </form>
                                <?php endif; ?>
                            </div>

                            <!-- Add New Address Form (Pre-filled with login credentials) -->
                            <div id="new_address_form_box" style="<?php echo (empty($user_addresses) ? 'display: block;' : 'display: none;'); ?> margin-top: 16px;">
                                <div class="card p-3" style="background: #f8fafc; border: 1.5px dashed #0284c7; border-radius: 10px;">
                                    <h6 style="font-weight: 700; color: #0284c7; margin-bottom: 14px;">
                                        <i class="fa fa-map-marker"></i> Add New Delivery Address
                                    </h6>
                                    <form method="POST" action="checkout.php?refid=<?php echo urlencode($raw_ref_id); ?>">
                                        <div class="row">
                                            <div class="col-md-6 form-group mb-2">
                                                <label style="font-size: 13px; font-weight: 600; color: #475569;">Full Name *</label>
                                                <input type="text" name="fname" class="form-control form-control-sm" placeholder="Your full name" value="<?php echo htmlspecialchars($prefill_name); ?>" required>
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                                <label style="font-size: 13px; font-weight: 600; color: #475569;">Mobile Number *</label>
                                                <input type="tel" name="mob" minlength="10" maxlength="10" class="form-control form-control-sm" placeholder="10-digit mobile" value="<?php echo htmlspecialchars($prefill_mobile); ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label style="font-size: 13px; font-weight: 600; color: #475569;">Email (optional)</label>
                                            <input type="email" name="email" class="form-control form-control-sm" placeholder="Email id" value="<?php echo htmlspecialchars($prefill_email); ?>">
                                        </div>
                                        <div class="form-group mb-2">
                                            <label style="font-size: 13px; font-weight: 600; color: #475569;">Shipping / Street Address *</label>
                                            <textarea name="shipping" class="form-control form-control-sm" rows="2" placeholder="House / Flat No, Street, Landmark" required></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group mb-2">
                                                <label style="font-size: 13px; font-weight: 600; color: #475569;">District / City *</label>
                                                <input type="text" name="district" class="form-control form-control-sm" placeholder="City / District" required>
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                                <label style="font-size: 13px; font-weight: 600; color: #475569;">State *</label>
                                                <input type="text" name="state" class="form-control form-control-sm" placeholder="State" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group mb-3">
                                                <label style="font-size: 13px; font-weight: 600; color: #475569;">Pincode *</label>
                                                <input type="number" name="pin" min="100000" max="999999" class="form-control form-control-sm" placeholder="6-digit Pincode" required>
                                            </div>
                                            <div class="col-md-6 form-group mb-3">
                                                <label style="font-size: 13px; font-weight: 600; color: #475569;">Country</label>
                                                <input type="text" name="country" class="form-control form-control-sm" value="India" readonly>
                                            </div>
                                        </div>
                                        <button type="submit" name="save_checkout_address" class="btn btn-success full-width" style="border-radius: 8px; font-weight: 700; padding: 10px;">
                                            <i class="fa fa-check"></i> Save & Deliver to This Address
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <?php endif; ?>

                        </div>
                    </div>
                    <!-- address details end -->

                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <script>
                    function deleteTemp(uid) {
                        $.ajax({
                            url: 'del_option.php',
                            type: 'GET',
                            data: {
                                uid: uid
                            },
                            success: function(response) {
                                console.log('Server response:', response);
                                location.reload();
                            },
                            error: function() {
                                alert('Error in AJAX call.');
                            }
                        });
                    }
                    </script>
                    <!-- payment end -->
                    <div class="col-lg-6 col-md-6">
                        <div class="step last">
                            <form action="final_checkout.php" method="POST" id="checkoutForm">
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;">
                                    2. Order Summary
                                </h3>
                                <div class="box_general summary" style="background: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">

                                    <ul style="list-style: none; padding: 0; margin: 0;">
                                         <?php 
                                         $chk_filter = "ref_id='$new_ref_id' OR encrypt_rid='$raw_ref_id' OR encrypt_rid='$new_ref_id' OR ref_id='$raw_ref_id'";
                                         $fetch_test = mysqli_query($con, "SELECT * FROM chekout WHERE $chk_filter");
                                         if (!$fetch_test || mysqli_num_rows($fetch_test) == 0) {
                                             $usrid_chk = $_SESSION['uid'] ?? 0;
                                             if (!empty($usrid_chk)) {
                                                 $chk_filter = "userid='$usrid_chk' ORDER BY id DESC LIMIT 1";
                                             }
                                         }

                                         $fetch_details = mysqli_query($con, "SELECT * FROM chekout WHERE $chk_filter");
                                         while ($get_values = mysqli_fetch_array($fetch_details)) { 
                                             $reference=$get_values['ref_id'];    
                                             $product_id = $get_values['pr_id'];
                                             $gst_pric = $get_values['gst_pri'];
                                             $price_id = $get_values['price_id'];
                                             $product_qty = $get_values['qty'];
                                             $product_current_price = $get_values['ct_py'];
                                             $old_prc = $get_values['old_prc'];
                                             $dis_pri= $get_values['dis_pri'];
                                         ?>
                                         <input type="hidden" name="ref_id" value="<?php echo $reference; ?>">
                                         <input type="hidden" name="product_id[]" value="<?php echo $product_id; ?>">
                                         <input type="hidden" name="product_gst[]" value="<?php echo $gst_pric; ?>">
                                         <input type="hidden" name="price_id[]" value="<?php echo $price_id; ?>">
                                         <input type="hidden" name="product_qty[]" value="<?php echo $product_qty; ?>">
                                         <input type="hidden" name="product_cr_price[]" value="<?php echo $product_current_price; ?>">
                                         <input type="hidden" name="product_old_price[]" value="<?php echo $old_prc; ?>">
                                         <input type="hidden" name="dis_price[]" value="<?php echo $dis_pri; ?>">
                                         <?php  }
                                     $s_total = 0;
                                     $total_discounts = 0;     
                                     $gst_pric = 0; 
                                     $price_total = 0;                                      
                                     $fetch_details2 = mysqli_query($con, "SELECT * FROM chekout WHERE $chk_filter");
                                         while ($get_values = mysqli_fetch_array($fetch_details2)) {
                                        $product_id = $get_values['pr_id'];
                                        $price_id = $get_values['price_id'];
                                        $product_name = $get_values['p_name'];
                                         $product_qty = $get_values['qty'];
                                         $product_current_price = (float)$get_values['ct_py'];
                                         $price_total = (float)$get_values['total'];
                                          $old_prc = (float)$get_values['old_prc'];
                                          $gstper = (float)$get_values['gstper'];
                                        $discounted_price= (float)$get_values['dis_pri'];
                                         $gst_pric = (float)$get_values['gst_pri'];
                                         $item=$get_values['items'];
                                         $product_img = $get_values['product_img'];
                                         $resolved_img = resolve_checkout_image($product_img, $product_id, $con);
                                         $chek_id=$get_values['id'];
                                         $subtotal=(float)$get_values['subtotal'];
                                         $total_discounts +=  $discounted_price;
                                         $reference=$get_values['ref_id'];
                                         $shiping_option=$get_values['ship_chrg'];

                                         $total_get_price = mysqli_query($con,"SELECT sum(gst_pri) as get_price , sum(dis_pri) as dis_price FROM chekout WHERE $chk_filter");
                                         $get__gst = mysqli_fetch_array($total_get_price);
                                          ?>
                                        <li class="pb-3 mb-3 border-bottom">
                                            <div style="display: flex; gap: 14px; align-items: center;">
                                                <img src="<?php echo htmlspecialchars($resolved_img); ?>" alt="<?php echo htmlspecialchars($product_name); ?>" style="width: 58px; height: 58px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc;" onerror="this.onerror=null; this.src='ovi-logo.png';">
                                                <div style="flex-grow: 1;">
                                                    <h6 style="margin: 0 0 4px 0; font-weight: 700; color: #0f172a; font-size: 14.5px;"><?php echo htmlspecialchars($product_name); ?></h6>
                                                    <small style="color: #64748b; font-size: 13px;">Qty: <strong><?php echo $product_qty; ?></strong> &times; <?=$_SESSION['selectedCurrency'] ?? '₹'; ?><?php echo number_format($product_current_price, 2); ?></small>
                                                </div>
                                                <div style="text-align: right; font-weight: 700; color: #0f172a; font-size: 15px;">
                                                    <?=$_SESSION['selectedCurrency'] ?? '₹'; ?><?php echo number_format($subtotal, 2); ?>
                                                </div>
                                            </div>
                                        </li>
                                        <?php }
                                            $subTotal_pin =  $price_total;
                                        ?>
                                        <li class="py-2 d-flex justify-content-between">
                                            <span style="color: #64748b;">Discount Applied</span>
                                            <span class="text-success" style="font-weight: 600;">
                                                <small class="text-muted">(-)</small> <?=$_SESSION['selectedCurrency'] ?? '₹'; ?><?php echo number_format($get__gst['dis_price'] ?? 0, 2); ?>
                                            </span>
                                            <input type="hidden" name="gst_price" value="<?=$gst_pric; ?>">
                                        </li>

                                        <li class="py-2 d-flex justify-content-between border-bottom pb-2">
                                            <span style="color: #64748b;">GST / Tax Amount</span>
                                            <span class="text-danger" style="font-weight: 600;">
                                                <small class="text-muted">(+)</small> <?=$_SESSION['selectedCurrency'] ?? '₹'; ?><?php echo number_format($get__gst['get_price'] ?? 0, 2); ?>
                                            </span>
                                        </li>

                                        <?php 
                                         $subTotal_pin =  $price_total;
                                        if (!empty($pin_get_amount_corrent)) {  ?>

                                        <li>Delivery Charge

                                            <span class="float-right">

                                                <?php 
                                                if( $shiping_option==0){
                                                    echo "<div class='text-success'> eligible for free Delivery </div>";
                                                }else{
                                                    if ($price_total >= 500) {
        
                                                        echo "<div class='text-success'> eligible for free Delivery </div>";
                                                    } else {
                                                       echo" <small class='text-muted'> (+)</small>";
                                                        echo $_SESSION['selectedCurrency'] . $pin_get_amount_corrent;
                                                    }
                                                }
    
                                                        ?>
                                            </span>

                                        </li>
                                        <?php  }else{ 



                                        }?>
                                        <?php
                                         if( $shiping_option==0){
                                            $price_total_with_ship = $price_total;
                                         }else{
                                            if ($price_total >= 499) {
   
                                                $price_total_with_ship = $price_total;
                                            } else {
                                               
                                                $price_total_with_ship = $price_total + $pin_get_amount_corrent;
                                            }
                                         }


?>
                                        <li>Total( disc+tax inc)<span
                                                class="float-right"><?=$_SESSION['selectedCurrency']; ?><?= $price_total_with_ship; ?></span>
                                        </li>
                                        <input type="hidden" class="shipping_amt_price" name="shipping_amt"
                                            value="<?php if (!empty($pin_get_amount_corrent)) { echo $pin_get_amount_corrent; } ?>">

                                        <div class="text-center">
                                            <input type="hidden" class="promo_discount1" id="promo_price"
                                                name="promo_amt">
                                            <span id="promo_error" class="text-danger h4"></span>
                                        </div>


                                        <div id="newsletter" class="news_coupon ml-3 mr-3 pl-4 pr-4">
                                            <div class="form-group">
                                                <input type="text" name="subs" id="email_newsletter"
                                                    class="form-control" placeholder="Apply Coupon">
                                                <input type="button" onclick="Find_coupon()" name="subscribe"
                                                    value="Apply" id="submit-newsletter"><small class="ml-2">Apply
                                                    coupon get discounts</small>
                                            </div>

                                        </div>



                                        <!-- <div class="text-center ml-3 mr-3 pl-4 pr-4" >
                                        <div class="info border p-3  "
                                        style="border-bottom:0.2px solid black;padding:5px;border-radius:1px;">
                                        </div>
                                        </div> -->

                                        <div class="text-center mb-2"><span id="discount-message"></span></div>


                                        <li class="text-dark">
                                            <h5>
                                                Order Total
                                                <span class="float-right total-cost">
                                                    <?=$_SESSION['selectedCurrency']; ?>
                                                    <span id="displayed-total"><?=$price_total_with_ship; ?></span>
                                                </span>
                                            </h5>
                                        </li>


                                    </ul>
                                    <input type="hidden" name="ref_id" value="<?php echo $new_ref_id; ?>">
                                    <input type="hidden" class="add_input" name="address_id" value="<?php echo $active_delivery_address['id'] ?? 0; ?>">
                                    <input type="hidden" class="add_input_pin" name="pincode_id" value="<?php echo $active_delivery_address['pin'] ?? ''; ?>">
                                    <input type="hidden" name="width_out_shipping" value="<?=$price_total; ?>">
                                    <input type="hidden" id="final_price_show" name="total_amt" value="<?php echo $price_total_with_ship; ?>">
                                    <input type="hidden" name="order_id" value="<?php echo $order_id ?? ''; ?>">
                                    <div class="form-group mt-3">
                                        <label class="container_check">I accept the <a href="#">Terms and conditions</a>.
                                            <input type="checkbox" id="termsCheckbox" checked required>
                                            <span class="checkmark"></span>
                                        </label>
                                        <div id="error-message" class="text-danger" style="display:none; font-size: 13px;"></div>
                                    </div>
                                    <button type="button" id="conform_btn" class="btn_1 full-width cart">CONFIRM AND PAY</button>
                                </div>

                            </form>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
                            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

                            <script>
                            document.getElementById("conform_btn").addEventListener('click', checkUser);

                            function checkUser() {
                                var isUserLoggedIn = <?php echo !empty($_SESSION['uid']) ? 'true' : 'false'; ?>;
                                var activeAddressId = <?php echo !empty($active_delivery_address['id']) ? (int)$active_delivery_address['id'] : 0; ?>;
                                const checkbox = document.getElementById('termsCheckbox');
                                const errorMessage = document.getElementById('error-message');
                                if (errorMessage) errorMessage.style.display = 'none';

                                if (!isUserLoggedIn) {
                                    toastr.error('Please login or register to continue checkout', 'Login Required');
                                    $('html, body').animate({ scrollTop: $('#tab_checkout').offset().top - 100 }, 500);
                                    return;
                                }

                                if (activeAddressId <= 0) {
                                    toastr.error('Please add or select a delivery address', 'Address Required');
                                    $('html, body').animate({ scrollTop: $('.step.first').offset().top - 100 }, 500);
                                    return;
                                }

                                if (checkbox && !checkbox.checked) {
                                    if (errorMessage) {
                                        errorMessage.style.display = 'block';
                                        errorMessage.textContent = 'You must accept the terms and conditions';
                                    } else {
                                        toastr.warning('You must accept the terms and conditions', 'Notice');
                                    }
                                    return;
                                }

                                createRazorpayOrder();
                            }

                            function createRazorpayOrder() {
                                var finalAmount = parseFloat($('#final_price_show').val() || $('#displayed-total').text() || '0');
                                if (finalAmount <= 0) {
                                    finalAmount = <?php echo (float)($price_total_with_ship ?? 0); ?>;
                                }
                                var amountInPaise = Math.round(finalAmount * 100);
                                openRazorpay(amountInPaise);
                            }

                            function openRazorpay(amount) {
                                console.log("Opening Razorpay with amount:", amount);

                                var options = {
                                    "key": "<?php echo htmlspecialchars($key_id ?? 'rzp_test_ecMbNe517quJcM'); ?>",
                                    "amount": amount,
                                    "currency": "INR",
                                    "name": "Karuda Computers",
                                    "description": "Product Purchase",
                                    "image": "ovi-logo.png",
                                    "handler": function(response) {
                                        console.log("Payment Successful:", response);
                                        if (response && response.razorpay_payment_id) {
                                            $('<input>').attr({
                                                type: 'hidden',
                                                name: 'razorpay_payment_id',
                                                value: response.razorpay_payment_id
                                            }).appendTo('#checkoutForm');
                                        }
                                        document.getElementById('checkoutForm').submit();
                                    },
                                    "prefill": {
                                        "name": "<?php echo htmlspecialchars($active_delivery_address['fname'] ?? ($_SESSION['uname'] ?? '')); ?>",
                                        "contact": "<?php echo htmlspecialchars($active_delivery_address['mobile'] ?? ''); ?>",
                                        "email": "<?php echo htmlspecialchars($active_delivery_address['email'] ?? ($user_info['email'] ?? '')); ?>"
                                    },
                                    "theme": {
                                        "color": "#305724"
                                    }
                                };

                                try {
                                    var rzp1 = new Razorpay(options);
                                    rzp1.on('payment.failed', function (response){
                                        toastr.error('Payment Failed: ' + (response.error.description || 'Please try again'), 'Payment Failed');
                                    });
                                    rzp1.open();
                                } catch(err) {
                                    console.error("Razorpay open error:", err);
                                    document.getElementById('checkoutForm').submit();
                                }
                            }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include('include/footer.php'); ?>
    </div>
    <?php include('include/sign_footer.php'); ?>
    <script>
    function Find_coupon() {
        let final_amount = "<?=$price_total_with_ship ?>";
        let discount_message = document.getElementById("discount-message");
        let email_newsletter = document.getElementById('email_newsletter').value;
        if (email_newsletter.length == 0) {
            toastr.error('please Enter valid Code ', 'Error');
        } else {
            let data = new FormData();
            data.append('coupon_code', email_newsletter.trim());

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "cp_aply.php", true);
            xhr.onload = function() {
                let response = JSON.parse(this.response);
                if (response.success) {
                    let discount_per = parseFloat(response.discount);
                    let discounted_price = final_amount * (1 - (discount_per / 100));
                    let act_dic = final_amount - discounted_price;
                    document.getElementById("displayed-total").textContent = discounted_price.toFixed(2);
                    discount_message.textContent = "Coupon Code Applied successfully";
                    discount_message.style.color = "green";
                    document.getElementById('promo_price').value = act_dic;
                    document.getElementById("final_price_show").value = discounted_price;
                    document.getElementsByClassName("news_coupon")[0].style.display = "none";
                } else {
                    discount_message.textContent = response.message;
                }
            };
            xhr.send(data);
        }
    }
    </script>      $.ajax({
                    type: 'POST',
                    url: 'get_pincode_amount.php',
                    data: {
                        pincode: address.pin
                    },
                    success: function(amountResponse) {
                        var pinAmount = parseFloat(amountResponse);
                        if (!isNaN(pinAmount)) {
                            $('.subtotal-line .shipping_amt').text('₹ ' +
                                pinAmount.toFixed(2));
                            $('.subtotal-line .shipping_amt_price').val(
                                pinAmount);
                            var subtotal = parseFloat($(
                                    '.subtotal-line .subtotal').text()
                                .replace('₹', '').trim());
                            var promoDiscount = parseFloat($(
                                '.promo_discount').text().replace(
                                '₹', '').trim());
                            calculateTotal(subtotal, pinAmount,
                                promoDiscount);
                        }
                    }
                });
            }
        });
    });
    </script>
</body>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger text-center">Add Delivery Address</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="pinForm" action="checkout_address.php" method="POST">
                    <div class="contact-form-container ">
                        <div class="row ">
                            <div class="col-lg-6">
                                <p class="form-row">
                                    <input type="text" name="fname" placeholder="Full name" class="form-control"
                                        required>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="form-row">
                                    <input type="tel" class="form-control" minlength="10" maxlength="10"
                                        title="Phone number must start with 9, 8, 7, or 6 followed by 9 digits"
                                        placeholder="Mobile Number"
                                        oninput="phoneNumberValidation(this, 'alternatePhoneError')"
                                        pattern="^[7896][0-9]{9}$" required name="mob" />
                                    <small id="alternatePhoneError" class="text-danger" style="display:none;">Please
                                        enter digits only.</small>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="form-row">
                                    <input type="text" name="email" placeholder="Email id" class="form-control"
                                        required>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="form-row">
                                    <input type="text" name="shipping" placeholder="Shipping / Resential Address"
                                        class="form-control" required>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="form-row">
                                    <input type="text" name="country" value="India" placeholder="Country"
                                        class="form-control" required>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="form-row">
                                    <input type="text" name="state" placeholder="state" class="form-control" required>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <p class="form-row">
                                    <input type="text" name="district" placeholder="District" class="form-control"
                                        required>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <span id="pinError" style="color: #800000;margin-buttom:5px;"></span>
                                <p class="form-row">
                                    <input type="number" name="pin" id="pin" placeholder="Pin code" class="form-control"
                                        required>
                                </p>
                            </div>
                            <div class="col-lg-12 text-right">
                                <p class="text-warning">
                                    <input type="checkbox" id="make_address" name="sts" value="1" required><label
                                        for="make_address">&nbsp; Make this my default address</label>
                                </p>
                            </div>
                        </div>
                        <div class='text-right'>
                            <p class="form-row">
                                <button class="btn btn-warning btn-submit" name="sumbit" type="submit">Add
                                    Address</button>
                            </p>
                        </div>
                    </div>
                </form>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                $(document).ready(function() {
                    $('#pinForm').submit(function(event) {
                        event.preventDefault();

                        var pin = $('#pin').val();

                        $.ajax({
                            url: 'check_pin_address.php',
                            type: 'GET',
                            data: {
                                pin: pin
                            },
                            success: function(response) {
                                if (response === 'invalid') {
                                    $('#pin').css('border', '3px solid #800000;');
                                    $('#pinError').text(
                                        'Shipping Temporarily Unavailable In This Pincode'
                                    );
                                } else {
                                    $('#pin').css('border-color', '');
                                    $('#pinError').text('');
                                    $('#pinForm').off('submit').submit();
                                }
                            }
                        });
                    });
                });
                -- >

                $('button[name="promo_submit"]').click(function(e) {
                    e.preventDefault();
                    var promocode = $('input[name="promocode"]').val();
                    $.ajax({
                        type: 'GET',
                        url: 'get_promo_amount.php',
                        data: {
                            promocode: promocode
                        },
                        success: function(response) {
                            if (response !== "error") {
                                $('.promo_discount').text(response);
                                $('.promo_discount1').val(response);

                                $('#promo_error').text('');
                                $('#promo_code_section').hide();
                                var subtotal = parseFloat($('.subtotal-line .subtotal').text()
                                    .replace('').trim());
                                var shippingAmtText = $('.subtotal-line .shipping_amt')
                                    .text();
                                var shippingAmt = parseFloat(shippingAmtText.replace('')
                                    .trim());
                                calculateTotal(subtotal, shippingAmt, parseFloat(response));
                            } else {
                                $('#promo_error').text('Invalid promo code');
                            }
                        }
                    });
                });

                $('.link-forward').click(function(e) {
                    e.preventDefault();
                    $('#promo_code_section').toggle();
                });
                </script>
                <script>
                function phoneNumberValidation(input, errorId) {
                    const phoneNumberPattern = /^[7896][0-9]{0,9}$/; // Allows partial valid numbers
                    const errorMessage = document.getElementById(errorId);
                    // Check for alphabetic characters
                    const hasAlphabet = /[a-zA-Z]/.test(input.value);
                    if (hasAlphabet) {
                        errorMessage.textContent = 'Alphabetic characters are not allowed. Please enter digits only.';
                        errorMessage.style.display = 'block';
                        input.classList.add('is-invalid');
                    } else {
                        // Remove any non-digit characters (excluding alphabet check)
                        input.value = input.value.replace(/\D/g, '');
                        // Validate the input against the pattern
                        if (!phoneNumberPattern.test(input.value) || input.value.length > 10) {
                            errorMessage.textContent =
                                'Invalid phone number. Please enter a valid 10-digit phone number starting with 9, 8, 7, or 6.';
                            errorMessage.style.display = 'block';
                            input.classList.add('is-invalid');
                        } else {
                            errorMessage.style.display = 'none';
                            input.classList.remove('is-invalid');
                        }
                    }
                }
                </script>
                <script src="https://accounts.google.com/gsi/client" async defer></script>
                <script src="https://apis.google.com/js/platform.js" async defer></script>
                <script src="js/checkout_script.js"></script>
            </div>
        </div>
    </div>
</div>

</html>