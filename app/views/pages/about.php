<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
error_reporting(0);
require('include/header.php');
?>
<style>
.sub_header_in {
    background: linear-gradient(135deg, #001A47 0%, #002566 50%, #003B95 100%) !important;
    padding: 36px 0 32px 0;
    color: #FFFFFF;
    border-bottom: 3px solid #003B95;
}
.sub_header_in h1 {
    font-size: 28px;
    font-weight: 900;
    color: #FFFFFF;
    margin-bottom: 6px;
    font-family: 'Poppins', 'Outfit', sans-serif;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.sub_header_in p {
    font-size: 14px;
    color: #DBEAFE;
    max-width: 650px;
    margin: 0 auto;
}

.margin_60_35 {
    padding: 35px 0 50px 0;
}

.kc-about-feat {
    transition: all 0.25s ease;
}
.kc-about-feat:hover {
    border-color: #0070F3 !important;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 112, 243, 0.1);
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
                <?php 
                $content = isset($ABOUT_CONTENT) ? $ABOUT_CONTENT : '';
                if (!empty($content)) {
                    echo $content;
                } else {
                ?>
                <div class="row align-items-center my-3">
                    <!-- Left Column: Story & Features -->
                    <div class="col-lg-6 col-12 mb-4 mb-lg-0">
                        <div class="badge badge-primary px-3 py-2 text-uppercase mb-3" style="background:linear-gradient(135deg, #0070F3, #00BCD4); border-radius:30px; font-weight:700; letter-spacing:0.8px; font-size:12px;">
                            About Karuda Computers
                        </div>
                        <h2 style="font-size:28px; font-weight:900; color:#0B192C; line-height:1.3; font-family:'Outfit', sans-serif;">
                            Empowering Gamers, Creators & Tech Enthusiasts
                        </h2>
                        <p style="font-size:14.5px; color:#475569; line-height:1.7; margin-top:14px;">
                            Founded with a passion for high-performance computing, <strong>Karuda Computers</strong> is India's premier destination for custom gaming rigs, liquid-cooled workstation PCs, gaming laptops, and certified hardware components.
                        </p>
                        <p style="font-size:14.5px; color:#475569; line-height:1.7;">
                            We partner directly with industry giants—Intel, AMD, NVIDIA, ASUS ROG, Corsair, and Lenovo—to ensure every custom rig comes with 100% genuine components, GST invoices, and official brand warranty.
                        </p>

                        <!-- Feature Grid -->
                        <div class="row g-3 mt-2">
                            <div class="col-6 mb-3">
                                <div style="background:#F8FAFC; border-radius:14px; padding:14px; border:1.5px solid #E2E8F0;" class="kc-about-feat">
                                    <div style="width:36px; height:36px; border-radius:10px; background:#EFF6FF; color:#0070F3; display:flex; align-items:center; justify-content:center; font-size:16px; margin-bottom:8px;">
                                        <i class="fa fa-shield-check"></i>
                                    </div>
                                    <h5 style="color:#0F172A; font-size:14px; font-weight:800; margin:0;">100% Genuine</h5>
                                    <p style="color:#64748B; font-size:11.5px; margin:4px 0 0 0; font-weight:600;">Official GST Invoice & Brand Warranty</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div style="background:#F8FAFC; border-radius:14px; padding:14px; border:1.5px solid #E2E8F0;" class="kc-about-feat">
                                    <div style="width:36px; height:36px; border-radius:10px; background:#F0FDF4; color:#16A34A; display:flex; align-items:center; justify-content:center; font-size:16px; margin-bottom:8px;">
                                        <i class="fa fa-microchip"></i>
                                    </div>
                                    <h5 style="color:#0F172A; font-size:14px; font-weight:800; margin:0;">Custom Water Cooling</h5>
                                    <p style="color:#64748B; font-size:11.5px; margin:4px 0 0 0; font-weight:600;">Hand-built RGB & Hard-Tube PC Rigs</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div style="background:#F8FAFC; border-radius:14px; padding:14px; border:1.5px solid #E2E8F0;" class="kc-about-feat">
                                    <div style="width:36px; height:36px; border-radius:10px; background:#FEF3C7; color:#D97706; display:flex; align-items:center; justify-content:center; font-size:16px; margin-bottom:8px;">
                                        <i class="fa fa-truck-fast"></i>
                                    </div>
                                    <h5 style="color:#0F172A; font-size:14px; font-weight:800; margin:0;">Express Shipping</h5>
                                    <p style="color:#64748B; font-size:11.5px; margin:4px 0 0 0; font-weight:600;">Insured Freight Delivery All Over India</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div style="background:#F8FAFC; border-radius:14px; padding:14px; border:1.5px solid #E2E8F0;" class="kc-about-feat">
                                    <div style="width:36px; height:36px; border-radius:10px; background:#F3E8FF; color:#9333EA; display:flex; align-items:center; justify-content:center; font-size:16px; margin-bottom:8px;">
                                        <i class="fa fa-headset"></i>
                                    </div>
                                    <h5 style="color:#0F172A; font-size:14px; font-weight:800; margin:0;">24/7 Tech Support</h5>
                                    <p style="color:#64748B; font-size:11.5px; margin:4px 0 0 0; font-weight:600;">Dedicated PC Engineers on Call</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Perfectly Bounded Showroom Images (NO OVERLAP) -->
                    <div class="col-lg-6 col-12">
                        <div style="background:#FFFFFF; border-radius:18px; border:1.5px solid #E2E8F0; padding:16px; box-shadow:0 8px 24px rgba(0,0,0,0.06);">
                            
                            <!-- Main PC Rig Card -->
                            <div style="border-radius:12px; overflow:hidden; background:#F8FAFC; border:1px solid #E2E8F0; display:flex; align-items:center; justify-content:center; height:320px;">
                                <img src="img/products/prod_desktop_1.jpg" alt="Karuda Hyperion Gaming Desktop" style="max-height:300px; max-width:100%; object-fit:contain;" onerror="this.src='img/categories/cat_desktop.jpg'">
                            </div>

                            <!-- Stats Bar -->
                            <div class="d-flex align-items-center justify-content-between my-3 px-3 py-2" style="background:#EFF6FF; border-radius:10px; border:1px solid #DBEAFE;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-award text-primary mr-2" style="font-size:20px;"></i>
                                    <div>
                                        <h6 style="margin:0; font-weight:800; color:#0B192C; font-size:14px;">1,000+ Custom PCs Delivered</h6>
                                        <span style="font-size:11px; color:#64748B;">Pan-India Certified Delivery</span>
                                    </div>
                                </div>
                                <span class="badge badge-success px-2 py-1" style="font-size:11px;">Verified</span>
                            </div>

                            <!-- Secondary Gallery Grid -->
                            <div class="row g-2">
                                <div class="col-6">
                                    <div style="border-radius:10px; overflow:hidden; background:#F8FAFC; border:1px solid #E2E8F0; height:120px; display:flex; align-items:center; justify-content:center;">
                                        <img src="img/products/prod_desktop_2.jpg" alt="Workstation PC" style="max-height:110px; max-width:100%; object-fit:contain;" onerror="this.src='img/categories/cat_laptop.jpg'">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div style="border-radius:10px; overflow:hidden; background:#F8FAFC; border:1px solid #E2E8F0; height:120px; display:flex; align-items:center; justify-content:center;">
                                        <img src="img/categories/cat_component.jpg" alt="RTX GPU Component" style="max-height:110px; max-width:100%; object-fit:contain;" onerror="this.src='img/categories/cat_component.jpg'">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </main>

        <footer id="footer">
            <?php include('include/footer.php'); ?>
        </footer>
    </div>

    <?php include('include/sign_footer.php'); ?>
</body>
</html>