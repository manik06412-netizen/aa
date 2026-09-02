<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
   require('include/header.php');
   ?>
<?php 
     if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }
    
    include("dbconnect.php");
    
    if (isset($_POST['subt']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
        $_SESSION['comtmgs'] = "Thankyou, Review sended succesfully!";
        $name = $_POST['name'];
        $comt = $_POST['comment'];
        $date = date('m/d/Y');
        $insert = mysqli_query($con, "INSERT INTO complaint values(null, '$name', '$comt', '$date')");
    
        // Regenerate a new token to prevent resubmission on refresh
        $_SESSION['token'] = bin2hex(random_bytes(32));
    
        if ($insert) {
            echo "<script>alert('Complaint registered,we will response soon!');</script>";
        } else {
         
        }
    } else {
       
    }
    ?>
<body>

    <div id="page">

        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
            <!-- /container -->
        </header>
        <!-- /header -->

        <div class="sub_header_in sticky_header">
            <div class="container text-center">
                <h1>Complaint</h1>
            </div>
            <!-- /container -->
        </div>
        <!-- /sub_header -->

        <main>
            <!-- /map -->
            <div class="container margin_60_35">
                <div class="row justify-content-center">

                    <div class="col-xl-7 col-lg-6 pr-xl-5">
                        <div class="main_title_3">
                            <span></span>
                            <h2>Raise a Complaint</h2>
                            <p>Please use the form below to share your concern with us and we will come back to you with the right solution.</p>
                        </div>
                        <div id="message-contact"></div>
                        <form action="" name="frm-contact" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                                        <label>Name</label>
                                        <input class="form-control" type="text" id="name_contact" name="name" required>
                                    </div>
                                </div> 

                            </div>
                   
                            <div class="form-group">
                                <label>share Complaint</label>
                                <textarea class="form-control" id="message_contact" name="comment"
                                    style="height:150px;" required></textarea>
                            </div>

                            <p class="add_top_30"><input type="submit" name="subt" value="Submit" class="btn_1 rounded"
                                    id="submit-contact"></p>
                        </form>
                    </div>
                    <div class="col-xl-5 col-lg-12 pl-xl-5">
                        <div class="box_contacts">
                            <i class="ti-support"></i>
                            <h2>Need Help?</h2>
                            <a href="#0">+ 61 23 8093 3400</a> - <a href="#0">help@classified.com</a>
                        </div>
                        <div class="box_contacts">
                            <i class="ti-help-alt"></i>
                            <h2>Questions?</h2>
                            <a href="#0">+ 61 23 8093 3400</a> - <a href="#0">info@classified.com</a>
                        </div>
                      
                    </div>
                </div>
            </div>
            <!-- /container -->
        </main>
        <!--/main-->
        <?php include('include/footer.php'); ?>
        <!--/footer-->
    </div>
    <?php include('include/sign_footer.php'); ?>
</body>

</html>