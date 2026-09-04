<?php
/**
 * include/promo_banner.php
 * Karuda Computers — Wireframe Section 6: Promo Banner (Perfect Alignment & Responsive)
 */
?>
<style>
.kc-wireframe-promo-section {
    padding: 10px 0;
    margin-top: 25px !important;
    margin-bottom: 25px !important;
    margin-left: auto !important;
    margin-right: auto !important;
}
.kc-wireframe-promo-box {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border: 1.5px solid #E2E8F0;
    border-radius: 20px;
    padding: 38px 45px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
}
.kc-promo-sub-badge {
    display: inline-block;
    background: #003B95;
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 800;
    padding: 5px 14px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 14px;
}
.kc-promo-title {
    font-size: 30px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.25;
    margin-bottom: 14px;
    letter-spacing: -0.3px;
}
.kc-promo-desc {
    color: #64748B;
    font-size: 14.5px;
    margin-bottom: 24px;
    line-height: 1.6;
}
.kc-promo-btn {
    background: linear-gradient(135deg, #003B95 0%, #002566 100%);
    color: #ffffff !important;
    font-size: 13.5px;
    font-weight: 800;
    padding: 13px 30px;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(0, 37, 102, 0.35);
}
.kc-promo-btn:hover {
    background: #002566;
    box-shadow: 0 6px 20px rgba(0, 37, 102, 0.5);
    transform: translateY(-2px);
}
.kc-promo-img-container {
    width: 100%;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1.5px solid #E2E8F0;
    background: #ffffff;
    max-height: 260px;
}
.kc-promo-img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.kc-promo-img-container:hover .kc-promo-img {
    transform: scale(1.03);
}

@media (max-width: 991px) {
    .kc-wireframe-promo-box {
        padding: 30px 24px;
    }
    .kc-promo-title {
        font-size: 24px;
    }
    .kc-promo-img-container {
        max-height: 220px;
    }
    .kc-promo-img {
        height: 220px;
    }
}
@media (max-width: 767px) {
    .kc-wireframe-promo-box {
        text-align: center;
        padding: 24px 18px;
    }
    .kc-promo-title {
        font-size: 21px;
    }
    .kc-promo-desc {
        font-size: 13px;
    }
    .kc-promo-img-container {
        margin-top: 15px;
        max-height: 180px;
    }
    .kc-promo-img {
        height: 180px;
    }
}
</style>

<section class="kc-wireframe-promo-section container">
    <div class="kc-wireframe-promo-box">
        <div class="row align-items-center">
            <!-- Left Content Column -->
            <div class="col-lg-7 col-md-6 mb-4 mb-md-0 text-left">
                <span class="kc-promo-sub-badge"><i class="fa fa-fire mr-1"></i> SPECIAL OFFER</span>
                <h2 class="kc-promo-title">
                    Up to 40% OFF On High Performance Laptops & Accessories
                </h2>
                <p class="kc-promo-desc">
                    Upgrade your gaming and work setup with top-tier hardware, ultra-fast processors, and premium tech accessories at unbeatable prices.
                </p>
                <a href="allproducts.php" class="kc-promo-btn">
                    SHOP NOW <i class="fa fa-arrow-right"></i>
                </a>
            </div>

            <!-- Right Image Column -->
            <div class="col-lg-5 col-md-6 text-center">
                <div class="kc-promo-img-container">
                    <img src="img/karuda_hero_laptop.jpg" alt="High Performance Laptops" class="kc-promo-img" onerror="this.src='img/products/hp_laptop.jpg'">
                </div>
            </div>
        </div>
    </div>
</section>
