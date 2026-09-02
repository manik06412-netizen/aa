<?php 
   session_start();
   error_reporting(0);  
   $userid=$_SESSION['uid'];
   ?>
<?php
   if (isset($_GET['refid'])) {
       $ref_id = $_GET['refid'];
       $_SESSION['refidd'] = $ref_id;
   }
   $new_ref_id = $_SESSION['refidd'];
      $_SESSION['uname'];
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
session_start();
 
    include("dbconnect.php");
    if(isset($_POST['reg'])){
        $fname=$_POST['fname'];
        $lname=$_POST['lname'];
        $email=$_POST['email'];
        $pwd=$_POST['pwd'];
        $sta='1';
        $mob=$_POST['mobile'];
        $pwd1=md5($pwd);
        $date=date("d/m/Y");
        $usrid=$_SESSION['uid'];
        $chek=mysqli_query($con,"SELECT * FROM user where email='$email' or mobile='$mob'");
        if(mysqli_num_rows($chek)){
            echo "<script>
            alert('EMAIL OR MOBILE ALREADY EXISTS!');
            window.location.href = 'checkout.php?refid=" . $_SESSION['refidd'] . "';
        </script>";
        }
        else{

            $insert=mysqli_query($con,"UPDATE user set fname='$fname',lname='$lname',email='$email',pwd='$pwd1',mobile='$mob',date='$date',status='$sta' where user_id='$usrid'");
            if($insert){
                $_SESSION['uname']=$fname;
                echo "<script>
                window.location.href = 'checkout.php?refid=" . $_SESSION['refidd'] . "';
            </script>";
            }else{
                echo "<script>
                alert('SORRY! Something went wrong.');
                window.location.href = 'checkout.php?refid=" . $_SESSION['refidd'] . "';
            </script>";
            }
        }
    }
    ?>

        <main>
            <div class="container margin_60">
                <div class="row">
                    <!-- address details start -->
                    <div class="col-lg-6 col-md-6">
                        <div class="step first">
                            <h3>1. User info and billing address</h3>
                            <?php if(checklogin()=='-'){ ?>
                            <ul class="nav nav-tabs" id="tab_checkout" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab_2" role="tab"
                                        aria-controls="tab_2" aria-selected="false">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab_1" role="tab"
                                        aria-controls="tab_1" aria-selected="false">Register</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab_3" role="tab"
                                        aria-controls="tab_3" aria-selected="false">Guest</a>
                                </li> -->
                            </ul>
                            <div class="tab-content checkout">
                                <!-- register start -->
                                <div class="tab-pane fade show" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="checkout.php" method="POST">
                                                <div class="form-group">
                                                    <input type="email" name="email" class="form-control"
                                                        placeholder="Email" required>
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" name="pwd" class="form-control"
                                                        placeholder="Password" required>
                                                </div>
                                                <hr>
                                                <div class="row no-gutters">
                                                    <div class="col-6 form-group pr-1">
                                                        <input type="text" name="fname" class="form-control"
                                                            placeholder="Name" required>
                                                    </div>
                                                    <div class="col-6 form-group pl-1">
                                                        <input type="text" name="lname" class="form-control"
                                                            placeholder="Last Name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="mobile" minlength="10" maxlength="10"
                                                        id="phoneNumber"
                                                        onkeypress="return phoneNumberValidation(event)"
                                                        class="form-control" placeholder="Mobile number" required>
                                                    <p id='vaid'></p>
                                                </div>
                                                <hr>
                                                <div class="form-group">
                                                    <input type="submit" name="reg" class="btn_1 full-width"
                                                        value="Register">
                                                </div>
                                            </form>
                                            <div class="text-center mt-2"> (OR)</div>
                                            <button type="button" class="btn btn-primary g_id_signin"
                                                data-logo_alignment="center" data-theme="filled_blue"
                                                data-type="standard" data-size="medium" data-text="continue_with"
                                                style="width: 100%; text-align: center; display:flex;justify-content:center;">

                                            </button>

                                            <span id="g_id_onload"
                                                data-client_id="387790781909-g6n5mnandsi468363g9b9vl8rd69f5ev.apps.googleusercontent.com"
                                                data-context="signup" data-ux_mode="popup"
                                                data-callback="handleCredentialResponse" data-auto_prompt="false">
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <!-- /tab_2 -->



                                <div class="tab-pane fade show active" id="tab_2" role="tabpanel"
                                    aria-labelledby="tab_2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="loginForm">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" placeholder="Email"
                                                        name="uemail" id="uemail">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" class="form-control" placeholder="Password"
                                                        name="password_in" id="password_in">
                                                </div>
                                                <div id="forgot_pw">
                                                    <div class="form-group">
                                                        <input type="email" class="form-control" name="email_forgot"
                                                            id="email_forgot" placeholder="Type your email">
                                                    </div>
                                                    <p>A new password will be sent shortly.</p>
                                                    <div class="text-center">
                                                        <input type="submit" value="Reset Password" class="btn_1">
                                                    </div>
                                                </div>
                                                <hr>
                                                <input type="submit" class="btn_1 full-width" value="Login">
                                            </form>
                                            <!--  -->
                                            <div class="text-center mt-2"> (OR)</div>
                                            <button type="button" class="btn btn-primary g_id_signin"
                                                data-logo_alignment="center" data-theme="filled_blue"
                                                data-type="standard" data-size="medium" data-text="continue_with"
                                                style="width: 100%; text-align: center; display:flex;justify-content:center;">

                                            </button>

                                            <span id="g_id_onload"
                                                data-client_id="387790781909-g6n5mnandsi468363g9b9vl8rd69f5ev.apps.googleusercontent.com"
                                                data-context="signup" data-ux_mode="popup"
                                                data-callback="handleCredentialResponse" data-auto_prompt="false">
                                            </span>
                                            <form id="userInfoForm" enctype="multipart/form-data"
                                                action="email_register.php" method="POST">
                                                <input type="hidden" name="first_name" id="userfname">
                                                <input type="hidden" name="last_name" id="userlname">
                                                <input type="hidden" name="full_name" id="userfullname">
                                                <input type="hidden" name="email" id="useremailname">
                                                <input type="hidden" name="id_num" id="idname">
                                                <input type="hidden" name="email_token" id="usertoken">
                                                <input type="hidden" name="photo_link" id="photos">
                                            </form>
                                        </div>
                                    </div>

                                </div>





                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script>
                                $(document).ready(function() {
                                    $('#loginForm').submit(function(e) {
                                        e.preventDefault();

                                        $.ajax({
                                            type: 'POST',
                                            url: 'check_login.php',
                                            data: $(this).serialize(),
                                            dataType: 'json',
                                            success: function(response) {
                                                if (response.status == 'success') {

                                                    location.reload();
                                                } else {
                                                    alert(response.message);
                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('AJAX Error: ' + status,
                                                    error);
                                            }
                                        });
                                    });
                                });
                                </script>
                                <!-- /tab_3 -->
                                <!-- <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="intro">
                                                <h3 class="box-title">Guest Checkout </h3>
                                                <a href="guset_reg.php?guest='guest'"
                                                    class="btn_1 full-width cart">Continue
                                                    as Guest</a>
                                            </div>
                                        </div>
                                    </div>

                                </div> -->
                            </div>
                            <?php }else{ ?>
                            <?php if(check_address() > 0){ }else{ ?>
                            <?php if(checkaddress()==3){ ?>
                            <a href="javascript:void(0);" class="h5 btn btn-warning" onclick="showToast()">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add new Address
                            </a>
                            <?php }else{ ?>
                            <a href="#" class="h5 btn btn-warning" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add new Address
                            </a><?php }  ?>
                            <?php include('dbconnect.php');
                                $address=mysqli_query($con,"SELECT * from address where  userid='$userid'");
                                while($getaddress=mysqli_fetch_array($address)){
                                    $nf=$getaddress['fname'];
                                
                                $mb=$getaddress['mobile'];
                                $flt=$getaddress['flat'];
                                $are=$getaddress['country'];
                                $pin=$getaddress['pin'];
                                $district=$getaddress['district'];
                                $ste=$getaddress['state'];
                                $st=$getaddress['status'];
                                $adddid=$getaddress['id'];
                             ?>
                            <div class="box-content main_address">
                                <form id="addressForm">
                                    <div class="contain-product right-info-layout contain-product__right-info-layout">
                                        <div class="info border pl-4 p-3 mt-2"
                                            style="background-color:white;border-bottom:0.1px solid black;padding:5px;border-radius:3px;">
                                            <input type="radio" name="addressset" value="<?php echo $adddid; ?>"
                                                <?php if($st==1){ ?> checked <?php } ?> style="transform: scale(1.5);">
                                            <b>
                                                <h5 class="product-title"><a href="#"
                                                        class="pr-name"><?php echo $nf ."."; ?></a>
                                                </h5>
                                            </b>

                                            <address>
                                                <?php echo $flt ." , <br>".$district." ,".$ste." ,".$are."-" .$pin."."; ?><br>
                                                Phone No. <?php echo $mb; ?> .
                                            </address>

                                            <div class="text-right">
                                                <a href="edit_address.php?id=<?php echo $adddid; ?>"
                                                    class="text-right">Edit
                                                    Address</a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <?php } ?>
                            <?php if(checkaddress()!=0){ ?>
                            <div class="login-on-checkout main_address mt-4">
                                <p class="form-row text-right">
                                    <button type="submit" value="Submit" name="btn-sbmt"
                                        class="btn_1 full-width cart">Use
                                        this
                                        address</button>
                                </p>
                            </div>
                            <?php } ?>
                            </form>
                            <?php } }?>
                            <!--  -->



                            <div class="box-content box_contentt" <?php if(check_address() > 0){ }else{ ?>
                                style="display:none" <?php } ?>>
                                <?php include('dbconnect.php');
                                    $pin_get_amount_corrent=0;

                                    $gst_corrent=mysqli_query($con,"SELECT * FROM deliver_address where user_id='$userid'");
                                    if(mysqli_num_rows($gst_corrent)){
                                    $grow=mysqli_fetch_array($gst_corrent);
                              $cor_id=$grow['address_id'];

                                    $address=mysqli_query($con,"SELECT * from address where  id='$cor_id'");
                                    if($getaddress=mysqli_fetch_array($address)){
                                    $nf=$getaddress['fname'];
                                    $mb=$getaddress['mobile'];
                                    $flt=$getaddress['flat'];
                                    $district=$getaddress['district'];
                                     $pin=$getaddress['pin'];
                                     $are=$getaddress['country'];
                                    $ste=$getaddress['state'];
                                    $st=$getaddress['status'];
                                    $adddid=$getaddress['id'];

                                    $fetch_pin_amt=mysqli_query($con,"SELECT * from pinamount where pincode='$pin'");
                                    if ($fetch_pin_amt && mysqli_num_rows($fetch_pin_amt) > 0) {
                                        $get_amt_pinquery = mysqli_fetch_array($fetch_pin_amt);
                                        $pin_get_amount_corrent = $get_amt_pinquery['price'];
                                        
                                    
                                    } else {
                                        $pin_get_amount_corrent='1';
                                       
                                    }

                                    } } ?>



                                <div class="contain-product right-info-layout  contain-product__right-info-layout">
                                    <div class="info border p-3"
                                        style="background-color:white;border-bottom:0.2px solid black;padding:5px;border-radius:3px;">
                                        <div class="text-right">
                                            <a href="address_change.php?g_id=<?php echo $adddid ?>"
                                                class="send_link_option text-primary text-right">Change Address</a>
                                        </div>
                                        <h5 class="product-title"><a href="#" class="pr-name" id="addressTitle">
                                                <?php echo $nf ."."; ?>
                                            </a>
                                        </h5>

                                        <address id="addressDetails">
                                            <?php echo $flt ." , <br>".$district." ,".$ste." ,".$are."-" .$pin."."; ?><br>
                                            Phone No: <?php echo $mb; ?> .
                                        </address>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /step -->
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
                                <h3>2. Order Summary</h3>
                                <div class="box_general summary">
                                    <input type="hidden" name="ref_id" value="<?php echo $new_ref_id; ?>">
                                    <ul>
                                        <?php 
                                    $s_total = 0;
                                    $total_discounts = 0;                                            
                                       $fetch_details = mysqli_query($con, "SELECT * FROM chekout WHERE ref_id='$new_ref_id'");
                                        while ($get_values = mysqli_fetch_array($fetch_details)) {
                                       $product_id = $get_values['pr_id'];
                                       $price_id = $get_values['price_id'];
                                       $product_name = $get_values['p_name'];
                                        $product_qty = $get_values['qty'];
                                        $product_current_price = $get_values['ct_py'];
                                        $price_total = $get_values['total'];
                                      $old_prc = $get_values['old_prc'];
                                       $gstper = $get_values['gstper'];
                                       $discounted_price= $get_values['dis_pri'];
                                        $gst_pric= $get_values['gst_pri'];
                                        $item=$get_values['items'];
                                        $product_img = $get_values['product_img'];
                                        $chek_id=$get_values['id'];
                                        $subtotal=$get_values['subtotal'];
                                       // $discounted_price = $get_values['discounted_price'];
                                        $total_discounts +=  $discounted_price;
                                        $shiping_option=$get_values['ship_chrg'];
                                         ?>
                                        <li>
                                            items: <?php echo $item; ?>
                                            <span class="float-right">
                                                <ins>
                                                    <span class="price-amount">
                                                        <span
                                                            class="currencySymbol"><?=$_SESSION['selectedCurrency']; ?></span>
                                                        <?php echo $subtotal; ?>
                                                    </span>
                                                </ins>
                                            </span>
                                            <input type="hidden" name="product_id[]" value="<?php echo $product_id; ?>">
                                            <input type="hidden" name="product_gst[]" value="<?php echo $gst_pric; ?>">
                                            <input type="hidden" name="price_id[]" value="<?php echo $price_id; ?>">
                                            <input type="hidden" name="product_qty[]"
                                                value="<?php echo $product_qty; ?>">
                                            <input type="hidden" name="product_cr_price[]"
                                                value="<?php echo $product_current_price; ?>">
                                            <input type="hidden" name="product_old_price[]"
                                                value="<?php echo $old_prc; ?>">
                                        </li>
                                        <?php }
                                            $subTotal_pin =  $price_total;
                                        ?>
                                        <li>
                                            <span class="text-dark">Discount Applied</span>
                                            <span class="float-right text-success"><small class="text-muted">(-)
                                                </small><?=$_SESSION['selectedCurrency']; ?><?=$total_discounts ?></span>
                                            <input type="hidden" name="gst_price" value="<?=$gst_pric; ?>">
                                        </li>

                                        <li>
                                            <span class="text-dark">Tax Amount</span>
                                            <span class="float-right text-danger"><small class="text-muted">(+)
                                                </small>

                                                <?=$_SESSION['selectedCurrency']; ?><?=$gst_pric ?></span>
                                            <input type="hidden" name="dis_price" value="<?=$total_discounts; ?>">
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
                                    <input type="hidden" id="address_id_check" class="add_input" name="address_id"
                                        value="<?php echo $adddid ?>" required>
                                    <input type="hidden" class="add_input_pin" name="pincode_id"
                                        value="<?php echo $pin ?>">

                                    <input type="hidden" name="width_out_shipping" value="<?=$price_total; ?>">

                                    <input type="hidden" id="final_price_show" name="total_amt"
                                        value="<?php echo $price_total_with_ship; ?>">

                                    </ul>
                                    <div class="form-group">
                                        <label class="container_check">Please accept <a href="#">Terms and
                                                conditions</a>.
                                            <input type="checkbox" checked required>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <button type="button" id="conform_btn" onclick="Check_user()"
                                        class="btn_1 full-width cart">CONFIRM AND
                                        PAY</button>
                                </div>
                                <script>
                                document.getElementById('checkoutForm').addEventListener('submit', function(event) {
                                    var addressId = document.querySelector('input[name="address_id"]').value;
                                    var paymentId = document.querySelector('input[name="payid"]').value;
                                    if (!addressId || !paymentId) {
                                        event.preventDefault();
                                        alert('Address and payment option must be selected');
                                    }
                                });
                                </script>
                            </form>
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
            alert("please Enter valid Code !")
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
    </script>

    <script>
    function phoneNumberValidation(event) {
        var phoneNumber = document.getElementById('phoneNumber').value;
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode < 48 || charCode > 57) {
            return false;
        }
        if (phoneNumber.length === 0) {
            if (charCode >= 49 && charCode <= 53) {
                return false;
            }
        }
        if (phoneNumber.length === 10) {
            return false;
        }
        return true;
    }
    // 
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
                    $('.promo_discount').text('₹ ' + response);
                    $('.promo_discount1').val(response);

                    $('#promo_error').text('');
                    $('#promo_code_section').hide();
                    var subtotal = parseFloat($('.subtotal-line .subtotal').text()
                        .replace('₹', '').trim());
                    var shippingAmtText = $('.subtotal-line .shipping_amt')
                        .text();
                    var shippingAmt = parseFloat(shippingAmtText.replace('₹', '')
                        .trim());
                    calculateTotal(subtotal, shippingAmt, parseFloat(response));
                } else {
                    $('#promo_error').text('Invalid promo code');
                }
            }
        });
    });
    // 
    function Check_user() {
        let address_id = sessionStorage.getItem('address_set');
        let address_id_check = document.getElementById("address_id_check").value;
        let conform_btn = document.getElementById("conform_btn");

        console.log('address_id:', address_id);
        console.log('address_id_check:', address_id_check);

        if (address_id_check.length > 0) {
            if (address_id) {
                conform_btn.setAttribute("type", "submit");
            } else {
                conform_btn.setAttribute("type", "button");
                alert("please select your delivery address");
            }
        } else {
            alert("Please login to continue the checkout!");
        }
    }


    $('#addressForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var address_id = $('input[name="addressset"]:checked').val();
        sessionStorage.setItem('address_set', address_id);
        $.ajax({
            type: 'POST',
            url: 'get_address.php',
            data: formData,
            success: function(response) {

                var address = JSON.parse(response);
                $('#addressTitle').text(address.fname + ', ' + address.pin);
                $('#addressDetails').html(address.flat + ', ' + address.city + '<br>' +
                    address.area + ', ' + address.state + '<br>Phone No. ' + address
                    .mobile);
                location.reload();
                $('.box_contentt').show();
                $('.main_address').hide();
                $('.main_address').val(address.id);
                $('.add_input_pin').val(address.pin);
                $('.add_input').val(address.id);
                $('#result_add').text('Deliver to');
                var newHref = 'address_change.php?g_id=' + address.id;
                $('a.send_link_option').attr('href', newHref);

                $.ajax({
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
                }); -->

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