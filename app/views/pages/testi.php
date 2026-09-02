<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
   require('include/header.php');
   ?>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>
        <div class="sub_header_in sticky_header">
            <div class="container">
                <h1>Testimonial</h1>
            </div>
        </div>
        <main class="main_testi" style="background-color:white;">
            <div class="container margin_60_35">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 pr-xl-5">
                        <div class="main_title_3 mb-2">
                            <span></span>
                            <p>Thank you for taking the time to complete our testimonial form.</p>
                        </div>
                        <div id="message-contact"></div>
                        <form action="add_testi.php" name="frm-contact" method="POST">
                            <?php echo \App\Core\Csrf::field(); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" type="text" id="name_contact" name="name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <input class="form-control" type="text" id="email_contact" name="designation" required>
                                    </div>
                                </div>
                                <!-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input class="form-control" type="file" id="phone_contact" name="mobile">
                                    </div>
                                </div> -->
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control" id="message_contact" name="message"
                                    style="height:150px;" required></textarea>
                            </div>
                            <p class="add_top_30"><input type="submit" name="subt" value="Submit" class="btn_1 rounded"
                                    id="submit-contact"></p>
                        </form>
                    </div>
                    <div class="col-xl-5 col-lg-12 pl-xl-5">
                    <img src="reviews.avif" alt="" class="w-100 pb-3" style="align-items: center; padding-top:20px;margin-top:80px;">
                    </div>
                </div>
            </div>
        </main>
        <?php include('include/footer.php'); ?>
    </div>
    <?php include('include/sign_footer.php'); ?>
</body>
</html>