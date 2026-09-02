<?php
// Ensure database connection is available
require_once __DIR__ . '/../dbconnect.php';

// Fetch the ad banners directly within this component so it works on ANY page
$kc_ad_banners = [];
if (isset($con) && $con) {
    // Fetch latest 4 promotional banners from banner table
    $q = mysqli_query($con, "SELECT id, fpath, link FROM banner ORDER BY id DESC LIMIT 4");
    if ($q && mysqli_num_rows($q) > 0) {
        while ($row = mysqli_fetch_assoc($q)) {
            $kc_ad_banners[] = $row;
        }
    }
}

// Only render the section if there are banners
if (!empty($kc_ad_banners)):
?>
<!-- ══════════════════════════════════════════════════════════
     KARUDA COMPUTERS — PROMOTIONAL AD BANNERS (STANDALONE)
══════════════════════════════════════════════════════════ -->
<style>
.kc-ad-banner-section {
    padding: 20px 0;
    margin-top: 30px;
    margin-bottom: 30px;
}
.kc-ad-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    align-items: center;
}
.kc-ad-grid.kc-single-banner {
    grid-template-columns: 1fr; /* Span full width if only 1 banner exists */
}
.kc-ad-item {
    width: 100%;
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    background: #000; /* Dark background behind image */
}
.kc-ad-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,112,243,0.15);
}
.kc-ad-img {
    width: 100%;
    height: 280px; /* Sleek, fixed cinematic height for desktop */
    object-fit: cover; /* Crop to fit perfectly without stretching */
    object-position: center center; /* Focus on the middle of the image */
    display: block;
    transition: transform 0.5s ease;
}
.kc-ad-item:hover .kc-ad-img {
    transform: scale(1.03);
}

/* Tablet screens */
@media (max-width: 992px) {
    .kc-ad-img {
        height: 220px;
    }
}

/* Mobile screens */
@media (max-width: 768px) {
    .kc-ad-grid {
        grid-template-columns: 1fr; /* Stack banners vertically on mobile */
        gap: 16px;
    }
    .kc-ad-img {
        height: 160px; /* Slimmer height for mobile */
    }
}
</style>

<section class="kc-ad-banner-section container">
    <div class="kc-ad-grid <?php echo (count($kc_ad_banners) === 1) ? 'kc-single-banner' : ''; ?>">
        <?php foreach ($kc_ad_banners as $ad): 
            // Handle image path correctly depending on where it's called from
            $imgRaw = $ad['fpath'];
            $imgSrc = function_exists('resolve_image_url') ? resolve_image_url($imgRaw) : 
                      ((strpos($imgRaw, 'http') === 0) ? $imgRaw : ((file_exists(__DIR__ . '/../' . $imgRaw)) ? $imgRaw : '../' . $imgRaw));
        ?>
        <div class="kc-ad-item">
            <a href="<?php echo htmlspecialchars(!empty($ad['link']) ? $ad['link'] : '#'); ?>">
                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Promotional Ad" class="kc-ad-img" onerror="this.style.display='none'">
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
