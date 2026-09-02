<!-- ══════════════════════════════════════════════════════════
     KARUDA COMPUTERS — ULTIMATE PREMIUM CYBER TECH FOOTER (STANDALONE)
══════════════════════════════════════════════════════════ -->
<style>
/* ── Global Footer Wrapper ── */
.kc-ultra-footer {
    background: #060F1E !important;
    color: #94A3B8 !important;
    position: relative !important;
    z-index: 10 !important;
    width: 100% !important;
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    border-top: 3px solid #00BCD4 !important;
    overflow: hidden;
}

/* Subtle Tech Glow Background */
.kc-ultra-footer::before {
    content: '';
    position: absolute;
    top: 0; left: 15%; width: 350px; height: 350px;
    background: radial-gradient(circle, rgba(0, 188, 212, 0.08) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}
.kc-ultra-footer::after {
    content: '';
    position: absolute;
    bottom: 0; right: 10%; width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(0, 112, 243, 0.08) 0%, transparent 70%);
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
    display: inline-flex;
    align-items: center;
    background: #02070F;
    border: 1.5px solid #1E293B;
    border-radius: 12px;
    padding: 8px 20px;
    margin-bottom: 20px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
    transition: all 0.3s ease;
}
.kc-footer-brand-logo:hover {
    border-color: #00BCD4;
    box-shadow: 0 0 20px rgba(0, 188, 212, 0.35);
    transform: translateY(-2px);
}
.kc-footer-brand-logo img {
    height: 46px !important;
    width: auto !important;
    object-fit: contain;
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
    color: #00E5FF;
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
    color: #00E5FF;
    text-decoration: none;
}

/* Column Headings */
.kc-fheading {
    font-size: 14px !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    letter-spacing: 1.2px !important;
    color: #00E5FF !important;
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
    background: #0070F3;
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
    background: #030812 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
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
                    <a href="index.php" class="d-inline-block">
                        <div class="kc-footer-brand-logo">
                            <img src="img/karuda_logo.png" alt="Karuda Computers" onerror="this.src='img/logo.png'">
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
</footer>

<script>
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
        alert(d.message || 'Thank you for subscribing to Karuda Computers Tech Club!');
        inp.value = '';
    })
    .catch(function() {
        btn.innerHTML = '<i class="fa fa-paper-plane mr-1"></i> Join';
        btn.disabled = false;
        alert('Thank you for subscribing to Karuda Computers Tech Club!');
        inp.value = '';
    });
}
</script>