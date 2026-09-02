<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(0);
include('include/header.php');
include('dbconnect.php');
?>
<style>
/* ── Karuda Cyber Tech Auth Theme ── */
body {
    background-color: #F8FAFC !important;
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}
.kc-auth-wrapper {
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    background: url('img/karuda_bg.jpg') no-repeat center center;
    background-size: cover;
    position: relative;
}
.kc-auth-wrapper::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(135deg, rgba(11, 25, 44, 0.9) 0%, rgba(13, 71, 161, 0.8) 100%);
    z-index: 1;
}
.kc-auth-card {
    background: #ffffff;
    border: 2px solid #00BCD4;
    border-radius: 16px;
    padding: 40px;
    width: 100%;
    max-width: 450px;
    position: relative;
    z-index: 2;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}
.kc-auth-logo {
    text-align: center;
    margin-bottom: 30px;
}
.kc-auth-logo img {
    max-width: 180px;
}
.kc-auth-title {
    font-size: 24px;
    font-weight: 800;
    color: #0F172A;
    text-align: center;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.kc-auth-subtitle {
    text-align: center;
    color: #64748B;
    font-size: 14px;
    margin-bottom: 30px;
}
.kc-form-group {
    margin-bottom: 20px;
    position: relative;
}
.kc-form-group label {
    font-size: 13px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 8px;
    display: block;
}
.kc-form-control {
    width: 100%;
    height: 48px;
    background: #F1F5F9;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 10px 15px 10px 45px;
    font-size: 14px;
    color: #0F172A;
    font-weight: 500;
    transition: all 0.3s ease;
}
.kc-form-control:focus {
    background: #ffffff;
    border-color: #0070F3;
    outline: none;
    box-shadow: 0 0 0 4px rgba(0, 112, 243, 0.1);
}
.kc-form-icon {
    position: absolute;
    left: 15px;
    top: 38px;
    color: #64748B;
    font-size: 18px;
}
.kc-auth-btn {
    width: 100%;
    height: 48px;
    background: linear-gradient(135deg, #0D47A1, #0070F3);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}
.kc-auth-btn:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4);
    box-shadow: 0 8px 20px rgba(0, 188, 212, 0.4);
    transform: translateY(-2px);
}
.kc-auth-links {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #64748B;
}
.kc-auth-links a {
    color: #0070F3;
    font-weight: 700;
    text-decoration: none;
}
.kc-auth-links a:hover {
    color: #0D47A1;
    text-decoration: underline;
}
.kc-forgot-pwd {
    display: block;
    text-align: right;
    margin-top: -10px;
    margin-bottom: 20px;
    font-size: 12.5px;
}
.kc-forgot-pwd a {
    color: #94A3B8;
    text-decoration: none;
    font-weight: 600;
}
.kc-forgot-pwd a:hover {
    color: #0070F3;
}
.kc-divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 25px 0;
    color: #94A3B8;
    font-size: 13px;
    font-weight: 600;
}
.kc-divider::before, .kc-divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #E2E8F0;
}
.kc-divider:not(:empty)::before {
    margin-right: .25em;
}
.kc-divider:not(:empty)::after {
    margin-left: .25em;
}
</style>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <main>
            <div class="kc-auth-wrapper">
                <div class="kc-auth-card">
                    <div class="kc-auth-logo">
                        <a href="index.php"><img src="<?php echo $CON_LOGO; ?>" alt="Karuda Computers"></a>
                    </div>
                    <h2 class="kc-auth-title">Welcome Back</h2>
                    <p class="kc-auth-subtitle">Sign in to your Karuda Computers account</p>
                    
                    <form action="login1.php" method="POST">
                        <?php echo \App\Core\Csrf::field(); ?>
                        
                        <div class="kc-form-group">
                            <label>Email Address</label>
                            <i class="icon_mail_alt kc-form-icon"></i>
                            <input type="email" class="kc-form-control" name="uemail" id="email" required placeholder="Enter your email">
                        </div>

                        <div class="kc-form-group">
                            <label>Password</label>
                            <i class="icon_lock_alt kc-form-icon"></i>
                            <input type="password" class="kc-form-control" name="upwd" id="password" required placeholder="Enter your password">
                        </div>
                        
                        <div class="kc-forgot-pwd">
                            <a id="forgot" href="forgot.php">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" name="login" class="kc-auth-btn">Login Now</button>
                        
                        <div class="kc-auth-links">
                            Don't have an account? <a href="register.php">Sign up!</a>
                        </div>
                        
                        <div class="kc-divider">OR</div>
                        
                        <div class="col-12 mt-2">
                            <button type="button" class="btn btn-primary g_id_signin" data-logo_alignment="center"
                                data-theme="filled_blue" data-type="standard" data-size="large"
                                data-text="continue_with"
                                style="width: 100%; text-align: center; display:flex;justify-content:center;">
                            </button>
                            <span id="g_id_onload"
                                data-client_id="387790781909-g6n5mnandsi468363g9b9vl8rd69f5ev.apps.googleusercontent.com"
                                data-context="signup" data-ux_mode="popup" data-callback="handleCredentialResponse"
                                data-auto_prompt="false">
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        
        <?php include('include/footer.php') ?>
    </div>
    
    <form id="userInfoForm" enctype="multipart/form-data" action="email_register.php" method="POST">
        <input type="hidden" name="first_name" id="userfname">
        <input type="hidden" name="last_name" id="userlname">
        <input type="hidden" name="full_name" id="userfullname">
        <input type="hidden" name="email" id="useremailname">
        <input type="hidden" name="id_num" id="idname">
        <input type="hidden" name="email_token" id="usertoken">
        <input type="hidden" name="photo_link" id="photos">
    </form>
    
    <!-- COMMON SCRIPTS -->
    <script src="js/common_scripts.js"></script>
    <script src="js/functions.js"></script>
    <script src="assets/validate.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="js/script.js"></script>
</body>
</html>