<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
error_reporting(0);
require('include/header.php');
?>
<style>
    .custom-ul {
        /* Ensures bullet points are squares */
        /* Adds padding to the left of the list */
    }
    .custom-ul li {
        list-style-type: square; 
        margin-bottom: 10px; 
        padding-left: 30px;
    }
</style>
<body>

    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <div class="sub_header_in">
            <div class="container text-center">
                <h1>About Karuda Computers</h1>
                <p>Your trusted destination for High-Performance Gaming Rigs, Workstation PCs, and Certified Hardware across India.</p>
            </div>
        </div>

        <main>
            <div class="container margin_60_35">
                <div class="row">
                    <div class="col-12 custom-ul">
                        <p><?php echo isset($ABOUT_CONTENT) ? $ABOUT_CONTENT : ''; ?></p>
                    </div>
                </div>
            </div>
        </main>

        <footer id="footer">
            <?php include('include/footer.php'); ?>
        </footer>
    </div>

    <?php include('include/sign_footer.php'); ?>
</body>
</html>