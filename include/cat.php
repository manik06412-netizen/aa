<?php
/**
 * include/cat.php
 * Karuda Computers — usedlaptopstore.in Style "All categories" Carousel Section
 */
if (!isset($con) || !$con) {
    if (class_exists('\\App\\Core\\Database')) {
        $con = \App\Core\Database::getInstance()->getConnection();
    } elseif (file_exists(__DIR__ . '/../dbconnect.php')) {
        require_once __DIR__ . '/../dbconnect.php';
    }
}

// Fetch categories from DB — sorted by admin order
$categories_list = [];
if (isset($con) && $con) {
    $c_q = mysqli_query($con, "SELECT c_id, c_name, icon, fpath FROM res_category ORDER BY orderr ASC, c_id ASC");
    if ($c_q && mysqli_num_rows($c_q) > 0) {
        while ($cr = mysqli_fetch_assoc($c_q)) {
            $categories_list[] = $cr;
        }
    }
}
// Fallback categories if empty
if (empty($categories_list)) {
    $categories_list = [
        ['c_id' => 1, 'c_name' => 'Laptops & Notebooks', 'icon' => 'img/categories/cat_laptop.jpg'],
        ['c_id' => 2, 'c_name' => 'Desktop PCs & Workstations', 'icon' => 'img/categories/cat_desktop.jpg'],
        ['c_id' => 3, 'c_name' => 'Components & Graphics Cards', 'icon' => 'img/categories/cat_component.jpg'],
        ['c_id' => 4, 'c_name' => 'Gaming Monitors & Displays', 'icon' => 'img/categories/cat_monitor.jpg'],
        ['c_id' => 5, 'c_name' => 'Computer Accessories', 'icon' => 'img/categories/cat_accessory.jpg'],
        ['c_id' => 8, 'c_name' => 'Gaming Rigs & Audio', 'icon' => 'img/categories/cat_gaming.jpg'],
    ];
}
?>

<style>
/* ══════════════════════════════════════════════════════════
   KARUDA COMPUTERS — PREMIUM 3D CATEGORIES SECTION
══════════════════════════════════════════════════════════ */
.uls-categories-section {
    background: #F8FAFC;
    padding: 16px 0 10px 0;
    position: relative;
    border-bottom: 1px solid #E2E8F0;
}


.uls-view-all-link {
    color: #0070F3;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}
.uls-view-all-link:hover {
    color: #0056B3;
    transform: translateX(3px);
}

/* Category Track Container */
.uls-cat-track-wrap {
    position: relative;
}

.uls-cat-track {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 4px 4px 6px 4px;
}
@media (max-width: 991px) {
    .uls-cat-track {
        justify-content: flex-start;
    }
}
.uls-cat-track::-webkit-scrollbar {
    display: none;
}

/* Circular 3D Category Card (Matching Hand-drawn Sketch) */
.uls-cat-item-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none !important;
    flex: 0 0 95px;
    width: 95px;
}

.uls-cat-card {
    flex: 0 0 76px;
    width: 76px;
    height: 76px;
    background: #FFFFFF;
    border: 2px solid #E2E8F0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4px;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(11, 25, 44, 0.06);
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    position: relative;
    box-sizing: border-box;
}

.uls-cat-item-wrap:hover .uls-cat-card,
.uls-cat-card:hover {
    transform: translateY(-3px) scale(1.05);
    border-color: #0070F3 !important;
    box-shadow: 0 8px 20px rgba(0, 112, 243, 0.22) !important;
    background: #FFFFFF;
}

.uls-cat-img-box {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid rgba(0, 112, 243, 0.15);
    background: #F8FAFC;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 6px;
    transition: all 0.3s ease;
}

.uls-cat-item-wrap:hover .uls-cat-img-box {
    border-color: #0070F3;
    background: #EFF6FF;
}

.uls-cat-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    filter: drop-shadow(0 3px 6px rgba(0, 112, 243, 0.12));
    transition: transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
}
.uls-cat-item-wrap:hover .uls-cat-img {
    transform: scale(1.12);
}

.uls-cat-name {
    font-size: 12px;
    font-weight: 700;
    color: #0F172A;
    text-align: center;
    margin-top: 6px;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
    font-family: 'Outfit', 'Poppins', sans-serif;
    transition: color 0.2s ease;
}
.uls-cat-item-wrap:hover .uls-cat-name {
    color: #0070F3;
}

