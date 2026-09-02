<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(0);
require ('include/header.php');
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <style>
            .deal-layout{
        margin-top: 3rem;
        border: 2px solid #ff810f;
    padding: 17px 18px 29px;
    background-color: #fafafa;
    }
    </style>
<body class="biolife-body">
<header class="kc-header-container">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
    </header>

    <div class="sub_header_in sticky_header">
        <div class="container">
            <h1>Delete account</h1>
        </div>
    </div>

    <!-- Navigation section -->
    <div class="container">
        <!-- <nav class="biolife-nav nav-86px">
            <ul>
                <li class="nav-item"><a href="index.php" class="permal-link">Home</a></li>
                <li class="nav-item"><a href="myprobile.php" class="permal-link">MyAccount</a></li>
                <li class="nav-item"><span class="current-page">Remove Account</span></li>
            </ul>
        </nav> -->
    </div>

    <div class="page-contain contact-us">
        <!-- Main content -->
        <div id="main-content" class="main-content ">
            <div class="container">
                <div class="row" style="text-align: center;">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                    <!-- Contact form -->
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" style="margin: 0 auto;">
                        <div class="contact-form-container deal-layout">
                            <h4 class="box-title">Remove Account</h4>
                            <form action="remove1.php" name="frm-contact" method="POST">
                                <div id="step1">
                                    <p class="frst-desc">*you can send only request for deleting account it will take 15 workings day to verify,Share your reason for deleting your account,we will send to admin
                                    </p>
                                    <p class="form-row">
                                        <textarea class="w-100 form-control" name="reason" id="mes-1" cols="30" rows="9" placeholder="Reason For Delete" required></textarea>
                                    </p>
                                    <p class="form-row">
                                        <input type="button" class="btn btn-submit btn-primary" value="Next" onclick="validateStep('step1')">
                                    </p>
                                </div>
                                <div id="step2" style="display: none;">
                                    <p class="form-row">
                                        <input type="password" name="pwd" value="" placeholder="Enter your Registered Password" class="txt-input form-control" required>
                                    </p>
                                    <p class="form-row">
                                        <input type="button" class="btn btn-submit btn-info" value="Previous" onclick="prevStep('step1')">
                                        <input type="button" class="btn btn-submit btn-primary" value="Next" onclick="validateStep('step2')">
                                    </p>
                                </div>
                                <div id="step3" style="display: none;">
                                    <p class="form-row">
                                        <input type="tel" name="mobile" value="" placeholder="Phone Number" class="txt-input form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                    </p>
                                    <p class="form-row">
                                        <input type="button" class="btn btn-submit btn-success" value="Previous" onclick="prevStep('step2')">
                                        <button class="btn btn-block btn-danger" type="submit" name="subt">Delete Account</button>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                </div>
            </div>
        </div>
    </div>

    <?php include ('include/footer.php') ?>
    <?php include ('include/sign_footer.php'); ?>
    <script>
        function validateStep(stepId) {
            var stepInputs = document.querySelectorAll('#' + stepId + ' input, #' + stepId + ' textarea');
            var isValid = true;

            stepInputs.forEach(function(input) {
                if (input.value.trim() === '') {
                    isValid = false;
                }
            });

            if (!isValid) {
                alert('Please fill in all fields before proceeding.');
            } else {
                document.getElementById(stepId).style.display = 'none';
                document.getElementById('step' + (parseInt(stepId.substr(4)) + 1)).style.display = 'block';
            }
        }

        function prevStep(stepId) {
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step3').style.display = 'none';
            document.getElementById(stepId).style.display = 'block';
        }
    </script>
</body>
</html>
