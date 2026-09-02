<?php
/**
 * include/slidderfull.php
 * Karuda Computers — Compact Hero Tech Banner Section (Reduced Height)
 */
if (!isset($con) || !$con) {
    if (class_exists('\\App\\Core\\Database')) {
        $con = \App\Core\Database::getInstance()->getConnection();
    } elseif (file_exists(__DIR__ . '/../dbconnect.php')) {
        require_once __DIR__ . '/../dbconnect.php';
    }
}

$hero_banners = [];
if (isset($con) && $con) {
    $b_res = mysqli_query($con, "SELECT * FROM banner ORDER BY id DESC");
    if ($b_res && mysqli_num_rows($b_res) > 0) {
        while ($b = mysqli_fetch_assoc($b_res)) {
            $hero_banners[] = $b;
        }
    }
}
?>

<style>
/* ══════════════════════════════════════════════════════════
   COMPACT HERO TECH SECTION (REDUCED HEIGHT)
══════════════════════════════════════════════════════════ */
.kc-hero-tech-section {
    position: relative;
    background: radial-gradient(circle at 50% 30%, #1a365d 0%, #0b192c 60%, #060d17 100%);
    padding: 42px 0 38px 0 !important;
    overflow: hidden;
    color: #ffffff;
    border-bottom: 1px solid rgba(0, 188, 212, 0.2);
}

/* Background Cyber Grid Accent */
.kc-hero-tech-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: 
        linear-gradient(rgba(0, 188, 212, 0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 188, 212, 0.04) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

.kc-hero-glow-blob {
    position: absolute;
    width: 450px;
    height: 450px;
    top: -90px;
    left: 50%;
    transform: translateX(-50%);
    background: radial-gradient(circle, rgba(0, 188, 212, 0.18) 0%, rgba(0, 112, 243, 0.08) 50%, transparent 70%);
    filter: blur(60px);
    pointer-events: none;
    z-index: 1;
}

.kc-hero-badge-sub {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #00BCD4;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.kc-hero-badge-sub::before {
    content: '';
    display: inline-block;
    width: 20px;
    height: 2px;
    background: #00BCD4;
    border-radius: 2px;
}

.kc-hero-headline {
    font-size: 38px;
    font-weight: 900;
    line-height: 1.15;
    letter-spacing: -0.4px;
    margin-bottom: 0;
    text-transform: uppercase;
}
.kc-hero-headline .kc-text-white {
    color: #FFFFFF;
    display: block;
}
.kc-hero-headline .kc-text-cyan {
    color: #00BCD4;
    display: block;
}

.kc-hero-description {
    color: #94A3B8;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.6;
    max-width: 500px;
    margin-top: 14px;
    margin-bottom: 24px;
}

.kc-hero-btn-group {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.kc-btn-hero-blue {
    background: linear-gradient(135deg, #0070F3 0%, #0056B3 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 9px;
    padding: 11px 25px;
    font-size: 13.5px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    box-shadow: 0 5px 18px rgba(0, 112, 243, 0.4);
    transition: all 0.2s ease;
}
.kc-btn-hero-blue:hover {
    transform: translateY(-2px);
    box-shadow: 0 9px 25px rgba(0, 188, 212, 0.5);
    background: linear-gradient(135deg, #0080FF 0%, #0070F3 100%);
}

.kc-btn-hero-outline {
    background: rgba(15, 23, 42, 0.6);
    border: 1.5px solid rgba(0, 188, 212, 0.4);
    color: #ffffff !important;
    border-radius: 9px;
    padding: 11px 22px;
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    backdrop-filter: blur(8px);
    transition: all 0.2s ease;
}
.kc-btn-hero-outline:hover {
    border-color: #00BCD4;
    background: rgba(0, 188, 212, 0.15);
    color: #00BCD4 !important;
    transform: translateY(-2px);
}

/* Right Showcase Card */
.kc-hero-showcase-card {
    position: relative;
    z-index: 10;
    border-radius: 18px;
    padding: 7px;
    background: rgba(15, 23, 42, 0.4);
    border: 1.5px solid rgba(0, 188, 212, 0.3);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    overflow: hidden;
}

.kc-hero-showcase-img {
    width: 100%;
    max-height: 290px;
    object-fit: cover;
    border-radius: 12px;
    display: block;
}

@media (max-width: 991px) {
    .kc-hero-tech-section { padding: 20px 0; }
    .kc-hero-headline { font-size: 26px; }
    .kc-hero-description { font-size: 12.5px; }
    .kc-hero-showcase-card { margin-top: 20px; }
}
</style>

<!-- ══════════════════════════════════════════════════════════
     COMPACT HERO TECH BANNER SECTION
══════════════════════════════════════════════════════════ -->
<section class="kc-hero-tech-section">
    <div class="kc-hero-glow-blob"></div>

    <div class="container" style="position: relative; z-index: 10;">
        <div class="row align-items-center">
            
            <!-- Left Text Content -->
            <div class="col-lg-6">
                <div class="kc-hero-badge-sub">
                    <i class="fa fa-microchip" style="font-size:11px;"></i> NEXT-GEN TECH & PERFORMANCE
                </div>
                
                <h1 class="kc-hero-headline">
                    <span class="kc-text-white">BUILD BETTER.</span>
                    <span class="kc-text-cyan">PERFORM FASTER.</span>
                </h1>

                <p class="kc-hero-description">
                    Premium Computers, Laptops & Accessories For Work, Play & Everything In Between. Built with precision and unmatched reliability.
                </p>

                <div class="kc-hero-btn-group">
                    <a href="allproducts.php" class="kc-btn-hero-blue">
                        <i class="fa-solid fa-bag-shopping"></i> SHOP NOW
                    </a>
                    <a href="category_list.php?search=Gaming" class="kc-btn-hero-outline">
                        <i class="fa-solid fa-headset"></i> BUILD CUSTOM PC
                    </a>
                </div>
            </div>

            <!-- Right Showcase Card -->
            <div class="col-lg-6">
                <div class="kc-hero-showcase-card">
                    <a href="allproducts.php">
                        <img src="img/karuda_hero_gaming_pc.jpg" alt="Gaming PC Setup" class="kc-hero-showcase-img" onerror="this.src='img/karuda_hero_laptop.jpg'">
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>