<?php 
session_start();
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<style>
.k1 {
    font-weight: bold;
    text-shadow: 0 7px 7px rgba(0, 0, 0, 0.2);
}

.card-first {
    margin-top: 18rem;

}

.crd-in {
    font-weight: bold;
    font-size: 10rem;
}

.bot {
    margin: 5rem 0px;
}

.firt-one {
    border: 2px solid black;
    border-radius: 5px;

}

.firt-one:hover {
    border: 2px solid #800000;
    box-shadow: 20px 20px 20px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
}

.product-thumnail {
    border-radius: 40px;
}

.categories {
    text-transform: capitalize;
    font-weight: bold;
}

.tel {
    font-weight: bold;
    color: #800000;
    font-size: 15px;
}
</style>
<?php  include('includes/header.php'); ?>

<body class="biolife-body">

    <!-- Preloader -->
    <div id="biof-loading">
        <div class="biof-loading-center">
            <div class="biof-loading-center-absolute">
                <div class="dot dot-one"></div>
                <div class="dot dot-two"></div>
                <div class="dot dot-three"></div>
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <?php include('includes/navbar.php'); ?>
    <!--Hero Section-->
    <div class="hero-section hero-background">
        <h1 class="page-title">Edit Profile</h1>
    </div>

    <!--Navigation section-->
    <div class="container">
        <nav class="biolife-nav nav-86px">

        </nav>
    </div>

    <div class="page-contain contact-us">
        <?php 
            include("dbconnect.php");
            $user_id=$_SESSION['uid'];
            $sel=mysqli_query($con,"SELECT * FROM user where user_id='$user_id'");
            if($seli=mysqli_fetch_array($sel)){
               $eid=$seli['email'];
               $fname=$seli['fname'];
               $mob=$seli['mobile'];
               $lname=$seli['lname'];
            }
         ?>

        <!-- Main content -->
        <div class="container">
            <div class="row">

                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <div class="contain-product deal-layout contain-product__deal-layout">
                        <div class="contact-form-container ">
                            <form action="edit1.php" name="frm-contact" method="POST">
                                <p class="form-row">
                                    <?php 
                                    if(isset($_SESSION['mgs'])){ ?>
                                <div class="alert alert-success alert-dismissible text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong><?php echo $_SESSION['mgs'] ; ?>!</strong>
                                    <?php unset($_SESSION['mgs']); ?>
                                </div>
                                <?php } ?>
                                </p>
                                <p class="form-row">
                                    <input type="text" name="fname1" value="<?php echo $fname; ?>"
                                        placeholder="Enter First Name" class="txt-input" required>
                                </p>
                                <p class="form-row">
                                    <input type="text" name="lname1" value="<?php echo $lname; ?>"
                                        placeholder="Enter last Name" class="txt-input" required>
                                </p>
                                <p class="form-row">
                                    <input type="email" name="email1" value="<?php echo $eid; ?>"
                                        placeholder="Phone Number" class="txt-input" required readonly>
                                </p>
                                <p class="form-row">
                                    <input type="text" name="mobile1" value="<?php echo $mob; ?>"
                                        placeholder="Phone Number" class="txt-input" required>
                                </p>
                                <p class="form-row">
                                    <button class="btn btn-submit" name="reg1" type="submit">Update</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3"></div>
                <div class="col-lg-12 bot"></div>
            </div>

        </div>

    </div>



    </div>

    <?php include('includes/footer.php'); ?>
    <!-- Scroll Top Button -->
    <a class="btn-scroll-top"><i class="biolife-icon icon-left-arrow"></i></a>

    <script src="assets/js/jquery-3.4.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.countdown.min.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/jquery.nicescroll.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/biolife.framework.js"></script>
    <script src="assets/js/functions.js"></script>
</body>

</html>