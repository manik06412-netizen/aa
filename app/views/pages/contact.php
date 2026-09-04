<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
require_once APP_ROOT . '/views/layouts/header.php';
?>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <div class="sub_header_in">
            <div class="container text-center">
                <h1 class="text-white mb-2">Get in Touch with Us</h1>
                <p class="text-white-50 mb-0" style="font-size:15px;">Have questions about custom builds, laptops, or enterprise IT hardware? We are here to help!</p>
            </div>
        </div>

        <main style="padding: 40px 0 70px;">
            <div class="container">
                <div class="row">
                    
                    <!-- Left: Contact Form Card -->
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <div style="background:#ffffff;border-radius:16px;border:1.5px solid #E2E8F0;padding:35px 30px;box-shadow:0 6px 24px rgba(11,25,44,0.06);">
                            <div class="d-flex align-items-center mb-4">
                                <div style="width:44px;height:44px;border-radius:12px;background:#EFF6FF;color:#0D47A1;display:flex;align-items:center;justify-content:center;font-size:20px;margin-right:15px;">
                                    <i class="fa fa-paper-plane"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:20px;font-weight:800;color:#0B192C;margin:0;">Send us a Direct Message</h3>
                                    <p class="text-muted mb-0" style="font-size:13px;">Our technical support engineers respond within 2-4 business hours.</p>
                                </div>
                            </div>

                            <form id="kcContactForm" action="contact1.php" method="POST" onsubmit="submitKcContactForm(event, this)">
                                <?php echo \App\Core\Csrf::field(); ?>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label style="font-weight:700;font-size:13px;color:#1E293B;">Your Full Name *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fa fa-user text-muted"></i></span>
                                            </div>
                                            <input class="form-control border-left-0" type="text" name="name" placeholder="John Doe" required style="height:46px;font-size:14px;">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label style="font-weight:700;font-size:13px;color:#1E293B;">Email Address *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fa fa-envelope text-muted"></i></span>
                                            </div>
                                            <input class="form-control border-left-0" type="email" name="email" placeholder="john@example.com" required style="height:46px;font-size:14px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label style="font-weight:700;font-size:13px;color:#1E293B;">Mobile / WhatsApp Number *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fa fa-phone text-muted"></i></span>
                                            </div>
                                            <input class="form-control border-left-0" type="tel" name="mobile" placeholder="9876543210" minlength="10" maxlength="10" pattern="^[6789][0-9]{9}$" required style="height:46px;font-size:14px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label style="font-weight:700;font-size:13px;color:#1E293B;">How can we help you? *</label>
                                    <textarea class="form-control" name="comment" rows="5" placeholder="Specify your requirements (e.g. custom PC specs, quotation request, order inquiry)..." required style="font-size:14px;border-radius:10px;"></textarea>
                                </div>

                                <button type="submit" name="subt" class="btn btn-primary btn-block py-3" style="background:linear-gradient(135deg, #003B95, #002566);border:none;border-radius:10px;font-weight:700;font-size:14px;text-transform:uppercase;letter-spacing:0.5px;box-shadow:0 4px 14px rgba(0,37,102,0.35);transition:all 0.2s ease;">
                                    <i class="fa fa-paper-plane mr-2"></i> Submit Inquiry
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right: Info Cards & Store Hours -->
                    <div class="col-lg-5">
                        
                        <!-- Card 1: Phone Support -->
                        <div class="box_contacts mb-3" style="border-left: 4px solid #003B95 !important;">
                            <div class="d-flex align-items-center">
                                <div style="width:48px;height:48px;border-radius:12px;background:#EFF6FF;color:#003B95;display:flex;align-items:center;justify-content:center;font-size:22px;margin-right:16px;">
                                    <i class="fa fa-headset"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:15px;font-weight:800;color:#0B192C;margin:0 0 4px;">Phone & WhatsApp</h3>
                                    <a href="tel:<?php echo $CON_CONTACT_PHONE ?: '+919876543210'; ?>" style="color:#003B95;font-size:15px;font-weight:700;text-decoration:none;">
                                        <?php echo $CON_CONTACT_PHONE ?: '+91 98765 43210'; ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Email -->
                        <div class="box_contacts mb-3" style="border-left: 4px solid #00BCD4 !important;">
                            <div class="d-flex align-items-center">
                                <div style="width:48px;height:48px;border-radius:12px;background:#ECFEFF;color:#0891B2;display:flex;align-items:center;justify-content:center;font-size:22px;margin-right:16px;">
                                    <i class="fa fa-envelope-open-text"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:15px;font-weight:800;color:#0B192C;margin:0 0 4px;">Email Support</h3>
                                    <a href="mailto:<?php echo $CON_CONTACT_EMAIL ?: 'support@karudacomputers.com'; ?>" style="color:#0891B2;font-size:14px;font-weight:700;text-decoration:none;">
                                        <?php echo $CON_CONTACT_EMAIL ?: 'support@karudacomputers.com'; ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Location -->
                        <div class="box_contacts mb-3" style="border-left: 4px solid #10B981 !important;">
                            <div class="d-flex align-items-start">
                                <div style="width:48px;height:48px;border-radius:12px;background:#F0FDF4;color:#16A34A;display:flex;align-items:center;justify-content:center;font-size:22px;margin-right:16px;flex-shrink:0;">
                                    <i class="fa fa-map-marked-alt"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:15px;font-weight:800;color:#0B192C;margin:0 0 4px;">Flagship Store & Tech Center</h3>
                                    <p style="margin:0;color:#475569;font-size:13px;line-height:1.5;">
                                        <?php echo $CON_CONTACT_ADDRESS ?: 'Karuda Computers, Tech Park Avenue, IT Corridor, Chennai - 600096'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Operating Hours -->
                        <div style="background:#0B192C;color:#ffffff;border-radius:14px;padding:22px;box-shadow:0 6px 20px rgba(11,25,44,0.15);">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-clock text-info mr-2" style="font-size:18px;"></i>
                                <h4 class="text-white mb-0" style="font-size:14px;font-weight:800;text-transform:uppercase;letter-spacing:0.5px;">Store Business Hours</h4>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-secondary" style="font-size:13px;">
                                <span class="text-white-50">Monday - Saturday:</span>
                                <span class="text-white font-weight-bold">09:30 AM - 09:00 PM</span>
                            </div>
                            <div class="d-flex justify-content-between" style="font-size:13px;">
                                <span class="text-white-50">Sunday:</span>
                                <span class="text-info font-weight-bold">10:00 AM - 06:00 PM</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>

        <footer id="footer">
            <?php require APP_ROOT . '/views/layouts/footer.php'; ?>
        </footer>
    </div>

    <?php require APP_ROOT . '/views/layouts/sign_footer.php'; ?>

    <script>
    function submitKcContactForm(e, form) {
        e.preventDefault();
        var btn = form.querySelector('button[type="submit"]');
        var originalBtnText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Sending...';

        var formData = new FormData(form);

        fetch('contact1.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data && data.status === 'success') {
                if (typeof window.showKcToast === 'function') {
                    window.showKcToast('Inquiry Submitted! 🎉', data.message, 'success', 6000);
                } else {
                    alert(data.message);
                }
                form.reset();
            } else {
                var msg = (data && data.message) ? data.message : 'Please check your inputs and try again.';
                if (typeof window.showKcToast === 'function') {
                    window.showKcToast('Notice', msg, 'error');
                } else {
                    alert(msg);
                }
            }
        })
        .catch(function(err) {
            console.error('Contact form error:', err);
            if (typeof window.showKcToast === 'function') {
                window.showKcToast('Error', 'Failed to submit form. Please check your connection.', 'error');
            } else {
                alert('Failed to submit form. Please check your connection.');
            }
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = originalBtnText;
        });
    }
    </script>
</body>
</html>