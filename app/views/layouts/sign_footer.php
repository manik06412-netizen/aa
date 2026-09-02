<!-- ══════════════════════════════════════════════════════════════
     KARUDA COMPUTERS — GLOBAL INSTANT LOGIN & AUTH MODAL
══════════════════════════════════════════════════════════════ -->
<style>
.kc-auth-modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    z-index: 999999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.kc-auth-modal-overlay.show {
    opacity: 1;
}
.kc-auth-modal-card {
    background: #ffffff;
    border-radius: 24px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0,0,0,0.05);
    overflow: hidden;
    transform: scale(0.95) translateY(20px);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
}
.kc-auth-modal-overlay.show .kc-auth-modal-card {
    transform: scale(1) translateY(0);
}
.kc-auth-modal-header {
    padding: 32px 32px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.kc-auth-modal-header h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.5px;
    font-family: 'Outfit', sans-serif;
}
.kc-auth-modal-header h3 i {
    color: #3b82f6;
    font-size: 22px;
}
.kc-auth-close-btn {
    background: #f1f5f9;
    border: none;
    color: #64748b;
    font-size: 20px;
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.kc-auth-close-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
    transform: rotate(90deg);
}
.kc-auth-modal-body {
    padding: 0 32px 32px;
    font-family: 'Inter', sans-serif;
}
.kc-auth-prompt-badge {
    background: #eff6ff;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 13.5px;
    font-weight: 500;
    color: #1d4ed8;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
    border: 1px solid #dbeafe;
}
.kc-auth-form-group {
    margin-bottom: 20px;
}
.kc-auth-form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
    display: block;
}
.kc-auth-input {
    width: 100%;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 14px;
    outline: none;
    color: #0f172a;
    transition: all 0.2s ease;
    font-family: inherit;
}
.kc-auth-input::placeholder {
    color: #94a3b8;
}
.kc-auth-input:focus {
    background: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
}
.kc-auth-submit-btn {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 16px;
    font-size: 15px;
    font-weight: 700;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 8px;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
}
.kc-auth-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
}
.kc-auth-footer-text {
    text-align: center;
    font-size: 14px;
    color: #64748b;
    margin-top: 24px;
}
.kc-auth-footer-text a {
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
    margin-left: 4px;
}
.kc-auth-footer-text a:hover {
    text-decoration: underline;
}
</style>

<!-- Global Login Modal -->
<div id="kc_auth_login_modal" class="kc-auth-modal-overlay">
    <div class="kc-auth-modal-card">
        <div class="kc-auth-modal-header">
            <h3><i class="fa fa-lock"></i> Sign In</h3>
            <button type="button" class="kc-auth-close-btn" onclick="closeAuthLoginModal()"><i class="fa fa-times" style="font-size:16px;"></i></button>
        </div>
        <div class="kc-auth-modal-body">
            <div class="kc-auth-prompt-badge">
                <i class="fa fa-shield-check" style="font-size:16px;"></i>
                <span id="kc_auth_reason_text">Please sign in to add products or purchase hardware.</span>
            </div>

            <form id="kc_global_login_form" action="login.php" method="POST">
                <input type="hidden" name="redirect" id="kc_auth_redirect_target" value="">

                <div class="kc-auth-form-group">
                    <label>Email Address</label>
                    <input type="email" name="uemail" class="kc-auth-input" placeholder="name@example.com" required>
                </div>

                <div class="kc-auth-form-group">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label style="margin:0;">Password</label>
                        <a href="forgot.php" style="font-size:12px; color:#0070F3; text-decoration:none; font-weight:600;">Forgot?</a>
                    </div>
                    <input type="password" name="upwd" class="kc-auth-input" placeholder="Enter password" required>
                </div>

                <button type="submit" class="kc-auth-submit-btn" id="kc_auth_btn">
                    <i class="fa fa-arrow-right-to-bracket"></i> Sign In & Continue
                </button>
            </form>

            <div class="kc-auth-footer-text">
                Don't have an account? <a href="register.php" id="kc_auth_register_link">Register New Account</a>
            </div>
        </div>
    </div>
</div>

<script>
// Check if user is logged in
var IS_USER_LOGGED_IN = <?php echo (\App\Models\UserModel::isLoggedIn()) ? 'true' : 'false'; ?>;

function openAuthLoginModal(redirectUrl, reasonMsg) {
    var modal = document.getElementById('kc_auth_login_modal');
    var targetInput = document.getElementById('kc_auth_redirect_target');
    var reasonSpan = document.getElementById('kc_auth_reason_text');
    var regLink = document.getElementById('kc_auth_register_link');

    var currentPath = redirectUrl || (window.location.pathname + window.location.search);
    if (targetInput) targetInput.value = currentPath;
    if (regLink) regLink.href = 'register.php?redirect=' + encodeURIComponent(currentPath);
    if (reasonSpan && reasonMsg) reasonSpan.textContent = reasonMsg;

    if (modal) {
        modal.style.display = 'flex';
        // Small delay to allow display:flex to apply before adding the animation class
        setTimeout(function() {
            modal.classList.add('show');
        }, 10);
    } else {
        window.location.href = 'login.php?redirect=' + encodeURIComponent(currentPath);
    }
}

function closeAuthLoginModal() {
    var modal = document.getElementById('kc_auth_login_modal');
    if (modal) modal.style.display = 'none';
}
</script>

<!-- COMMON SCRIPTS -->
<script src="js/common_scripts.js"></script>
<script src="js/functions.js"></script>
<script src="assets/validate.js"></script>

<div id="toTop" style="display: none;" onclick="window.scrollTo({top: 0, behavior: 'smooth'});"></div>
<script>
(function() {
    var toTopBtn = document.getElementById('toTop');
    if (toTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                toTopBtn.style.display = 'block';
            } else {
                toTopBtn.style.display = 'none';
            }
        });
    }
    
    $(document).ready(function() {
        $('#close_cart').click(function() {
            $(".mfp-close").click();
        });
    });
})();
</script>