/* Scroll Arrow Button - Mobile only */
.uls-cat-scroll-next {
    position: absolute;
    right: -14px;
    top: 50%;
    transform: translateY(-60%);
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    color: #0F172A;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    display: none;  /* hidden by default, show only on mobile */
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.22s ease;
}
.uls-cat-scroll-next:hover {
    background: #0070F3;
    color: #ffffff;
    border-color: #0070F3;
    transform: translateY(-60%) scale(1.1);
    box-shadow: 0 6px 18px rgba(0, 112, 243, 0.4);
}


@media (max-width: 576px) {
    .uls-sec-title { font-size: 18px; }
    .uls-cat-card { flex: 0 0 80px; width: 80px; height: 80px; border-radius: 50%; }
    .uls-cat-item-wrap { flex: 0 0 80px; width: 80px; }
    .uls-cat-img-box { width: 100%; height: 100%; }
    .uls-cat-name { font-size: 11px; }
    /* Show scroll button on mobile */
    .uls-cat-scroll-next { display: flex; }
}
@media (min-width: 577px) {
    /* Keep scroll button hidden on desktop/tablet */
    .uls-cat-scroll-next { display: none !important; }
}
</style>

<!-- ══════════════════════════════════════════════════════════
     ALL CATEGORIES SECTION (WITH 3D ICONS)
══════════════════════════════════════════════════════════ -->
<section class="uls-categories-section">
    <div class="container">

        <!-- Category Horizontal Track -->
        <div class="uls-cat-track-wrap">
            <div class="uls-cat-track" id="ulsCatTrack">
                <?php foreach ($categories_list as $cat):
                    $cname = $cat['c_name'];

                    // Short display name — for known categories, use short name; else use actual name
                    if      (stripos($cname, 'Laptop') !== false)                                          $short_name = 'Laptops';
                    elseif  (stripos($cname, 'Desktop') !== false)                                         $short_name = 'Desktops';
                    elseif  (stripos($cname, 'Component') !== false)                                       $short_name = 'Components';
                    elseif  (stripos($cname, 'Monitor') !== false)                                         $short_name = 'Monitors';
                    elseif  (stripos($cname, 'Accessory') !== false || stripos($cname, 'Accessories') !== false) $short_name = 'Accessories';
                    elseif  (stripos($cname, 'Gaming') !== false)                                          $short_name = 'Gaming Gear';
                    else                                                                                    $short_name = $cname;

                    // Image path: prefer icon → fpath → category fallback
                    $img_file = !empty($cat['icon']) ? trim($cat['icon'])
                              : (!empty($cat['fpath']) ? trim($cat['fpath']) : '');

                    // If still empty OR path clearly missing, use default by category name
                    if (empty($img_file)) {
                        if      (stripos($cname, 'Laptop') !== false)                                          $img_file = 'img/categories/cat_laptop.jpg';
                        elseif  (stripos($cname, 'Desktop') !== false)                                         $img_file = 'img/categories/cat_desktop.jpg';
                        elseif  (stripos($cname, 'Component') !== false)                                       $img_file = 'img/categories/cat_component.jpg';
                        elseif  (stripos($cname, 'Monitor') !== false)                                         $img_file = 'img/categories/cat_monitor.jpg';
                        elseif  (stripos($cname, 'Accessory') !== false || stripos($cname, 'Accessories') !== false) $img_file = 'img/categories/cat_accessory.jpg';
                        else                                                                                    $img_file = 'img/categories/cat_gaming.jpg';
                    }
                ?>
                    <a href="category_list.php?search=<?php echo urlencode($cname); ?>" class="uls-cat-item-wrap" title="<?php echo htmlspecialchars($cname); ?>">
                        <div class="uls-cat-card">
                            <div class="uls-cat-img-box">
                                <img src="<?php echo htmlspecialchars($img_file); ?>"
                                     alt="<?php echo htmlspecialchars($cname); ?>"
                                     class="uls-cat-img"
                                     onerror="this.onerror=null; this.src='img/categories/cat_laptop.jpg'">
                            </div>
                        </div>
                        <span class="uls-cat-name"><?php echo htmlspecialchars($short_name); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Scroll Right Next Button -->
            <button type="button" class="uls-cat-scroll-next" id="ulsCatScrollNext" aria-label="Scroll Categories Right">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>

    </div>
</section>

<script>
(function() {
    const track = document.getElementById('ulsCatTrack');
    const btnNext = document.getElementById('ulsCatScrollNext');
    if (track && btnNext) {
        btnNext.addEventListener('click', function() {
            track.scrollBy({ left: 300, behavior: 'smooth' });
        });
    }
})();
</script>