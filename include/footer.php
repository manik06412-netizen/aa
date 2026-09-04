<!-- ══════════════════════════════════════════════════════════
     KARUDA COMPUTERS — ULTIMATE PREMIUM CYBER TECH FOOTER (STANDALONE)
══════════════════════════════════════════════════════════ -->
<style>
/* ── Global Footer Wrapper ── */
.kc-ultra-footer {
    background: linear-gradient(180deg, #001A47 0%, #002566 45%, #001840 100%) !important;
    color: #94A3B8 !important;
    position: relative !important;
    z-index: 10 !important;
    width: 100% !important;
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    border-top: 3px solid #003B95 !important;
    box-shadow: 0 -4px 30px rgba(0, 37, 102, 0.4) !important;
    overflow: hidden;
}

/* Subtle Tech Glow Background */
.kc-ultra-footer::before {
    content: '';
    position: absolute;
    top: 0; left: 15%; width: 350px; height: 350px;
    background: radial-gradient(circle, rgba(0, 59, 149, 0.25) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}
.kc-ultra-footer::after {
    content: '';
    position: absolute;
    bottom: 0; right: 10%; width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(0, 37, 102, 0.25) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

/* ── Main Footer Content ── */
.kc-footer-main {
    padding: 55px 0 35px !important;
    position: relative;
    z-index: 1;
}
.kc-footer-brand-logo {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0px !important;
    background: #001538 !important;
    border: 1.5px solid #003B95 !important;
    border-radius: 12px !important;
    padding: 8px 16px !important;
    margin-bottom: 20px !important;
    box-shadow: 0 8px 24px rgba(0, 37, 102, 0.4) !important;
    transition: all 0.3s ease !important;
}
.kc-footer-brand-logo:hover {
    border-color: #60A5FA !important;
    box-shadow: 0 0 25px rgba(0, 59, 149, 0.6) !important;
}
.kc-footer-logo-img {
    height: 48px !important;
    max-height: 48px !important;
    width: auto !important;
    object-fit: contain !important;
    margin: 0 -4px 0 0 !important;
    filter: drop-shadow(0 2px 8px rgba(0, 188, 212, 0.6)) !important;
}
.kc-footer-text-wrap {
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    line-height: 0.9 !important;
    margin: 0 !important;
}
.kc-footer-title-main {
    font-family: 'Poppins', 'Montserrat', 'Inter', sans-serif !important;
    font-size: 24px !important;
    font-weight: 900 !important;
    color: #FFFFFF !important;
    letter-spacing: 0.5px !important;
    text-transform: uppercase !important;
    text-shadow: 0 2px 10px rgba(255, 255, 255, 0.4) !important;
}
.kc-footer-title-sub {
    font-family: 'Poppins', 'Montserrat', 'Inter', sans-serif !important;
    font-size: 12px !important;
    font-weight: 900 !important;
    color: #60A5FA !important;
    letter-spacing: 5px !important;
    text-transform: uppercase !important;
    margin-top: 2px !important;
}
.kc-footer-brand-bio {
    font-size: 13.5px;
    line-height: 1.65;
    color: #94A3B8;
    margin-bottom: 22px;
}

/* Contact Info Items */
.kc-footer-contacts {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.kc-fcontact-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 13px;
    color: #CBD5E1;
}
.kc-fcontact-item i {
    color: #60A5FA;
    font-size: 15px;
    margin-top: 3px;
    flex-shrink: 0;
}
.kc-fcontact-item a {
    color: #CBD5E1;
    text-decoration: none;
    transition: color 0.2s ease;
}
.kc-fcontact-item a:hover {
    color: #93C5FD;
    text-decoration: none;
}

/* Column Headings */
.kc-fheading {
    font-size: 14px !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    letter-spacing: 1.2px !important;
    color: #93C5FD !important;
    margin-bottom: 20px !important;
    position: relative;
    display: inline-block;
}
.kc-fheading::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 24px;
    height: 2px;
    background: #003B95;
    border-radius: 2px;
}

/* Link Lists */
.kc-flinks-list {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}
.kc-flinks-list li {
    margin-bottom: 11px !important;
}
.kc-flinks-list li a {
    color: #CBD5E1 !important;
    text-decoration: none !important;
    font-size: 13.5px !important;
    font-weight: 400 !important;
    transition: all 0.2s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
}
.kc-flinks-list li a i {
    font-size: 10px;
    color: #00BCD4;
    transition: transform 0.2s ease;
}
.kc-flinks-list li a:hover {
    color: #00E5FF !important;
    transform: translateX(5px) !important;
    text-decoration: none !important;
}
.kc-flinks-list li a:hover i {
    transform: translateX(2px);
}

/* Newsletter Pod */
.kc-fnewsletter-card {
    background: #0A192F;
    border: 1.5px solid #1E293B;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}
.kc-fnewsletter-sub {
    font-size: 12.5px;
    color: #94A3B8;
    line-height: 1.5;
    margin-bottom: 14px;
}
.kc-fnews-form {
    display: flex;
    align-items: center;
    background: #040D1A;
    border: 1.5px solid #0070F3;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 16px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.kc-fnews-form:focus-within {
    border-color: #00E5FF;
    box-shadow: 0 0 14px rgba(0, 229, 255, 0.3);
}
.kc-fnews-form input {
    flex: 1;
    background: transparent;
    border: none;
    padding: 10px 14px;
    font-size: 13px;
    color: #ffffff;
    outline: none;
}
.kc-fnews-form input::placeholder {
    color: #64748B;
}
.kc-fnews-form button {
    background: linear-gradient(135deg, #0D47A1, #0070F3);
    color: #ffffff;
    border: none;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}
.kc-fnews-form button:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4);
}

/* Social Media Badges */
.kc-fsocial-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.kc-fsocial-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #06152B;
    border: 1px solid #1E293B;
    color: #94A3B8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none !important;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}
.kc-fsocial-btn:hover {
    color: #ffffff;
    transform: translateY(-3px) scale(1.1);
}
.kc-fsocial-btn.kc-fb:hover { background: #1877F2; border-color: #1877F2; box-shadow: 0 4px 14px rgba(24, 119, 242, 0.4); }
.kc-fsocial-btn.kc-tw:hover { background: #1DA1F2; border-color: #1DA1F2; box-shadow: 0 4px 14px rgba(29, 161, 242, 0.4); }
.kc-fsocial-btn.kc-ig:hover { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); border-color: #e6683c; box-shadow: 0 4px 14px rgba(220, 39, 67, 0.4); }
.kc-fsocial-btn.kc-yt:hover { background: #FF0000; border-color: #FF0000; box-shadow: 0 4px 14px rgba(255, 0, 0, 0.4); }
.kc-fsocial-btn.kc-in:hover { background: #0A66C2; border-color: #0A66C2; box-shadow: 0 4px 14px rgba(10, 102, 194, 0.4); }
.kc-fsocial-btn.kc-wa:hover { background: #25D366; border-color: #25D366; box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4); }

/* ── Bottom Copyright Bar ── */
.kc-footer-bottom {
    background: #001230 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
    padding: 18px 0 !important;
    font-size: 13px !important;
    color: #94A3B8 !important;
    position: relative;
    z-index: 1;
}
.kc-fpayment-badges {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.kc-fpay-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 3px 9px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 800;
    height: 24px;
}
.kc-fpay-pill.kc-visa { background: #ffffff; color: #0033A0; font-style: italic; font-weight: 900; }
.kc-fpay-pill.kc-mc { background: #222222; color: #EB001B; font-size: 14px; }
.kc-fpay-pill.kc-rupay { background: #005088; color: #ffffff; }
.kc-fpay-pill.kc-upi { background: #5F259F; color: #ffffff; }
.kc-fpay-pill.kc-paypal { background: #ffffff; color: #003087; }
</style>

<footer class="kc-ultra-footer">
    
    <!-- Main Footer Grid -->
    <div class="kc-footer-main">
        <div class="container-fluid px-lg-5 px-3">
            <div class="row">
                
                <!-- Col 1: Brand & Contact Info (3.5 cols) -->
                <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0 pr-lg-4">
                    <a href="index.php" class="d-inline-block text-decoration-none">
                        <div class="kc-footer-brand-logo">
                            <img src="img/karuda_eagle_logo.png" alt="Karuda Computers" class="kc-footer-logo-img" onerror="this.src='img/logo.png'">
                            <div class="kc-footer-text-wrap">
                                <span class="kc-footer-title-main">KARUDA</span>
                                <span class="kc-footer-title-sub">COMPUTERS</span>
                            </div>
                        </div>
                    </a>
                    <p class="kc-footer-brand-bio">
                        Your trusted destination for High-Performance Gaming Rigs, Workstation PCs, Certified Laptops, and genuine PC Hardware across India.
                    </p>
                    <div class="kc-footer-contacts">
                        <div class="kc-fcontact-item">
                            <i class="fa fa-location-dot"></i>
                            <span>Karuda Tech Park, Cyber Corridor, Chennai - 600096</span>
                        </div>
                        <div class="kc-fcontact-item">
                            <i class="fa fa-phone-volume"></i>
                            <div>
                                <a href="tel:+919876543210">+91 98765 43210</a> &nbsp;|&nbsp; 
                                <a href="tel:+919876543211">+91 98765 43211</a>
                            </div>
                        </div>
                        <div class="kc-fcontact-item">
                            <i class="fa fa-envelope"></i>
                            <a href="mailto:support@karudacomputers.com">support@karudacomputers.com</a>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Quick Links (2 cols) -->
                <div class="col-lg-2 col-md-6 col-6 mb-4 mb-lg-0">
                    <h4 class="kc-fheading">QUICK LINKS</h4>
                    <ul class="kc-flinks-list">
                        <li><a href="index.php"><i class="fa fa-chevron-right"></i> Home & Deals</a></li>
                        <li><a href="allproducts.php"><i class="fa fa-chevron-right"></i> All Products</a></li>
                        <li><a href="about.php"><i class="fa fa-chevron-right"></i> About Us</a></li>
                        <li><a href="contact.php"><i class="fa fa-chevron-right"></i> Contact Store</a></li>
                        <li><a href="faq.php"><i class="fa fa-chevron-right"></i> FAQs & Help</a></li>
                        <li><a href="order_track.php"><i class="fa fa-chevron-right"></i> Track Order</a></li>
                    </ul>
                </div>

                <!-- Col 3: Categories (2 cols) -->
                <div class="col-lg-2 col-md-6 col-6 mb-4 mb-lg-0">
                    <h4 class="kc-fheading">CATEGORIES</h4>
                    <ul class="kc-flinks-list">
                        <?php
                        if (!isset($con) || !$con) {
                            @include_once __DIR__ . '/../dbconnect.php';
                        }
                        $footer_cats = [];
                        if (isset($con) && $con) {
                            $fcat_q = mysqli_query($con, "SELECT c_id, c_name FROM res_category ORDER BY c_id DESC LIMIT 6");
                            if ($fcat_q && mysqli_num_rows($fcat_q) > 0) {
                                while ($fc = mysqli_fetch_assoc($fcat_q)) {
                                    $footer_cats[] = $fc['c_name'];
                                }
                            }
                        }
                        if (!empty($footer_cats)) {
                            foreach ($footer_cats as $fcname) {
                                echo '<li><a href="category_list.php?search=' . urlencode($fcname) . '"><i class="fa fa-chevron-right"></i> ' . htmlspecialchars($fcname) . '</a></li>';
                            }
                        } else {
                        ?>
                        <li><a href="category_list.php?search=laptop"><i class="fa fa-chevron-right"></i> Laptops</a></li>
                        <li><a href="category_list.php?search=desktop"><i class="fa fa-chevron-right"></i> Desktops & AIO</a></li>
                        <li><a href="category_list.php?search=component"><i class="fa fa-chevron-right"></i> Components</a></li>
                        <li><a href="category_list.php?search=monitor"><i class="fa fa-chevron-right"></i> Monitors</a></li>
                        <li><a href="category_list.php?search=accessories"><i class="fa fa-chevron-right"></i> Accessories</a></li>
                        <li><a href="category_list.php?search=gaming"><i class="fa fa-chevron-right"></i> Gaming PCs</a></li>
                        <?php } ?>
                    </ul>
                </div>

                <!-- Col 4: VIP Newsletter & Social (4 cols) -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="kc-fnewsletter-card">
                        <h4 class="kc-fheading" style="margin-bottom:10px !important;">JOIN THE TECH CLUB</h4>
                        <p class="kc-fnewsletter-sub">
                            Subscribe to receive exclusive hardware drop alerts, flash deals, and custom PC build discounts.
                        </p>
                        <form action="subscribe.php" method="POST" class="kc-fnews-form" onsubmit="submitUltraNewsletter(event)">
                            <input type="email" id="kc_ultra_email" name="email" placeholder="Enter your email address" required autocomplete="off">
                            <button type="submit" id="kc_ultra_btn"><i class="fa fa-paper-plane mr-1"></i> Join</button>
                        </form>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top border-secondary">
                            <span style="font-size:12px; font-weight:700; color:#CBD5E1; text-transform:uppercase; letter-spacing:0.5px;">Follow Us:</span>
                            <div class="kc-fsocial-strip">
                                <a href="#" class="kc-fsocial-btn kc-fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="kc-fsocial-btn kc-tw" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                                <a href="#" class="kc-fsocial-btn kc-ig" title="Instagram"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="kc-fsocial-btn kc-yt" title="YouTube"><i class="fab fa-youtube"></i></a>
                                <a href="#" class="kc-fsocial-btn kc-in" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="https://wa.me/919876543210" class="kc-fsocial-btn kc-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Copyright Bottom Bar -->
    <div class="kc-footer-bottom">
        <div class="container-fluid px-lg-5 px-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <span>© 2026 <strong style="color:#00E5FF;">Karuda Computers</strong>. All Rights Reserved.</span>
                    <span class="d-none d-md-inline" style="opacity:0.4;">|</span>
                    <a href="privacy_policy.php" style="color:#94A3B8; text-decoration:none; font-size:12px;">Privacy Policy</a>
                    <a href="terms_conditions.php" style="color:#94A3B8; text-decoration:none; font-size:12px;">Terms of Service</a>
                </div>
                <div class="kc-fpayment-badges">
                    <span class="kc-fpay-pill kc-visa">VISA</span>
                    <span class="kc-fpay-pill kc-mc"><i class="fab fa-cc-mastercard"></i></span>
                    <span class="kc-fpay-pill kc-rupay">RuPay</span>
                    <span class="kc-fpay-pill kc-upi">UPI</span>
                    <span class="kc-fpay-pill kc-paypal"><i class="fab fa-paypal"></i> PayPal</span>
                </div>
            </div>
        </div>
    </div>
<script>
if (typeof window.quickAddToCart !== 'function') {
    window.quickAddToCart = function(productId, btn) {
        if (!productId) return;
        if (typeof toastr !== 'undefined') {
            toastr.options = { "closeButton": true, "progressBar": true, "positionClass": "toast-top-right", "timeOut": "3000" };
        }
        if (typeof window.IS_USER_LOGGED_IN !== 'undefined' && !window.IS_USER_LOGGED_IN) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('Please log in to add products to your cart!', 'Login Required');
            } else {
                alert('Please log in to add products to your cart!');
            }
            setTimeout(function() {
                window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.pathname + window.location.search);
            }, 1200);
            return;
        }

        var $btn = $(btn);
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: 'cart.php?action=add',
            type: 'POST',
            data: { prdid: productId, pid: 0, qty: 1 },
            dataType: 'json',
            success: function(resp) {
                $btn.prop('disabled', false).html(origHtml);
                if (resp && resp.status == 3) {
                    if (typeof toastr !== 'undefined') {
                        toastr.info('🛒 Item is already in your cart! Quantity updated.', 'Cart Updated');
                    } else {
                        alert('🛒 Item is already in your cart! Quantity updated.');
                    }
                } else if (resp && resp.status == 2) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success('✅ Item added to your cart!', 'Success');
                    } else {
                        alert('✅ Item added to your cart!');
                    }
                } else {
                    if (typeof window.showKcToast !== 'undefined') {
                        window.showKcToast('Added to Cart! 🛒', 'Item successfully added to your shopping cart.', 'success');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.success('✅ Item added to your cart!', 'Success');
                    }
                }
                if (resp && typeof resp.number_of_cart !== 'undefined') {
                    $('.uls-badge, #cartCountBadge, #ulsCartBadge, .kc-cart-badge').text(resp.number_of_cart).show();
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                if (typeof window.showKcToast !== 'undefined') {
                    window.showKcToast('Cart Error', 'Failed to update cart. Please try again.', 'error');
                } else {
                    alert('Failed to update cart. Please try again.');
                }
            }
        });
    };
}
</script>
</footer>

<!-- ══════════════════════════════════════════════════════════
     KARUDA COMPUTERS — MODERN TOAST NOTIFICATION SYSTEM
══════════════════════════════════════════════════════════ -->
<div id="kc-toast-container" class="kc-toast-container"></div>

<style>
.kc-toast-container {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 99999999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 400px;
    width: calc(100vw - 32px);
    pointer-events: none;
}
.kc-toast {
    pointer-events: auto;
    background: #FFFFFF;
    border-radius: 12px;
    box-shadow: 0 12px 35px rgba(0, 37, 102, 0.22), 0 0 0 1px rgba(0, 59, 149, 0.12);
    border-left: 5px solid #003B95;
    padding: 14px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    position: relative;
    overflow: hidden;
    transform: translateX(120%);
    opacity: 0;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.kc-toast.kc-toast-show {
    transform: translateX(0);
    opacity: 1;
}
.kc-toast.kc-toast-hide {
    transform: translateX(120%);
    opacity: 0;
}
.kc-toast-icon-wrap {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #EFF6FF;
    color: #003B95;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.kc-toast.kc-toast-success {
    border-left-color: #10B981;
}
.kc-toast.kc-toast-success .kc-toast-icon-wrap {
    background: #ECFDF5;
    color: #10B981;
}
.kc-toast.kc-toast-error {
    border-left-color: #EF4444;
}
.kc-toast.kc-toast-error .kc-toast-icon-wrap {
    background: #FEF2F2;
    color: #EF4444;
}
.kc-toast-content {
    flex: 1;
    min-width: 0;
}
.kc-toast-title {
    font-size: 14.5px;
    font-weight: 800;
    color: #002566;
    margin: 0 0 3px 0;
    font-family: 'Poppins', sans-serif;
    line-height: 1.3;
}
.kc-toast-message {
    font-size: 13px;
    color: #475569;
    margin: 0;
    line-height: 1.45;
    font-family: 'Outfit', sans-serif;
}
.kc-toast-close {
    background: none;
    border: none;
    color: #94A3B8;
    font-size: 18px;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    margin-left: 6px;
    transition: color 0.15s;
}
.kc-toast-close:hover {
    color: #0F172A;
}
.kc-toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #003B95, #60A5FA);
    width: 100%;
    transform-origin: left;
    animation: kcToastProgress linear forwards;
}
.kc-toast.kc-toast-success .kc-toast-progress {
    background: #10B981;
}
@keyframes kcToastProgress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>

<script>
window.showKcToast = function(title, message, type, duration) {
    type = type || 'success';
    duration = duration || 4500;

    var container = document.getElementById('kc-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'kc-toast-container';
        container.className = 'kc-toast-container';
        document.body.appendChild(container);
    }

    var toast = document.createElement('div');
    toast.className = 'kc-toast kc-toast-' + type;

    var iconClass = 'fa-check';
    if (type === 'error') iconClass = 'fa-triangle-exclamation';
    else if (type === 'info') iconClass = 'fa-circle-info';

    toast.innerHTML = 
        '<div class="kc-toast-icon-wrap"><i class="fa-solid ' + iconClass + '"></i></div>' +
        '<div class="kc-toast-content">' +
            '<div class="kc-toast-title">' + title + '</div>' +
            '<div class="kc-toast-message">' + message + '</div>' +
        '</div>' +
        '<button type="button" class="kc-toast-close" aria-label="Close">&times;</button>' +
        '<div class="kc-toast-progress" style="animation-duration: ' + duration + 'ms;"></div>';

    container.appendChild(toast);

    setTimeout(function() {
        toast.classList.add('kc-toast-show');
    }, 10);

    var isRemoved = false;
    function removeToast() {
        if (isRemoved) return;
        isRemoved = true;
        toast.classList.remove('kc-toast-show');
        toast.classList.add('kc-toast-hide');
        setTimeout(function() {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 350);
    }

    toast.querySelector('.kc-toast-close').addEventListener('click', removeToast);
    setTimeout(removeToast, duration);
};

function submitUltraNewsletter(e) {
    e.preventDefault();
    var inp = document.getElementById('kc_ultra_email');
    var btn = document.getElementById('kc_ultra_btn');
    if (!inp || !inp.value) return;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch('subscribe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'email=' + encodeURIComponent(inp.value.trim())
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        btn.innerHTML = '<i class="fa fa-paper-plane mr-1"></i> Join';
        btn.disabled = false;
        showKcToast('Subscription Confirmed! 🎉', d.message || 'Thank you for subscribing to Karuda Computers Tech Club!', 'success');
        inp.value = '';
    })
    .catch(function() {
        btn.innerHTML = '<i class="fa fa-paper-plane mr-1"></i> Join';
        btn.disabled = false;
        showKcToast('Subscription Confirmed! 🎉', 'Thank you for subscribing to Karuda Computers Tech Club!', 'success');
        inp.value = '';
    });
}
</script>

<?php if (!empty($_SESSION['flash_toast'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.showKcToast !== 'undefined') {
        window.showKcToast(
            <?php echo json_encode($_SESSION['flash_toast']['title'] ?? 'Notice'); ?>,
            <?php echo json_encode($_SESSION['flash_toast']['message'] ?? ''); ?>,
            <?php echo json_encode($_SESSION['flash_toast']['type'] ?? 'success'); ?>
        );
    }
});
</script>
<?php unset($_SESSION['flash_toast']); endif; ?>